<div class="card">
    <div class="card-header">
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
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive max-w-7xl mx-auto">
    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No.</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Tipo</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Fecha</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Total</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Acción</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($transactions as $transaction)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">{{ $loop->iteration }}</td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">{{ $transaction->type }}</td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">{{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">QTZ {{ number_format($transaction->total, 2) }}</td>
                    <td class="px-6 py-3 text-sm text-gray-500 text-center">
                    <a href="{{ route('transactions.show', ['id' => $transaction->id, 'type' => $transaction->type]) }}"
                                class="btn btn-icon btn-outline-info">
                                    {!! file_get_contents(public_path('assets/svg/eye.svg')) !!}
                            </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-3 text-sm text-gray-500 text-center">No se encontraron transacciones</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Mostrando {{ $transactions->firstItem() }} a {{ $transactions->lastItem() }} de {{ $transactions->total() }} registros
        </p>
        <div class="ms-auto">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
