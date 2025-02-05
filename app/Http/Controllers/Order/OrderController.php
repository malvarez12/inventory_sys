<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Mail\StockAlert;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Str;

// Controlador para gestionar órdenes
class OrderController extends Controller
{
    // Muestra un resumen de las órdenes
    public function index()
    {
        // Cuenta las órdenes del usuario autenticado
        $orders = Order::where('user_id', auth()->id())->count();

        // Devuelve la vista con las órdenes
        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    // Muestra el formulario para crear una nueva orden
    public function create()
    {
        // Obtiene los productos del usuario autenticado con relaciones de categoría y unidad
        $products = Product::where('user_id', auth()->id())->with(['category', 'unit'])->get();

        // Obtiene los clientes del usuario autenticado
        $customers = Customer::where('user_id', auth()->id())->get(['id', 'name']);

        // Obtiene los productos en el carrito
        $carts = Cart::content();

        // Devuelve la vista con los datos necesarios
        return view('orders.create', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    // Guarda una nueva orden en la base de datos
    public function store(OrderStoreRequest $request)
    {
        // Crea la orden con los datos proporcionados
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'payment_type' => $request->payment_type,
            'pay' => $request->pay,
            'order_date' => Carbon::now()->format('Y-m-d'),
            'order_status' => OrderStatus::PENDING->value,
            'total_products' => Cart::count(),
            'sub_total' => Cart::subtotal(),
            'vat' => Cart::tax(),
            'total' => Cart::total(),
            'invoice_no' => IdGenerator::generate([
                'table' => 'orders',
                'field' => 'invoice_no',
                'length' => 10,
                'prefix' => 'INV-'
            ]),
            'due' => (Cart::total() - $request->pay),
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
        ]);

        // Crea los detalles de la orden
        $contents = Cart::content();
        $oDetails = [];

        foreach ($contents as $content) {
            $oDetails['order_id'] = $order['id'];
            $oDetails['product_id'] = $content->id;
            $oDetails['quantity'] = $content->qty;
            $oDetails['unitcost'] = $content->price;
            $oDetails['total'] = $content->subtotal;
            $oDetails['created_at'] = Carbon::now();

            OrderDetails::insert($oDetails);
        }

        // Limpia el carrito de compras
        Cart::destroy();

        // Redirige a la lista de órdenes con un mensaje de éxito
        return redirect()
            ->route('orders.index')
            ->with('success', 'Venta creada!');
    }

    // Muestra una orden específica
    public function show($uuid)
    {
        // Obtiene la orden por su UUID y carga relaciones faltantes
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->loadMissing(['customer', 'details'])->get();
        return view('orders.show', [
            'order' => $order
        ]);
    }

    // Actualiza una orden
    public function update($uuid, Request $request)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        // Reduce el stock de los productos asociados a la orden
        $products = OrderDetails::where('order_id', $order->id)->get();
        $stockAlertProducts = [];

        foreach ($products as $product) {
            $productEntity = Product::where('id', $product->product_id)->first();
            $newQty = $productEntity->quantity - $product->quantity;
            if ($newQty < $productEntity->quantity_alert) {
                $stockAlertProducts[] = $productEntity;
            }
            $productEntity->update(['quantity' => $newQty]);
        }

        // Envía alertas de stock bajo si corresponde
        if (count($stockAlertProducts) > 0) {
            $listAdmin = [];
            foreach (User::all('email') as $admin) {
                $listAdmin[] = $admin->email;
            }
            Mail::to($listAdmin)->send(new StockAlert($stockAlertProducts));
        }

        // Actualiza el estado de la orden
        $order->update([
            'order_status' => OrderStatus::COMPLETE,
            'due' => '0',
            'pay' => $order->total
        ]);

        return redirect()
            ->route('orders.complete')
            ->with('success', 'Venta completada!');
    }

    // Elimina una orden
    public function destroy($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->delete();
    }

    // Descarga una factura en PDF (vista para imprimir)
    public function downloadInvoice($uuid)
    {
        $order = Order::with(['customer', 'details'])->where('uuid', $uuid)->firstOrFail();

        return view('orders.print-invoice', [
            'order' => $order,
        ]);
    }

    // Cancela una orden
    public function cancel(Order $order)
    {
        $order->update([
            'order_status' => 2 // Cambia el estado a "cancelado".
        ]);
        $orders = Order::where('user_id', auth()->id())->count();

        return redirect()
            ->route('orders.index', [
                'orders' => $orders
            ])
            ->with('success', 'Venta cancelada!');
    }
}