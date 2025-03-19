@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                <h1 class="card-title" style="font-size: 22px; font-weight: bold;">Detalles de la {{ $type }}</h1>
                <div class="card-actions btn-actions">
                        <a href="{{ route('transactions.index') }}" class="btn-action">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z"></path>
                                <path d="M18 6l-12 12"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fs-2">No.</label>
                            <div class="form-control form-control-solid" style="font-size: 18px;">{{ $transaction->id }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-2">Fecha</label>
                            <div class="form-control form-control-solid" style="font-size: 18px;">{{ $transaction->date ?? $transaction->order_date }}</div>
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        @if ($type === 'Compra')
                            <div class="col-md-6">
                                <label class="form-label fs-2">Proveedor</label>
                                <div class="form-control form-control-solid" style="font-size: 18px;">{{ $transaction->supplier->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-2">Total</label>
                                <div class="form-control form-control-solid" style="font-size: 18px;">Q{{ number_format($transaction->total_amount, 2) }}</div>
                            </div>
                        @elseif ($type === 'Venta')
                            <div class="col-md-6">
                                <label class="form-label fs-2">Cliente</label>
                                <div class="form-control form-control-solid" style="font-size: 18px;">{{ $transaction->customer->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-2">Total</label>
                                <div class="form-control form-control-solid" style="font-size: 18px;">Q{{ number_format($transaction->total, 2) }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle fs-2">
                            <thead >
                                <tr>
                                    <th>No.</th>
                                    <th>Foto</th>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->details as $detail)
                                    <tr class="hover:bg-gray-100 fs-2">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <img src="{{ $detail->product->product_image ? asset('storage/' . $detail->product->product_image) : asset('assets/img/products/default.webp') }}" style="max-height: 80px; max-width: 80px;">
                                        </td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->product->code }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>Q{{ number_format($detail->unitcost, 2) }}</td>
                                        <td>Q{{ number_format($detail->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
