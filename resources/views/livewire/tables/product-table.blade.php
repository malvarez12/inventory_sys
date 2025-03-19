<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">
                {{ __('Productos') }}
            </h2>
        </div>

        <div class="card-actions btn-group">
            <div class="dropdown">
                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <x-icon.vertical-dots />
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="">
                    <a href="{{ route('products.create') }}" class="dropdown-item">
                        <x-icon.plus />
                        {{ __('Crear producto') }}
                    </a>
                    <a href="{{ route('products.export.store') }}" class="dropdown-item">
                        <x-icon.plus />
                        {{ __('Exportar producto') }}
                    </a>
                </div>
            </div>
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
                entradas
            </div>
            <div class="ms-auto text-secondary">
                Buscar:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                        aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive max-w-7xl mx-auto">
    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('No.') }}
                    </th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('Foto') }}
                    </th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('name')" href="#" class="hover:text-blue-500">
                            {{ __('Nombre') }}
                            @include('inclues._sort-icon', ['field' => 'name'])
                        </a>
                    </th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('code')" href="#" class="hover:text-blue-500">
                            {{ __('Código') }}
                            @include('inclues._sort-icon', ['field' => 'code'])
                        </a>
                    </th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('category_id')" href="#" class="hover:text-blue-500">
                            {{ __('Categoría') }}
                            @include('inclues._sort-icon', ['field' => 'category_id'])
                        </a>
                    </th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        <a wire:click.prevent="sortBy('quantity')" href="#" class="hover:text-blue-500">
                            {{ __('Cantidad de stock') }}
                            @include('inclues._sort-icon', ['field' => 'quantity'])
                        </a>
                        </th>
                    <th class="px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                        {{ __('Acción') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-100">
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            <img style="width: 90px;" src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('assets/img/products/default.webp') }}" alt="">
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            {{ $product->name }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            {{ $product->code }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            {{ $product->category ? $product->category->name : '--' }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            {{ $product->quantity }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-500 text-center">
                            <x-button.show class="btn-icon" route="{{ route('products.show', $product->uuid) }}" />
                            <x-button.edit class="btn-icon" route="{{ route('products.edit', $product->uuid) }}" />
                            <x-button.delete class="btn-icon" route="{{ route('products.destroy', $product->uuid) }}" onclick="return confirm('Estás seguro de eliminar {{ $product->name }}?')" />
                            <x-button.delete class="btn-icon" route="{{ route('products.destroy', $product->uuid) }}"
                                onclick="return confirm('¿Estás de eliminar {{ $product->name }}?')" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="align-middle text-center" colspan="7">
                            No se encontró
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Mostrando <span>{{ $products->firstItem() }}</span> de <span>{{ $products->lastItem() }}</span> de <span>{{ $products->total() }}</span> registros
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $products->links() }}
        </ul>
    </div>
</div>
