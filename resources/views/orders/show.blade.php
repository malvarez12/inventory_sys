@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h1 class="card-title" style="font-size: 22px; font-weight: bold;">
                            {{ __('Detalles de venta') }}
                        </h1>
                    </div>

                    <div class="card-actions btn-actions">
                        @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                            <div class="dropdown">
                                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
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
                                            onclick="return confirm('¿Está seguro de que desea aprobar esta venta?')">
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
            <label for="order_date" class="form-label required" style="font-size: 18px; font-weight: bold;">
                {{ __('Fecha de venta') }}
            </label>
            <input type="text" id="order_date" class="form-control" 
                   style="font-size: 18px; padding: 10px;"
                   value="{{ $order->order_date->format('d-m-Y') }}" disabled>
        </div>

        <div class="col">
            <label for="invoice_no" class="form-label required" style="font-size: 18px; font-weight: bold;">
                {{ __('No. de comprobante') }}
            </label>
            <input type="text" id="invoice_no" class="form-control" 
                   style="font-size: 18px; padding: 10px;"
                   value="{{ $order->invoice_no }}" disabled>
        </div>

        <div class="col">
            <label for="customer" class="form-label required" style="font-size: 18px; font-weight: bold;">
                {{ __('Cliente') }}
            </label>
            <input type="text" id="customer" class="form-control" 
                   style="font-size: 18px; padding: 10px;"
                   value="{{ $order->customer->name }}" disabled>
        </div>

        <div class="col">
            <label for="payment_type" class="form-label required" style="font-size: 18px; font-weight: bold;">
                {{ __('Tipo de pago') }}
            </label>
            <input type="text" id="payment_type" class="form-control" 
                   style="font-size: 18px; padding: 10px;"
                   value="{{ $order->payment_type }}" disabled>
        </div>
    </div>
</div>

                    <div class="tabla-ventas">
    <table class="table table-bordered table-striped align-middle">
        <tbody>
            <tr>
                <th style="font-size: 18px;">No.</th>
                <th style="font-size: 18px;">Foto</th>
                <th style="font-size: 18px;">Nombre de Producto</th>
                <th style="font-size: 18px;">Código de Producto</th>
                <th style="font-size: 18px;">Cantidad</th>
                <th style="font-size: 18px;">Precio</th>
                <th style="font-size: 18px;">Total</th>
            </tr>
            @foreach ($order->details as $item)
                <tr>
                    <td class="align-middle text-center" style="font-size: 18px;">{{ $loop->iteration }}</td>
                    <td class="align-middle justify-content-center text-center">
                        <div style="max-height: 80px; max-width: 80px;">
                            <img class="img-fluid"
                                src="{{ $item->product->product_image ? asset('storage/' . $item->product->product_image) : asset('assets/img/products/default.webp') }}">
                        </div>
                    </td>
                    <td class="align-middle text-center" style="font-size: 18px;">
                        {{ $item->product->name }}
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge bg-indigo-lt" style="font-size: 18px;">
                            {{ $item->product->code }}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge bg-primary-lt" style="font-size: 18px;">
                            {{ $item->quantity }}
                        </span>
                    </td>
                    <td class="align-middle text-center" style="font-size: 18px;">
                        {{ number_format($item->unitcost, 2) }}
                    </td>
                    <td class="align-middle text-center" style="font-size: 18px;">
                        {{ number_format($item->total, 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="align-middle text-end" colspan="6" style="font-size: 18px;">
                    Monto pagado
                </td>
                <td class="align-middle text-center" style="font-size: 18px;">
                    {{ number_format($order->pay, 2) }}
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end" colspan="6" style="font-size: 18px;">
                    Monto pendiente
                </td>
                <td class="align-middle text-center" style="font-size: 18px;">
                    {{ number_format($order->due, 2) }}
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end" colspan="6" style="font-size: 18px;">
                    Total
                </td>
                <td class="align-middle text-center" style="font-size: 18px;">
                    {{ number_format($order->total, 2) }}
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end" colspan="6" style="font-size: 18px;">
                    Estado
                </td>
                <td class="align-middle text-center">
                    <span class="badge {{ $order->order_status === \App\Enums\OrderStatus::COMPLETE ? 'bg-success-lt' : ($order->order_status === \App\Enums\OrderStatus::PENDING ? 'bg-warning-lt' : '') }}" style="font-size: 18px;">
                        {{ $order->order_status->label() }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>



                <div class="card-footer text-end">
                    @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                        <form action="{{ route('orders.update', $order->uuid) }}" method="POST">
                            @method('put')
                            @csrf

                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('¿Estás seguro que quieres completar esta venta?')">
                                {{ __('Completar venta') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
