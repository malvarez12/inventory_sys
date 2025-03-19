<div class="card bg-white shadow-md rounded-lg">
    <div class="card-header flex items-center justify-between p-4 border-b">
        <h1 class="card-title" style="font-size: 22px; font-weight: bold;">
            {{ __('Ventas') }}
        </h1>

        <div class="card-actions">
            <x-action.create route="{{ route('orders.create') }}" />
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Mostrar
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                registros
            </div>
            <div class="ms-auto text-secondary">
                Buscar:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive max-w-7xl mx-auto">
        <table wire:loading.remove class="min-w-full divide-y divide-gray-200 border border-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('No.') }}
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('invoice_no')" href="#" class="hover:text-blue-500">
                            {{ __('No. de comprobante') }}
                            @include('inclues._sort-icon', ['field' => 'invoice_no'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('customer_id')" href="#" class="hover:text-blue-500">
                            {{ __('Cliente') }}
                            @include('inclues._sort-icon', ['field' => 'customer_id'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('order_date')" href="#" class="hover:text-blue-500">
                            {{ __('Fecha') }}
                            @include('inclues._sort-icon', ['field' => 'order_date'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('payment_type')" href="#" class="hover:text-blue-500">
                            {{ __('Pago') }}
                            @include('inclues._sort-icon', ['field' => 'payment_type'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('total')" href="#" class="hover:text-blue-500">
                            {{ __('Total') }}
                            @include('inclues._sort-icon', ['field' => 'sub_total'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('order_status')" href="#" class="hover:text-blue-500">
                            {{ __('Estado') }}
                            @include('inclues._sort-icon', ['field' => 'order_status'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('Acción') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-100">
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ $order->invoice_no }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ $order->customer->name }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ $order->order_date->format('d-m-Y') }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ $order->payment_type }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ Number::currency($order->sub_total, 'QTZ') }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            <x-status dot
                                color="{{ $order->order_status === \App\Enums\OrderStatus::COMPLETE ? 'green' : ($order->order_status === \App\Enums\OrderStatus::PENDING ? 'orange' : '') }}"
                                class="text-uppercase">
                                {{ $order->order_status->label() }}
                            </x-status>
                        </td>
                        <td class="align-middle text-center">
                            <x-button.show class="btn-icon" route="{{ route('orders.show', $order->uuid) }}" />
                            <x-button.print class="btn-icon"
                                route="{{ route('order.downloadInvoice', $order->uuid) }}" />
                            @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                                <x-button.delete class="btn-icon" route="{{ route('orders.cancel', $order) }}"
                                    onclick="return confirm('¿Está seguro de cancelar la orden de venta No. {{ $order->invoice_no }} ?')" />
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 text-center text-gray-500">
                            No se encontraron resultados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer flex items-center justify-between px-4 py-3">
        <p class="text-sm text-gray-500">
            Mostrando <span>{{ $orders->firstItem() }}</span> 
            de <span>{{ $orders->lastItem() }}</span> de
            <span>{{ $orders->total() }}</span> registros
        </p>
        <div class="pagination">
            {{ $orders->links() }}
        </div>
    </div>
</div>
