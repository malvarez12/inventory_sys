<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Order; // Para ventas
use App\Models\Purchase; // Para compras
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\KardexExport;
use Maatwebsite\Excel\Facades\Excel;





class TransactionController extends Controller
{
    public function index()
{
    $purchases = Purchase::with('details.product')
    ->has('details') // <-- solo trae compras con detalles
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function ($purchase) {
        return (object)[
            'id' => $purchase->id,
            'type' => 'Compra',
            'date' => $purchase->created_at,
            'total' => $purchase->details->sum(fn($d) => $d->quantity * $d->unitcost),
            'details' => $purchase->details
        ];
    });

$orders = Order::with('details.product')
    ->has('details') // <-- solo trae ventas con detalles
    ->orderBy('created_at', 'desc')
    ->get()
    ->map(function ($order) {
        return (object)[
            'id' => $order->id,
            'type' => 'Venta',
            'date' => $order->created_at,
            'total' => $order->details->sum(fn($d) => $d->quantity * $d->rate),
            'details' => $order->details
        ];
    });

    $transactions = collect($purchases)->merge($orders)->sortByDesc('date')->values();
    $transactions = $this->paginate($transactions, 10);

return view('transactions.index', compact('transactions'));

}

public function exportKardex()
{
    $ventas = DB::table('order_details')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->select(
            'orders.order_date as fecha',
            DB::raw("'VENTA' as tipo"),
            'products.id as product_id',
            'products.name as producto',
            DB::raw('0 as entrada'),
            'order_details.quantity as salida',
            DB::raw('(order_details.quantity * order_details.unitcost) as total')
        );

    $compras = DB::table('purchase_details')
        ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
        ->join('products', 'purchase_details.product_id', '=', 'products.id')
        ->select(
            'purchases.date as fecha',
            DB::raw("'COMPRA' as tipo"),
            'products.id as product_id',
            'products.name as producto',
            'purchase_details.quantity as entrada',
            DB::raw('0 as salida'),
            DB::raw('(purchase_details.quantity * purchase_details.unitcost) as total')
        );

    // ¡AQUÍ VA EL ORDEN Y EJECUCIÓN!
    $movimientos = $compras
        ->unionAll($ventas)
        ->get()
        ->sortBy(function ($item) {
            return [
                \Carbon\Carbon::parse($item->fecha)->format('Y-m-d H:i:s'),
                $item->tipo === 'COMPRA' ? 1 : 2
            ];
        })
        ->values();

    // Calcular stock acumulado por producto
    $stocks = [];
    $kardexFinal = [];

    foreach ($movimientos as $movimiento) {
        $productoId = $movimiento->product_id;

        if (!isset($stocks[$productoId])) {
            $stocks[$productoId] = 0;
        }

        $stocks[$productoId] += $movimiento->entrada;
        $stocks[$productoId] -= $movimiento->salida;

        $kardexFinal[] = [
            'fecha' => $movimiento->fecha,
            'tipo' => $movimiento->tipo,
            'producto' => $movimiento->producto,
            'entrada' => $movimiento->entrada,
            'salida' => $movimiento->salida,
            'stock' => $stocks[$productoId],
            'total' => $movimiento->total,
        ];
    }

    return Excel::download(
        new KardexExport($kardexFinal),
        'Reporte-kardex.xlsx',
        \Maatwebsite\Excel\Excel::XLSX
    );
}




public function show($id, $type)
{
    $type = strtolower($type); // asegura que sea minúscula

    if ($type === 'venta') {
        $transaction = \App\Models\Order::with('details.product')->findOrFail($id);
    } elseif ($type === 'compra') {
        $transaction = \App\Models\Purchase::with('details.product')->findOrFail($id);
    } else {
        abort(404, 'Tipo de transacción no válido.');
    }

    return view('transactions.show', compact('transaction', 'type'));
}



    
    public function kardexDetalle()
{
    $ventas = DB::table('order_details')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->select(
            'orders.created_at as fecha',
            DB::raw("'VENTA' as tipo"),
            'products.name as producto',
            'products.code as codigo',
            DB::raw("0 as entrada"),
            'order_details.quantity as salida',
            'products.stock as stock_actual',
            DB::raw('(order_details.quantity * order_details.unitcoste) as total')
        );

    $compras = DB::table('purchase_details')
        ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
        ->join('products', 'purchase_details.product_id', '=', 'products.id')
        ->select(
            'purchases.purchase_date as fecha',
            DB::raw("'COMPRA' as tipo"),
            'products.name as producto',
            'products.code as codigo',
            'purchase_details.quantity as entrada',
            DB::raw("0 as salida"),
            'products.stock as stock_actual',
            DB::raw('(purchase_details.quantity * purchase_details.rate) as total')
        );

    $kardex = $compras->unionAll($ventas)->orderBy('fecha', 'desc')->get();

    return view('transactions.kardex', compact('kardex'));
}



public function kardexTabla()
{
    $ventas = DB::table('order_details')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->join('products', 'order_details.product_id', '=', 'products.id')
        ->select(
            'orders.created_at as fecha',
            DB::raw("'VENTA' as tipo"),
            'products.id as product_id',
            'products.name as producto',
            'order_details.quantity as salida',
            DB::raw('0 as entrada'),
            DB::raw('(order_details.quantity * order_details.unitcost) as total'),
            'order_details.id as id'
        );

    $compras = DB::table('purchase_details')
        ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
        ->join('products', 'purchase_details.product_id', '=', 'products.id')
        ->select(
            'purchases.created_at as fecha',
            DB::raw("'COMPRA' as tipo"),
            'products.id as product_id',
            'products.name as producto',
            'purchase_details.quantity as entrada',
            DB::raw('0 as salida'),
            DB::raw('(purchase_details.quantity * purchase_details.unitcost) as total'),
            'purchase_details.id as id'
        );

    $movimientos = $compras
        ->unionAll($ventas)
        ->get()
        ->sortByDesc(function ($item) {
            return [
                \Carbon\Carbon::parse($item->fecha)->timestamp,
                $item->tipo === 'VENTA' ? 1 : 2
            ];
        })
        ->values();

    $productStocks = Product::pluck('quantity', 'id')->toArray();
    $stocks = $productStocks;

    $kardex = [];

    foreach ($movimientos as $movimiento) {
        $productoId = $movimiento->product_id;

        $kardex[] = (object)[
            'id' => $movimiento->id,
            'fecha' => $movimiento->fecha,
            'tipo' => $movimiento->tipo,
            'producto' => $movimiento->producto,
            'entrada' => $movimiento->entrada,
            'salida' => $movimiento->salida,
            'stock' => $stocks[$productoId] ?? 0,
            'total' => $movimiento->total,
        ];

        $stocks[$productoId] -= $movimiento->entrada;
        $stocks[$productoId] += $movimiento->salida;
    }

    // Ordena el resultado final hacia adelante si quieres mostrar cronológicamente
    $kardex = collect($kardex)->sortBy('fecha')->values();

    return view('livewire.kardex-tabla', ['kardex' => $kardex]);
}






    /**
     * Paginar una colección manualmente.
     */
    protected function paginate(Collection $items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }
}
