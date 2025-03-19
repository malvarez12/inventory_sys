<div class="card bg-white shadow-md rounded-lg">
    <div class="card-header flex items-center justify-between p-4 border-b">
        <<h1 class="card-title" style="font-size: 22px; font-weight: bold;">">
            {{ __('Proveedores') }}
        </h1>

        <div class="card-actions">
            <x-action.create route="{{ route('suppliers.create') }}" />
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
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">{{ __('No.') }}</th>
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Nombre') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('email')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Correo electrónico') }}
                        @include('inclues._sort-icon', ['field' => 'email'])
                    </a>
                </th>
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('shopname')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Nombre de tienda') }}
                        @include('inclues._sort-icon', ['field' => 'shopname'])
                    </a>
                </th>
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('type')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Tipo de proveedor') }}
                        @include('inclues._sort-icon', ['field' => 'type'])
                    </a>
                </th>
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('created_at')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Creado') }}
                        @include('inclues._sort-icon', ['field' => 'created_at'])
                    </a>
                </th>
                <th class="px-5 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    {{ __('Acción') }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($suppliers as $supplier)
                <tr>
                    <td class="align-middle text-center">
                        {{ $loop->index }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $supplier->name }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $supplier->email }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $supplier->shopname }}
                    </td>
                    <td class="align-middle text-center">
                    <span class="badge bg-primary text-white text-uppercase">
                        {{ $supplier->type->label() }}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="">
                            {{ $supplier->created_at->diffForHumans() }}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <x-button.show class="btn-icon" route="{{ route('suppliers.show', $supplier->uuid) }}"/>
                        <x-button.edit class="btn-icon" route="{{ route('suppliers.edit', $supplier->uuid) }}"/>
                        <x-button.delete 
                            class="btn-icon" 
                            route="{{ route('suppliers.destroy', $supplier->uuid) }}" 
                            onclick="return confirm('¿Estás de eliminar proveedor {{ $supplier->name }}?')"
                        />
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
        <p class="m-0 text-secondary">
            Mostrando <span>{{ $suppliers->firstItem() }}</span> 
            de <span>{{ $suppliers->lastItem() }}</span> de <span>{{ $suppliers->total() }}</span> registros
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $suppliers->links() }}
        </ul>
    </div>
</div>
