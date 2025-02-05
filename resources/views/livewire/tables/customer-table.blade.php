<div class="card bg-white shadow-md rounded-lg">
    <div class="card-header flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-700">
            {{ __('Clientes') }}
        </h2>

        
        <div class="card-actions">
            <x-action.create route="{{ route('customers.create') }}" />
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
                <th class="px-7 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('id')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('ID') }}
                        @include('inclues._sort-icon', ['field' => 'id'])
                    </a>
                </th>
                <th class="px-7 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('name')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Nombre') }}
                        @include('inclues._sort-icon', ['field' => 'name'])
                    </a>
                </th>
                <th class="px-7 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('email')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Correo electrónico') }}
                        @include('inclues._sort-icon', ['field' => 'email'])
                    </a>
                </th>
                <th class="px-7 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    <a wire:click.prevent="sortBy('created_at')" href="#" role="button" class="hover:text-blue-500">
                        {{ __('Creado') }}
                        @include('inclues._sort-icon', ['field' => 'created_at'])
                    </a>
                </th>
                <th class="px-7 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    {{ __('Acción') }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($customers as $customer)
                <tr>
                    <td class="align-middle text-center">
                        {{ $loop->index }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->name }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->email }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $customer->created_at->diffForHumans() }}
                    </td>
                    <td class="align-middle text-center">
                        <x-button.show class="btn-icon" route="{{ route('customers.show', $customer->uuid) }}"/>
                        <x-button.edit class="btn-icon" route="{{ route('customers.edit', $customer->uuid) }}"/>
                        <x-button.delete 
                            class="btn-icon" 
                            route="{{ route('customers.destroy', $customer->uuid) }}" 
                            onclick="return confirm('¿Estás de eliminar cliente {{ $customer->name }}?')"
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
            Mostrando <span>{{ $customers->firstItem() }}</span> de <span>{{ $customers->lastItem() }}</span> de <span>{{ $customers->total() }}</span> registros
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $customers->links() }}
        </ul>
    </div>
</div>
