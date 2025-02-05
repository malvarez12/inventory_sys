@extends('layouts.tabler')

@section('content')
<div class="page-body">
    @if(!$customers)
        <x-empty
            title="Clientes no encontrados"
            message="Intenta ajustar tu búsqueda o filtro para encontrar lo que estás buscando."
            button_label="{{ __('Agregar cliente') }}"
            button_route="{{ route('customers.create') }}"
        />
    @else
        <div class="container-xl">

            {{---
            <div class="card">
                <div class="card-body">
                    <livewire:power-grid.customers-table/>
                </div>
            </div>
            ---}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <h3 class="mb-1">Hecho</h3>
                    <p>{{ session('success') }}</p>

                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif
            @livewire('tables.customer-table')
        </div>
    @endif
</div>
@endsection
