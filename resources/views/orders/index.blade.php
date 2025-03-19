@extends('layouts.tabler')

@section('content')
<div class="page-body">
    @if (!$orders)
        <x-empty
            title="No hay ventas encontradas"
            message="Intenta ajustar tu búsqueda o filtro para encontrar lo que estás buscando."
            button_label="{{ __('Agregar primera venta') }}"
            button_route="{{ route('orders.create') }}"
        />
    @else
    <div class="container-xl">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <h3 class="mb-1">Hecho</h3>
                <p>{{ session('success') }}</p>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        @livewire('tables.order-table')
    </div>
    @endif
</div>
@endsection

