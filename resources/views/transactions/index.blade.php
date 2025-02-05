@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        @if (!$transactions)
            <x-empty title="No hay transacciones encontradas" 
                     message="Intenta ajustar tu búsqueda o filtro para encontrar lo que estás buscando."
                     button_label="{{ __('Agregar primera transacción') }}" 
                     button_route="{{ route('transactions.create') }}" 
                     />
                    @else
            <div class="container-xl">
                    @livewire('transactions-table')
                @endif
    </div>
    @endsection
       



