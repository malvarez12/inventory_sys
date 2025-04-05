<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class KardexTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'fecha';
    public $sortAsc = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
        }
    }

    public function render()
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
            DB::raw('(order_details.quantity * order_details.unitcost) as total'),
            'orders.id as id'
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
            DB::raw('(purchase_details.quantity * purchase_details.unitcost) as total'),
            'purchases.id as id'
        );

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
    
        

    $stocks = [];
    $kardex = [];

    foreach ($movimientos as $movimiento) {
        $productoId = $movimiento->product_id;

        if (!isset($stocks[$productoId])) {
            $stocks[$productoId] = 0;
        }

        $stocks[$productoId] += $movimiento->entrada;
        $stocks[$productoId] -= $movimiento->salida;

        $kardex[] = (object)[
            'id' => $movimiento->id,
            'fecha' => $movimiento->fecha,
            'tipo' => $movimiento->tipo,
            'producto' => $movimiento->producto,
            'entrada' => $movimiento->entrada,
            'salida' => $movimiento->salida,
            'stock' => $stocks[$productoId],
            'total' => $movimiento->total,
        ];
    }

    // Paginación
    $page = request()->get('page', 1);
    $perPage = $this->perPage;
    $kardexCollection = collect($kardex);
    $pagedData = new \Illuminate\Pagination\LengthAwarePaginator(
        $kardexCollection->forPage($page, $perPage),
        $kardexCollection->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('livewire.kardex-table', [
        'kardex' => $pagedData,
        'sortField' => $this->sortField,
        'sortAsc' => $this->sortAsc
    ]);
}
}
