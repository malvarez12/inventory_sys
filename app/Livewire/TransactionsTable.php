<?php

namespace App\Livewire;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\Button;
use Livewire\WithPagination;
use App\Models\Purchase;
use App\Models\Order;

class TransactionsTable extends Component
{
    use WithPagination;

    public $search = ''; // Campo de búsqueda
    public $perPage = 5; // Número de registros por página
    public $sortField = 'date'; // Campo para ordenar (por defecto: fecha)
    public $sortAsc = true; // Orden ascendente por defecto

    public function updatingSearch()
    {
        $this->resetPage(); // Reinicia la paginación al buscar
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc; // Alterna entre ascendente y descendente
        } else {
            $this->sortField = $field;
            $this->sortAsc = true; // Reinicia a ascendente al cambiar de campo
        }
    }

    public function render()
    {
        // Obtener compras con los campos renombrados
        $purchases = Purchase::query()
            ->select('id', 'created_at as date', 'total_amount as total', \DB::raw("'Compra' as type"));
    
        // Obtener ventas con los campos renombrados
        $orders = Order::query()
            ->select('id', 'order_date as date', 'total', \DB::raw("'Venta' as type"));
    
        // Unir ambas consultas y ordenar antes de la paginación
        $transactions = \DB::table(\DB::raw("({$purchases->toSql()} UNION ALL {$orders->toSql()}) as transactions"))
            ->mergeBindings($purchases->getQuery()) // Evita errores de parámetros en Laravel
            ->mergeBindings($orders->getQuery())
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%');
            })
            ->orderBy('date', 'desc') // Ordenar por la fecha en orden descendente
            ->paginate($this->perPage);
    
        return view('livewire.transactions-table', [
            'transactions' => $transactions,
        ]);
    }    

}
