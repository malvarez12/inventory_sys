@extends('layouts.tabler')

@section('content')
<div class="page-body">
    @if(count($purchases) == 0)
        <x-empty
            title="No hay compras aprobadas encontradas"
            message="Ajusta los filtros para encontrar lo que estás buscando."
            button_label="{{ __('Agregar tu primer compra') }}"
            button_route="{{ route('purchases.create') }}"
        />
    @else
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                <h2 class="text-lg font-semibold text-gray-700">
                        {{ __('Compras: ') }}
                        <x-status dot
                            color="green"
                            class="text-uppercase">
                            {{ __('Aprobadas') }}
                        </x-status>
                    </h2>
                </div>

                    <div class="card-actions">
                        <a href="{{ route('purchases.create') }}" class="btn btn-icon btn-outline-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        </a>
                    </div>
                </div>
                <div class="table-responsive max-w-7xl mx-auto">
    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No.</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No. de Compra</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Proveedor</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Fecha</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Total</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Acción</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($purchases as $purchase)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                        {{ $purchase->purchase_no }}
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                        {{ $purchase->supplier->name }}
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                        {{ $purchase->created_at->format('d-m-Y') }}
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                        {{ Number::currency($purchase->total_amount, 'QTZ') }}
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                    <a href="{{ route('purchases.show', $purchase->uuid) }}" class="btn btn-icon btn-outline-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{--- ---}}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
