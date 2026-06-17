<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        // Los productos en oferta aparecen primero
        $query->orderBy('en_oferta', 'desc');

        if ($request->filled('buscar')) {
            $searchTerm = $request->buscar;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'ILIKE', '%' . $searchTerm . '%')
                  ->orWhere('descripcion', 'ILIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $productos = $query->paginate(12);

        return view('tienda', compact('productos'));
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function ofertas()
    {
        $productos = Producto::where('en_oferta', true)
            ->whereNotNull('precio_oferta')
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('productos.ofertas', compact('productos'));
    }

    // ===== API DE BÚSQUEDA =====

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $productos = Producto::where('nombre', 'ILIKE', '%' . $query . '%')
            ->orWhere('descripcion', 'ILIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'nombre', 'precio', 'precio_oferta', 'en_oferta', 'categoria', 'imagen']);

        return response()->json($productos);
    }

    // ===== ADMIN =====

    public function adminIndex()
    {
        $productos = Producto::orderBy('id', 'desc')->paginate(15);
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = [
            'Semiconductores',
            'Componentes pasivos',
            'Sensores',
            'Arduino y desarrollo',
            'Alimentación y energía',
            'Conectores y cables',
            'Motores y movimiento',
            'Pantallas e indicadores',
            'Herramientas y soldadura'
        ];
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'en_oferta' => 'nullable|boolean',
            'precio_oferta' => 'nullable|numeric|min:0|lt:precio',
        ]);

        $data = $request->all();
        $data['en_oferta'] = $request->has('en_oferta');

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen'] = $path;
        }

        Producto::create($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = [
            'Semiconductores',
            'Componentes pasivos',
            'Sensores',
            'Arduino y desarrollo',
            'Alimentación y energía',
            'Conectores y cables',
            'Motores y movimiento',
            'Pantallas e indicadores',
            'Herramientas y soldadura'
        ];
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria' => 'nullable|string|max:50',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'en_oferta' => 'nullable|boolean',
            'precio_oferta' => 'nullable|numeric|min:0|lt:precio',
        ]);

        $data = $request->all();
        $data['en_oferta'] = $request->has('en_oferta');

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $path = $request->file('imagen')->store('productos', 'public');
            $data['imagen'] = $path;
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}