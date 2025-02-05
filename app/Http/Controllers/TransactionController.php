<?php

namespace App\Http\Controllers;

use App\Models\Order; // Para ventas
use App\Models\Purchase; // Para compras
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;


class TransactionController extends Controller
{
    public function index()
    {
        // Obtén todas las compras y ventas
        $purchases = Purchase::query()->get()->map(function ($purchase) {
            return [
                'id' => $purchase->id,
                'type' => 'Compra',
                'date' => Carbon::parse($purchase->created_at), // Convertir explícitamente a Carbon
            ];
        });
        
        $orders = Order::query()->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'type' => 'Venta',
                'date' => Carbon::parse($order->created_at), // Convertir explícitamente a Carbon
            ];
        });
        

        // Combinar ambas colecciones y ordenar por fecha
        $transactions = $purchases->merge($orders)->sortByDesc('date');

        // Paginar la colección combinada
        $transactions = $this->paginate($transactions, 10); // 10 registros por página

        return view('transactions.index', compact('transactions'));
    }

    public function show($id, $type)
    {
        if ($type === 'Compra') {
            // Busca la transacción como una compra
            $transaction = Purchase::with(['details.product', 'supplier', 'user'])->findOrFail($id);
    
            return view('transactions.show', [
                'transaction' => $transaction,
                'type' => $type,
            ]);
        } elseif ($type === 'Venta') {
            // Busca la transacción como una venta
            $transaction = Order::with(['details.product', 'customer'])->findOrFail($id);
    
            return view('transactions.show', [
                'transaction' => $transaction,
                'type' => $type,
            ]);
        }
    
        abort(404, 'Transacción no encontrada');
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
