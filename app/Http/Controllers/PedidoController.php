<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Elimina el __construct con middleware
    // El middleware se maneja en las rutas

    public function index()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $pedido->load('detalles.producto');
        return view('pedidos.show', compact('pedido'));
    }

    public function checkout(Request $request)
    {
        $carrito = auth()->user()->carrito()->with('producto')->get();

        if ($carrito->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'El carrito está vacío.');
        }

        $total = $carrito->sum(function ($item) {
            // Usar precio con oferta si existe
            $precioUnitario = $item->producto->en_oferta && $item->producto->precio_oferta && $item->producto->precio_oferta < $item->producto->precio
                ? $item->producto->precio_oferta
                : $item->producto->precio;
            return $item->cantidad * $precioUnitario;
        });

        $pedido = Pedido::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'estado' => 'pendiente',
            'direccion_entrega' => $request->direccion_entrega ?? 'Sin dirección especificada',
        ]);

        foreach ($carrito as $item) {
            $precioUnitario = $item->producto->en_oferta && $item->producto->precio_oferta && $item->producto->precio_oferta < $item->producto->precio
                ? $item->producto->precio_oferta
                : $item->producto->precio;

            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item->producto_id,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $precioUnitario,
                'subtotal' => $item->cantidad * $precioUnitario,
            ]);

            // Actualizar stock
            $producto = $item->producto;
            $producto->stock -= $item->cantidad;
            $producto->save();
        }

        auth()->user()->carrito()->delete();

        return redirect()->route('pedidos.show', $pedido)->with('success', '¡Pedido realizado con éxito!');
    }

    // ===== ADMIN =====

    public function adminIndex()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $pedidos = Pedido::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado'
        ]);

        $pedido->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }
}