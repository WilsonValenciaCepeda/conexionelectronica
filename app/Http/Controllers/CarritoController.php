<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return view('carrito.index', [
                'carrito' => collect([]),
                'total' => 0,
            ]);
        }

        $carrito = auth()->user()->carrito()->with('producto')->get();
        $total = $carrito->sum(function ($item) {
            return $item->cantidad * $item->producto->precio;
        });

        return view('carrito.index', compact('carrito', 'total'));
    }

    public function agregar(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar productos al carrito.');
        }

        $producto = Producto::findOrFail($request->producto_id);

        if ($producto->stock <= 0) {
            return redirect()->back()->with('error', 'Producto sin stock.');
        }

        $item = Carrito::where('user_id', auth()->id())
            ->where('producto_id', $producto->id)
            ->first();

        if ($item) {
            if ($item->cantidad + 1 > $producto->stock) {
                return redirect()->back()->with('error', 'No hay suficiente stock.');
            }
            $item->increment('cantidad');
        } else {
            Carrito::create([
                'user_id' => auth()->id(),
                'producto_id' => $producto->id,
                'cantidad' => 1,
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, Carrito $carrito)
    {
        if ($carrito->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = $carrito->producto;

        if ($request->cantidad > $producto->stock) {
            return redirect()->back()->with('error', 'No hay suficiente stock.');
        }

        $carrito->update(['cantidad' => $request->cantidad]);

        return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
    }

    public function eliminar(Carrito $carrito)
    {
        if ($carrito->user_id !== auth()->id()) {
            abort(403);
        }

        $carrito->delete();

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        auth()->user()->carrito()->delete();

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }

    public function comprarIndividual(Carrito $carrito)
    {
        if ($carrito->user_id !== auth()->id()) {
            abort(403);
        }

        $producto = $carrito->producto;

        if ($carrito->cantidad > $producto->stock) {
            return redirect()->back()->with('error', 'No hay suficiente stock para este producto.');
        }

        $pedido = Pedido::create([
            'user_id' => auth()->id(),
            'total' => $carrito->cantidad * $producto->precio,
            'estado' => 'pendiente',
            'direccion_entrega' => 'Sin dirección especificada',
        ]);

        DetallePedido::create([
            'pedido_id' => $pedido->id,
            'producto_id' => $producto->id,
            'cantidad' => $carrito->cantidad,
            'precio_unitario' => $producto->precio,
            'subtotal' => $carrito->cantidad * $producto->precio,
        ]);

        $producto->stock -= $carrito->cantidad;
        $producto->save();

        $carrito->delete();

        return redirect()->route('pedidos.show', $pedido)->with('success', '¡Producto comprado exitosamente!');
    }
}