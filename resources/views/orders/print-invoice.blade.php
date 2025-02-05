<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Gestión de inventario
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet"
        href="{{ asset('assets/invoice/fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
</head>

<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>{{ Str::title(auth()->user()->store_name) }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h2>
                                            Comprobante de pago # <span>{{ $order->invoice_no }}</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <div class="invoice-number">
                                        <h4 class="inv-title-1">
                                            Fecha de comprobante:
                                        </h4>
                                        <p class="invo-addr-1">
                                            {{ $order->order_date }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <h4 class="inv-title-1">Cliente</h4>
                                    <p class="inv-from-1">{{ $order->customer->name }}</p>
                                    <p class="inv-from-1">{{ $order->customer->phone }}</p>
                                    <p class="inv-from-1">{{ $order->customer->email }}</p>
                                    <p class="inv-from-2">{{ $order->customer->address }}</p>
                                </div>
                                @php
                                    $user = auth()->user();
                                @endphp
                                <div class="col-sm-6 text-end mb-50">
                                <h4 class="inv-title-1">Tienda</h4>
                                        <p class="inv-from-1">Happy Colors</p>
                                        <p class="inv-from-1">Colonia Prados de Villahermosa</p>
                                        <p class="inv-from-1">Zona 7, San Miguel Petapa</p>
                                        <p class="inv-from-2">+502 3606-3908</p>
                                </div>
                            </div>
                        </div>
                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">Producto</th>
                                            <th class="align-middle text-center">Precio</th>
                                            <th class="align-middle text-center">Cantidad</th>
                                            <th class="align-middle text-center">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{--                                            @foreach ($orderDetails as $item) --}}
                                        @foreach ($order->details as $item)
                                            <tr>
                                                <td class="align-middle">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ Number::currency($item->unitcost, 'QTZ') }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ Number::currency($item->total, 'QTZ') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>
                                                    Subtotal
                                                </strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->sub_total, 'QTZ') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Impuesto(Incluido en precio)</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->vat, 'QTZ') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->total, 'QTZ') }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="invoice-informeshon-footer">
                                <ul>
                                    <li><a href="#">IG: Happy Colors GT</a></li>
                                    <li><a href="mailto:sales@hotelempire.com">Facebook: Happy Colors Guate</a></li>
                                    <li><a href="tel:+088-01737-133959">+502 3606-3908</a></li>
                                </ul>
                            </div> --}}
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Imprimir comprobante
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download">
                            <i class="fa fa-download"></i>
                            Descargar comprobante
                        </a>
                    </div>

                    {{-- back button --}}
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="{{ route('orders.index') }}" class="btn btn-lg btn-print">
                            <i class="fa fa-arrow-left"></i>
                            Atrás
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/invoice/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/html2canvas.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/app.js') }}"></script>
</body>

</html>
