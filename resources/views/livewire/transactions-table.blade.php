<div class="card bg-white shadow-md rounded-lg">
    <div class="card-header flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">
            {{ __('Transacciones') }}
        </h2>
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
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search transaction">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive max-w-7xl mx-auto">
        <table wire:loading.remove class="min-w-full divide-y divide-gray-200 border border-gray-300">
            <thead class="bg-gray-50 fs-2">
                <tr>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('No.') }}
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('type')" href="#" class="hover:text-blue-500">
                            {{ __('Tipo') }}
                            @include('inclues._sort-icon', ['field' => 'type'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('date')" href="#" class="hover:text-blue-500">
                            {{ __('Fecha') }}
                            @include('inclues._sort-icon', ['field' => 'date'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('total')" href="#" class="hover:text-blue-500">
                            {{ __('Total') }}
                            @include('inclues._sort-icon', ['field' => 'total'])
                        </a>
                    </th>
                    <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('Acción') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-100 fs-2">
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ $loop->iteration }}
                        </td>
                        
                    <td class="align-middle text-center">
                        <span class="badge text-white text-uppercase {{ $transaction->type === 'Venta' ? 'bg-success' : 'bg-info' }}">
                            {{ $transaction->type }}
                        </span>
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            QTZ {{ number_format($transaction->total, 2) }}
                        </td>
                        <td class="px-5 py-2 text-sm text-gray-500 text-center">
                            <a href="{{ route('transactions.show', ['id' => $transaction->id, 'type' => $transaction->type]) }}"
                               class="btn btn-icon btn-outline-info">
                                {!! file_get_contents(public_path('assets/svg/eye.svg')) !!}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-2 text-sm text-gray-500 text-center">
                            No se encontraron transacciones
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer flex items-center justify-between px-4 py-3">
        <p class="text-sm text-gray-500">
            Mostrando <span>{{ $transactions->firstItem() }}</span> 
            de <span>{{ $transactions->lastItem() }}</span> de
            <span>{{ $transactions->total() }}</span> registros
        </p>
        <div class="pagination">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
