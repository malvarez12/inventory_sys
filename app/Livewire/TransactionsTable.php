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
        // Obtenemos compras y ventas
        $purchases = Purchase::query()
            ->select('id', 'created_at as date', 'total_amount as total', \DB::raw("'Compra' as type"));

        $orders = Order::query()
            ->select('id', 'order_date as date', 'total', \DB::raw("'Venta' as type"));

        // Unimos ambos conjuntos
        $transactions = $purchases->unionAll($orders)
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc') // Orden dinámico
            ->paginate($this->perPage);

        return view('livewire.transactions-table', [
            'transactions' => $transactions,
        ]);
    }

}
