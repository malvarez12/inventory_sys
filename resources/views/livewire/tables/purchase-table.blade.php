<div class="card bg-white shadow-md rounded-lg">
<div class="card-header flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">
            {{ __('Compras') }}
        </h2>

        <div class="card-actions">
            <x-action.create route="{{ route('purchases.create') }}" />
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

    <x-spinner.loading-spinner/>

    <div class="table-responsive max-w-7xl mx-auto">
    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('No.') }}</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('purchase_no')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('No. de compra') }}
                        @include('inclues._sort-icon', ['field' => 'purchase_no'])
                    </a>
                </th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('supplier_id')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Proveedor') }}
                        @include('inclues._sort-icon', ['field' => 'supplier_id'])
                    </a>
                </th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('date')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Fecha') }}
                        @include('inclues._sort-icon', ['field' => 'date'])
                    </a>
                </th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('total_amount')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Total') }}
                        @include('inclues._sort-icon', ['field' => 'total_amount'])
                    </a>
                </th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('status')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Estado') }}
                        @include('inclues._sort-icon', ['field' => 'status'])
                    </a>
                </th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    {{ __('Acción') }}
                </th>
            </tr>
        </thead>
            @forelse ($purchases as $purchase)
                <tr>
                    <td class="align-middle text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $purchase->purchase_no }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $purchase->supplier->name }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $purchase->date->format('d-m-Y') }}
                    </td>
                    <td class="align-middle text-center">
                        {{ Number::currency($purchase->total_amount, 'QTZ') }}
                    </td>

                    @if ($purchase->status === \App\Enums\PurchaseStatus::APPROVED)
                        <td class="align-middle text-center">
                            <span class="badge bg-green text-white text-uppercase">
                                {{ __('Aprobada') }}
                            </span>
                        </td>
                        <td class="align-middle text-center">
                            <x-button.show class="btn-icon" route="{{ route('purchases.edit', $purchase->uuid) }}"/>
                        </td>
                    @else
                        <td class="align-middle text-center">
                            <span class="badge bg-orange text-white text-uppercase">
                                {{ __('Pendiente') }}
                            </span>
                        </td>
                        <td class="align-middle text-center" style="width: 10%">
                            <x-button.show class="btn-icon" route="{{ route('purchases.edit', $purchase->uuid) }}"/>
                            {{-- <x-button.complete class="btn-icon"  onclick="return confirm('¿Está seguro de aprobar la compra No. {{ $purchase->purchase_no }}!') route="{{ route('purchases.update', $purchase->uuid) }}"/> --}}
                            <x-button.complete class="btn-icon" route="{{ route('purchases.update', $purchase->uuid) }}" onclick="return confirm('¿Está seguro de aprobar la compra No. {{ $purchase->purchase_no }}?')"/>
                            <x-button.delete class="btn-icon" onclick="return confirm('Estás seguro!')" route="{{ route('purchases.delete', $purchase->uuid) }}"/>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td class="align-middle text-center" colspan="7">
                        No se encontraron resultados
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Mostrando <span>{{ $purchases->firstItem() }}</span>
            de <span>{{ $purchases->lastItem() }}</span> de <span>{{ $purchases->total() }}</span> registros
        </p>

        <ul class="pagination m-0 ms-auto">
        {{ $purchases->links() }}
        </ul>
    </div>
</div>
