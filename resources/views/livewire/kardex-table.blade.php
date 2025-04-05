<div class="container">


    <div class="bg-white p-6 rounded-lg shadow border">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Transacciones</h2>

<div class="flex justify-between items-center bg-white p-4 rounded-t-lg border border-b-0 shadow-sm">
    <div class="flex items-center text-gray-700">
        <label class="mr-2">Mostrar</label>
        <select wire:model="perPage" class="w-20 border border-gray-300 rounded-md text-sm px-2 py-1 focus:outline-none focus:ring focus:ring-blue-200">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
        </select>
        <span class="ml-2">registros</span>
    </div>

        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
        <a href="{{ route('kardex.export') }}" class="btn btn-success mb-3">
                Exportar a Excel
            </a>

            <thead class="bg-white text-gray-800 text-sm uppercase"> 
                <tr>
                    <th class="px-5 py-2 text-xs text-center">No.</th>

                    <th class="px-5 py-2 text-xs text-center">
                        <a wire:click.prevent="sortBy('tipo')" href="#" class="hover:text-blue-500">
                            Tipo
                            @include('inclues._sort-icon', ['field' => 'tipo', 'sortField' => $sortField, 'sortAsc' => $sortAsc])
                        </a>
                    </th>

                    <th class="px-5 py-2 text-xs text-center">
                        <a wire:click.prevent="sortBy('fecha')" href="#" class="hover:text-blue-500">
                            Fecha
                            @include('inclues._sort-icon', ['field' => 'fecha', 'sortField' => $sortField, 'sortAsc' => $sortAsc])
                        </a>
                    </th>

                    <th class="px-5 py-2 text-xs text-center">
                        <a wire:click.prevent="sortBy('producto')" href="#" class="hover:text-blue-500">
                            Producto
                            @include('inclues._sort-icon', ['field' => 'producto', 'sortField' => $sortField, 'sortAsc' => $sortAsc])
                        </a>
                    </th>

                    <th class="px-5 py-2 text-xs text-center">Entrada</th>
                    <th class="px-5 py-2 text-xs text-center">Salida</th>

                    <th class="px-5 py-2 text-xs text-center">
                        <a wire:click.prevent="sortBy('stock')" href="#" class="hover:text-blue-500">
                            Stock
                            @include('inclues._sort-icon', ['field' => 'stock', 'sortField' => $sortField, 'sortAsc' => $sortAsc])
                        </a>
                    </th>

                    <th class="px-5 py-2 text-xs text-center">
                        <a wire:click.prevent="sortBy('total')" href="#" class="hover:text-blue-500">
                            Total
                            @include('inclues._sort-icon', ['field' => 'total', 'sortField' => $sortField, 'sortAsc' => $sortAsc])
                        </a>
                    </th>

                    <th class="px-5 py-2 text-xs text-center">Acción</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kardex as $index => $item)
                    <tr class="hover:bg-gray-50 text-sm">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                        <span style="background-color: {{ $item->tipo === 'VENTA' ? '#16a34a' : '#1d4ed8' }}; color: white;"
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold shadow">
                                {{ strtoupper($item->tipo) }}
                            </span>
                            </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->fecha)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $item->producto }}</td>
                        <td class="text-center">{{ $item->entrada > 0 ? $item->entrada : '-' }}</td>
                        <td class="text-center">{{ $item->salida > 0 ? $item->salida : '-' }}</td>
                        <td class="text-center">{{ $item->stock }}</td>
                        <td class="text-center">Q{{ number_format($item->total, 2) }}</td>
                        <td class="text-center">
                        <a href="{{ route('transactions.show', ['id' => $item->id, 'type' => $item->tipo]) }}" class="btn btn-icon btn-outline-info">
                            {!! file_get_contents(public_path('assets/svg/eye.svg')) !!}
                        </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 py-2">No se encontraron registros</td>
                    </tr>
                @endforelse
            </tbody>

            <div class="mt-4">
    {{ $kardex->links() }}
</div>
        </table>
    </div>
</div>
