<?php

namespace App\Http\Controllers\Purchase;


use App\Enums\PurchaseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Str;

// Controlador para gestionar compras
class PurchaseController extends Controller
{
    // Muestra el índice de compras
    public function index()
    {
        return view('purchases.index', [
            'purchases' => Purchase::where('user_id', auth()->id())->count() // Cuenta las compras del usuario autenticado.
        ]);
    }

    // Muestra las compras aprobadas
    public function approvedPurchases()
    {
        $purchases = Purchase::with(['supplier']) // Relación con el modelo Supplier.
            ->where('status', PurchaseStatus::APPROVED) // Filtra solo las aprobadas.
            ->get();

        return view('purchases.approved-purchases', [
            'purchases' => $purchases
        ]);
    }

    // Muestra los detalles de una compra específica
    public function show($uuid)
    {
        $purchase = Purchase::where('uuid', $uuid)->firstOrFail(); // Obtiene la compra por su UUID.
        $purchase->with(['supplier', 'details'])->get(); // Carga relaciones con proveedor y detalles.
        return view('purchases.edit', [
            'purchase' => $purchase
        ]);
    }

    // Edición de una compra (similar a show, muestra datos en un formulario)
    public function edit($uuid)
    {
        $purchase = Purchase::where('uuid', $uuid)->firstOrFail();
        $purchase->with(['supplier', 'details'])->get();
        return view('purchases.edit', [
            'purchase' => $purchase
        ]);
    }

    // Crea una nueva compra (muestra el formulario)
    public function create()
    {
        return view('purchases.create', [
            'categories' => Category::where('user_id', auth()->id())->select(['id', 'name'])->get(), // Categorías del usuario autenticado.
            'suppliers' => Supplier::where('user_id', auth()->id())->select(['id', 'name'])->get() // Proveedores del usuario autenticado.
        ]);
    }

    // Guarda una nueva compra en la base de datos
    public function store(StorePurchaseRequest $request)
    {
        // Valida que se hayan agregado productos a la factura
        if ($request->invoiceProducts == null || $request->invoiceProducts[0]['total'] == 0) {
            return redirect()->back()->with('error', 'Por favor, agrega un producto');
        }

        // Crea la compra con los datos proporcionados
        $purchase = Purchase::create([
            'purchase_no' => IdGenerator::generate([ // Genera un número único de compra.
                'table' => 'purchases',
                'field' => 'purchase_no',
                'length' => 10,
                'prefix' => 'PRS-'
            ]),
            'status' => PurchaseStatus::PENDING->value, // Estado inicial: pendiente.
            'created_by' => auth()->user()->id, // Usuario que creó la compra.
            'supplier_id.required' => $request->required,
            'supplier_id' => $request->supplier_id, // Proveedor asociado.
            'date' => $request->date, // Fecha de la compra.
            'total_amount' => $request->total_amount, // Monto total.
            'uuid' => Str::uuid(), // UUID único para la compra.
            'user_id' => auth()->id() // Usuario autenticado.
        ]);

        // Valida que haya productos en la factura y los guarda en detalles
        if (! $request->invoiceProducts == null) {
            $pDetails = [];
            foreach ($request->invoiceProducts as $product) {
                $pDetails['purchase_id'] = $purchase['id'];
                $pDetails['product_id'] = $product['product_id'];
                $pDetails['quantity'] = $product['quantity'];
                $pDetails['unitcost'] = intval($product['unitcost']);
                $pDetails['total'] = $product['total'];
                $pDetails['created_at'] = Carbon::now();

                // Inserta los detalles de la compra
                $purchase->details()->insert($pDetails);
            }
        }

        return redirect()->route('purchases.index')->with('success', 'Compra creada');
    }

    // Actualiza una compra existente
    public function update($uuid)
    {
        $purchase = Purchase::where('uuid', $uuid)->firstOrFail(); // Busca la compra por UUID.
        $products = PurchaseDetails::where('purchase_id', $purchase->id)->get(); // Obtiene los productos de la compra.

        // Incrementa el stock de los productos asociados
        foreach ($products as $product) {
            Product::where('id', $product->product_id)
                ->update(['quantity' => DB::raw('quantity+' . $product->quantity)]);
        }

        // Cambia el estado de la compra a aprobada
        $purchase->update([
            'status' => PurchaseStatus::APPROVED,
            'updated_by' => auth()->user()->id // Usuario que actualizó la compra.
        ]);

        return redirect()->back()->with('success', 'Compra aprobada');
    }

    // Elimina una compra
    public function destroy($uuid)
    {
        $purchase = Purchase::where('uuid', $uuid)->firstOrFail(); // Busca la compra por UUID.
        $purchase->delete(); // Elimina la compra.

        return redirect()->route('purchases.index')->with('success', 'Compra eliminada');
    }

    // Genera un reporte de compras del día
    public function purchaseReport()
    {
        $purchases = Purchase::with(['supplier'])
            ->where('date', today()->format('Y-m-d')) // Filtra compras del día actual.
            ->get();

        return view('purchases.report-purchase', [
            'purchases' => $purchases
        ]);
    }

    // Muestra la vista para generar un reporte de compras
    public function getPurchaseReport()
    {
        return view('purchases.report-purchase');
    }

    // Exporta un reporte de compras a Excel
    public function exportPurchaseReport(Request $request)
    {
        // Valida las fechas proporcionadas
        $rules = [
            'start_date' => 'required|string|date_format:Y-m-d',
            'end_date' => 'required|string|date_format:Y-m-d',
        ];
        $validatedData = $request->validate($rules);

        // Filtra las compras entre las fechas dadas
        $sDate = $validatedData['start_date'];
        $eDate = $validatedData['end_date'];

        $purchases = DB::table('purchase_details')
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join('users', 'users.id', '=', 'purchases.created_by')
            ->whereBetween('purchases.updated_at', [$sDate, $eDate])
            ->where('purchases.status', '1') // Solo compras aprobadas.
            ->select('purchases.purchase_no', 'purchases.updated_at', 'purchases.supplier_id', 'products.code', 'products.name', 'purchase_details.quantity', 'purchase_details.unitcost', 'purchase_details.total', 'users.name as created_by')
            ->get();

        // Crea un arreglo con los datos para exportar
        $purchase_array[] = ['Fecha', 'No. de Compra', 'Proveedor', 'Código de producto', 'Producto', 'Cantidad', 'Precio unitario', 'Total', 'Creado por'];
        foreach ($purchases as $purchase) {
            $purchase_array[] = [
                'Date' => $purchase->updated_at,
                'No Purchase' => $purchase->purchase_no,
                'Supplier' => $purchase->supplier_id,
                'Product Code' => $purchase->code,
                'Product' => $purchase->name,
                'Quantity' => $purchase->quantity,
                'Unitcost' => $purchase->unitcost,
                'Total' => $purchase->total,
                'Created By' => $purchase->created_by
            ];
        }

        // Exporta los datos a un archivo Excel
        $this->exportExcel($purchase_array);
    }

    // Maneja la exportación a Excel
    public function exportExcel($products)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($products);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="purchase-report.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return $e;
        }
    }
}
