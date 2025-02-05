@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            {{ __('Detalle de venta') }}
                        </h3>
                    </div>

                    <div class="card-actions btn-actions">
                        @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                            <div class="dropdown">
                                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"><!-- Download SVG icon from http://tabler-icons.io/i/dots-vertical -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                    </svg>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    <form action="{{ route('orders.update', $order->uuid) }}" method="POST">
                                        @csrf
                                        @method('put')

                                        <button type="submit" class="dropdown-item text-success"
                                            onclick="return confirm('¿Está seguro de que desea aprobar esta compra?')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-check" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>

                                            {{ __('Venta aprobada') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        <x-action.close route="{{ route('orders.index') }}" />
                    </div>
                </div>

                <div class="card-body">
                    <div class="row row-cards mb-3">
                        <div class="col">
                            <label for="order_date" class="form-label required">
                                {{ __('Fecha de compra') }}
                            </label>
                            <input type="text" id="order_date" class="form-control"
                                value="{{ $order->order_date->format('d-m-Y') }}" disabled>
                        </div>

                        <div class="col">
                            <label for="invoice_no" class="form-label required">
                                {{ __('No. de comprobante') }}
                            </label>
                            <input type="text" id="invoice_no" class="form-control" value="{{ $order->invoice_no }}"
                                disabled>
                        </div>

                        <div class="col">
                            <label for="customer" class="form-label required">
                                {{ __('Cliente') }}
                            </label>
                            <input type="text" id="customer" class="form-control" value="{{ $order->customer->name }}"
                                disabled>
                        </div>

                        <div class="col">
                            <label for="payment_type" class="form-label required">
                                {{ __('Tipo de pago') }}
                            </label>

                            <input type="text" id="payment_type" class="form-control" value="{{ $order->payment_type }}"
                                disabled>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="align-middle text-center">No.</th>
                                    <th scope="col" class="align-middle text-center">Foto</th>
                                    <th scope="col" class="align-middle text-center">Nombre de producto</th>
                                    <th scope="col" class="align-middle text-center">Códidgo de producto</th>
                                    <th scope="col" class="align-middle text-center">Cantidad</th>
                                    <th scope="col" class="align-middle text-center">Precio</th>
                                    <th scope="col" class="align-middle text-center">Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->details as $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <div style="max-height: 80px; max-width: 80px;">
                                                <img class="img-fluid"
                                                    src="{{ $item->product->product_image ? asset('storage/' . $item->product->product_image) : asset('assets/img/products/default.webp') }}">
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $item->product->code }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ number_format($item->unitcost, 2) }}
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ number_format($item->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-end">
                                        Monto pagado
                                    </td>
                                    <td class="text-center">{{ number_format($order->pay, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end">Monto pendiente</td>
                                    <td class="text-center">{{ number_format($order->due, 2) }}</td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="6" class="text-end">IVA</td>
                                    <td class="text-center">{{ number_format($order->vat, 2) }}</td>
                                </tr> -->
                                <tr>
                                    <td colspan="6" class="text-end">Total</td>
                                    <td class="text-center">{{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                <td colspan="6" class="text-end">Estado</td>
                                    <td class="text-center">
                                        <x-status dot
                                            color="{{ $order->order_status === \App\Enums\OrderStatus::COMPLETE ? 'green' : ($order->order_status === \App\Enums\OrderStatus::PENDING ? 'orange' : '') }}"
                                            class="text-uppercase">
                                            {{ $order->order_status->label() }}
                                        </x-status>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer text-end">
                    @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                        <form action="{{ route('orders.update', $order->uuid) }}" method="POST">
                            @method('put')
                            @csrf

                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('¿Estás de que quieres completar esta venta?')">
                                {{ __('Completar venta') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
