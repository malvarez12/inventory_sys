@extends('layouts.tabler')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h1 class="card-title" style="font-size: 22px; font-weight: bold;">
                        {{ __('Detalles de compra') }}
                    </h1>
                </div>

                <div class="card-actions btn-actions">
                    {{--- {{ URL::previous() }} ---}}
                    <a href="{{ route('purchases.index') }}" class="btn-action">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                    </a>
                </div>
            </div>


            <div class="card-body">
    <div class="row row-cards mb-3">
        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('Nombre') }}
            </label>
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->supplier->name }}" disabled>
        </div>

        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('Correo electrónico') }}
            </label>
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->supplier->email }}" disabled>
        </div>
    </div>

    <div class="row row-cards mb-3">
        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('Teléfono') }}
            </label>
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->supplier->phone }}" disabled>
        </div>

        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('Fecha de compra') }}
            </label>
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->date }}" disabled>
        </div>
    </div>

    <div class="row row-cards mb-3">
        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('No. de compra') }}
            </label>
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->purchase_no }}" disabled>
        </div>

        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('Total') }}
            </label>
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->total_amount }}" disabled>
        </div>
    </div>

    <div class="row row-cards mb-3">
        <div class="col">
            <label style="font-size: 18px; font-weight: bold;">
                {{ __('Dirección') }}
            </label>
            
            <input type="text" class="form-control form-control-solid" 
                   style="font-size: 16px; background-color: #f8f9fa;" 
                   value="{{ $purchase->supplier->address }}" disabled>
        </div>
    </div>
</div>

                
                <div class="tabla-compras">
    <table class="table table-bordered table-striped align-middle">
        <tbody>
            <tr>
                <th style="font-size: 18px;">No.</th>
                <th style="font-size: 18px;">Foto</th>
                <th style="font-size: 18px;">Nombre de Producto</th>
                <th style="font-size: 18px;">Código de Producto</th>
                <th style="font-size: 18px;">Stock Actual</th>
                <th style="font-size: 18px;">Cantidad</th>
                <th style="font-size: 18px;">Precio</th>
                <th style="font-size: 18px;">Total</th>
            </tr>
            @foreach ($purchase->details as $item)
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
                            {{ $item->product->quantity }}
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
            {{-- created by --}}
            <tr>
                <td class="align-middle text-end" colspan="7" style="font-size: 18px;">
                    Creado por
                </td>
                <td class="align-middle text-center" style="font-size: 18px;">
                    {{ $purchase->user->name }}
                </td>
            </tr>

            <tr>
                <td class="align-middle text-end" colspan="7" style="font-size: 18px;">
                    Porcentaje de impuesto
                </td>
                <td class="align-middle text-center" style="font-size: 18px;">
                    {{ number_format($purchase->tax_percentage, 2) }}
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end" colspan="7" style="font-size: 18px;">
                    Monto de impuesto
                </td>
                <td class="align-middle text-center" style="font-size: 18px;">
                    {{ number_format($purchase->tax_amount, 2) }}
                </td>
            </tr>

            <tr>
                <td class="align-middle text-end" colspan="7" style="font-size: 18px;">
                    Estado
                </td>
                <td class="align-middle text-center">
                    @if ($purchase->status->value == 1)
                        <span class="badge bg-success-lt" style="font-size: 18px;">
                            Aprobado
                        </span>
                    @elseif ($purchase->status->value == 0)
                        <span class="badge bg-warning-lt" style="font-size: 18px;">
                            Pendiente
                        </span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>



            <div class="card-footer text-end">
                @if ($purchase->status === \App\Enums\PurchaseStatus::PENDING)
                    <form action="{{ route('purchases.update', $purchase->uuid) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $purchase->id }}">

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm('¿¿Estás de que quieres aprobar esta compra?')"
                        >
                            {{ __('Aprobar compra') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
