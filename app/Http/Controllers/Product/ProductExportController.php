<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductExportController extends Controller
{
    public function create()
    {
        $products = Product::all()->sortBy('product_name');

        $product_array[] = array(
            'Producto',
            'Slug',
            'No. de Categoría',
            'No. de Unidad',
            'Código de producto',
            'Cantidad de stock',
            'Alerta de stock',
            'Precio de compra',
            'Precio de venta',
            'Nota'
        );

        foreach ($products as $product) {
            $product_array[] = array(
                'Nombre de producto' => $product->name,
                'Slug' => $product->slug,
                'No. Categoría' => $product->category_id,
                'No. Unidad' => $product->unit_id,
                'Código de producto' => $product->code,
                'Inventario' => $product->quantity,
                'Alerta de inventario' => $product->quantity_alert,
                'Precio de compra' => $product->buying_price,
                'Precio de venta' => $product->selling_price,
                'Nota' => $product->note
            );
        }

        $this->store($product_array);
    }

    public function store($products)
    {
        ini_set('max_execution_time', 0); // Evita que el script se detenga si tarda mucho tiempo
        ini_set('memory_limit', '4000M'); // Aumenta el límite de memoria

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($products);

            // Cambiar a formato Xlsx (más moderno y recomendado)
            $Excel_writer = new Xlsx($spreadSheet);

            // Establecer los encabezados correctos para la exportación en formato .xlsx
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="products.xlsx"');
            header('Cache-Control: max-age=0');

            // Comenzar el buffer de salida
            ob_start();

            // Limpiar cualquier salida previa para evitar que dañe el archivo
            ob_clean();

            // Guardar el archivo y enviarlo al navegador
            $Excel_writer->save('php://output');

            // Limpiar el buffer de salida y finalizar
            ob_end_flush();
            exit();
        } catch (Exception $e) {
            // Puedes registrar el error en los logs para facilitar el debugging
            error_log($e->getMessage());
            return response()->json(['error' => 'Error al generar el archivo Excel'], 500);
        }
    }
}
