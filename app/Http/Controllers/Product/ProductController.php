<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Str;

class ProductController extends Controller
{
    public function index()
    {
        // Cuenta el número de productos asociados al usuario autenticado.
        $products = Product::where("user_id", auth()->id())->count();

        // Retorna la vista del listado de productos con la cantidad de productos.
        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function create(Request $request)
    {
        // Obtiene todas las categorías y unidades asociadas al usuario autenticado.
        $categories = Category::where("user_id", auth()->id())->get(['id', 'name']);
        $units = Unit::where("user_id", auth()->id())->get(['id', 'name']);

        // Si hay un filtro por categoría, filtra las categorías por su slug.
        if ($request->has('category')) {
            $categories = Category::where("user_id", auth()->id())->whereSlug($request->get('category'))->get();
        }

        // Si hay un filtro por unidad, filtra las unidades por su slug.
        if ($request->has('unit')) {
            $units = Unit::where("user_id", auth()->id())->whereSlug($request->get('unit'))->get();
        }

        // Retorna la vista de creación de productos con las categorías y unidades.
        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        /**
         * Manejo de la subida de imágenes del producto.
         */
        $image = "";
        if ($request->hasFile('product_image')) {
            // Almacena la imagen en el directorio 'products' dentro del almacenamiento público.
            $image = $request->file('product_image')->store('products', 'public');
        }

        // Crea un nuevo producto con los datos proporcionados y genera un código único.
        Product::create([
            "code" => IdGenerator::generate([
                'table' => 'products',
                'field' => 'code',
                'length' => 4,
                'prefix' => 'PC'
            ]),

            'product_image'     => $image,
            'name'              => $request->name,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'quantity'          => $request->quantity,
            'buying_price'      => $request->buying_price,
            'selling_price'     => $request->selling_price,
            'quantity_alert'    => $request->quantity_alert,
            'tax'               => $request->tax,
            'tax_type'          => $request->tax_type,
            'notes'             => $request->notes,
            "user_id"           => auth()->id(), // Asigna el producto al usuario autenticado.
            "slug"              => Str::slug($request->name, '-'), // Genera el slug a partir del nombre del producto.
            "uuid"              => Str::uuid() // Genera un UUID único para el producto.
        ]);

        // Redirecciona al índice de productos con un mensaje de éxito.
        return to_route('products.index')->with('sucess', 'Producto agregado correctamente');
    }

    public function show($uuid)
    {
        // Busca el producto utilizando el UUID (identificador único global).
        $product = Product::where("uuid", $uuid)->firstOrFail();
        
        // Genera un código de barras para el producto basado en su código.
        $generator = new BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($product->code, $generator::TYPE_CODE_128);

        // Retorna la vista de detalle del producto con el producto y el código de barras.
        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    public function edit($uuid)
    {
        // Busca el producto a editar utilizando el UUID.
        $product = Product::where("uuid", $uuid)->firstOrFail();

        // Retorna la vista de edición del producto con las categorías, unidades y datos del producto.
        return view('products.edit', [
            'categories' => Category::where("user_id", auth()->id())->get(),
            'units' => Unit::where("user_id", auth()->id())->get(),
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, $uuid)
    {
        // Busca el producto por su UUID y realiza la actualización.
        $product = Product::where("uuid", $uuid)->firstOrFail();
        $product->update($request->except('product_image'));

        // Maneja la actualización de la imagen del producto.
        $image = $product->product_image;
        if ($request->hasFile('product_image')) {
            // Elimina la imagen antigua si existe.
            if ($product->product_image) {
                unlink(public_path('storage/') . $product->product_image);
            }
            // Almacena la nueva imagen.
            $image = $request->file('product_image')->store('products', 'public');
        }

        // Actualiza los demás campos del producto.
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-'); // Actualiza el slug con el nuevo nombre.
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->quantity = $request->quantity;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->quantity_alert = $request->quantity_alert;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->notes = $request->notes;
        $product->product_image = $image;
        $product->save();

        // Redirecciona al índice de productos con un mensaje de éxito.
        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado');
    }

    public function destroy($uuid)
    {
        // Busca el producto a eliminar por su UUID.
        $product = Product::where("uuid", $uuid)->firstOrFail();

        /**
         * Elimina la foto si existe.
         */
        if ($product->product_image) {
            // Verifica si la imagen existe en el sistema de archivos.
            if (file_exists(public_path('storage/') . $product->product_image)) {
                unlink(public_path('storage/') . $product->product_image);
            }
        }

        // Elimina el producto.
        $product->delete();

        // Redirecciona al índice de productos con un mensaje de éxito.
        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado');
    }
}
