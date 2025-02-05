<div class="card bg-white shadow-md rounded-lg">
    <div class="card-header flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">
            {{ __('Unidades') }}
        </h2>

        <div class="card-actions">
            <x-action.create route="{{ route('units.create') }}" />
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
    <table wire:loading.remove class="min-w-full divide-y divide-gray-200 border border-gray-300">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-7 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center w-1">
                    {{ __('ID') }}
                </th>
                <th class="px-7 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Nombre') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <th class="px-7 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center d-none d-sm-table-cell">
                    <a wire:click.prevent="sortBy('slug')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Slug') }}
                        @include('inclues._sort-icon', ['field' => 'slug'])
                    </a>
                </th>
                <th class="px-7 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('short_code')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Código de producto') }}
                        @include('inclues._sort-icon', ['field' => 'short_code'])
                    </a>
                </th>
                <th class="px-7 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    {{ __('Acción') }}
                </th>
            </tr>
        </thead>

            <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($units as $unit)
                <tr>
                    <td class="align-middle text-center" style="width: 10%">
                        {{ $loop->index }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $unit->name }}
                    </td>
                    <td class="align-middle text-center d-none d-sm-table-cell">
                        {{ $unit->slug }}
                    </td>
                    <td class="align-middle text-center" style="width: 15%">
                        {{ $unit->short_code }}
                    </td>
                    <td class="align-middle text-center" style="width: 15%">
                        <x-button.show class="btn-icon" route="{{ route('units.show', $unit) }}"/>
                        <x-button.edit class="btn-icon" route="{{ route('units.edit', $unit) }}"/>
                        <x-button.delete class="btn-icon" route="{{ route('units.destroy', $unit) }}"/>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="8">
                        No se encontraron resultados
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary d-none d-sm-block">
            Mostrando <span>{{ $units->firstItem() }}</span> de 
            <span>{{ $units->lastItem() }}</span> de <span>{{ $units->total() }}</span> registros
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $units->links() }}
        </ul>
    </div>
</div>
