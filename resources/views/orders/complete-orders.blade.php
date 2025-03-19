@extends('layouts.tabler')

@section('content')
<div class="page-body">
    @if($orders->isEmpty())
    <div class="empty">
        <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <circle cx="12" cy="12" r="9" />
                <line x1="9" y1="10" x2="9.01" y2="10" />
                <line x1="15" y1="10" x2="15.01" y2="10" />
                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" />
            </svg>
        </div>
        <p class="empty-title">
            No hay ventas encontradas
        </p>
        <p class="empty-subtitle text-secondary">
            Intenta ajustar tu búsqueda o filtro para encontrar lo que estás buscando.
        </p>
        <div class="empty-action">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                Agrega primer venta
            </a>
        </div>
    </div>
    @else
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                <h2 class="text-lg font-semibold text-gray-700">
                        {{ __('Ventas: ') }}
                        <x-status dot
                            color="green"
                            class="text-uppercase">
                            {{ __('Completada') }}
                        </x-status>
                    </h2>
                </div>

                <div class="card-actions">
                    <a href="{{ route('orders.create') }}" class="btn btn-icon btn-outline-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    </a>
                </div>
            </div>
            <div class="table-responsive max-w-7xl mx-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('No.') }}</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('No. de comprobante') }}</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Cliente') }}</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Fecha') }}</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Pago') }}</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Total') }}</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('Acción') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ $order->invoice_no }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ $order->customer->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ $order->order_date->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ $order->payment_type }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">{{ Number::currency($order->total, 'QTZ') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500 text-center">
                                <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-icon btn-outline-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </a>
                                <x-button.print class="btn-icon" route="{{ route('order.downloadInvoice', $order->uuid) }}" />
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
