<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class BookingAdminController extends Controller
{
    // Listado de reservas del restaurante logueado
    public function index()
    {
        $restaurantId = auth()->id();

        // Obtener todas las reservas del restaurante logueado con relación al usuario (cliente)
        $bookings = Booking::with('user')
            ->where('restaurant_id', $restaurantId)
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        // Calcular totales
        $totalPax = $bookings->sum('num_people');
        $totalMesas = $bookings->count();

        // Nombre del restaurante
        $admin = auth('admin')->user();
        $restaurantName = $admin?->restaurant_name ?? $admin?->name ?? 'Restaurante';


        return view('Client.index', [
            'bookings'       => $bookings,
            'restaurantName' => $restaurantName,
            'selectedDate'   => null,
            'mealType'       => null,
            'totalPax'       => $totalPax,
            'totalMesas'     => $totalMesas,
            'statusFilter'   => null,
            'timeFilter'     => null,
            'nameFilter'     => null,
            'now'            => Carbon::now(),
        ]);
    }

    public function marcarLlegada(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->arrival = true;
        $booking->save();

        // Solo devuelve respuesta si es AJAX
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        // Si por alguna razón se accede desde formulario normal, redirige
        return redirect()->back()->with('success', 'Reserva marcada como llegada.');
    }


    // Filtrar reservas (fecha, estado, etc.)
    public function filter(Request $request, $id)
    {
        // Obtener filtros del formulario
        $selectedDate = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $timeFilter = $request->input('time');
        $nameFilter = $request->input('search');

        // Usuario restaurante
        $restaurant = auth()->user();

        // Consulta filtrada
        $query = Booking::with('user')
            ->where('restaurant_id', $id)
            ->whereDate('booking_date', $selectedDate);

        // Filtro por hora exacta (HH:MM)
        if (!empty($timeFilter)) {
            $query->whereTime('booking_time', '=', $timeFilter);
        }

        // Filtro por nombre de cliente (relación con tabla users)
        if (!empty($nameFilter)) {
            $query->whereHas('user', function ($q) use ($nameFilter) {
                $q->where('name', 'like', '%' . $nameFilter . '%');
            });
        }

        // Ordenar por hora ascendente y luego por id
        $query->orderBy('booking_time')->orderBy('id');

        // Obtener resultados
        $bookings = $query->get();

        // Totales
        $totalPax = $bookings->sum('num_people');
        $totalMesas = $bookings->count();

        return view('Client.index', [
            'bookings'       => $bookings,
            'restaurantName' => $restaurant->restaurant_name ?? $restaurant->name,
            'selectedDate'   => $selectedDate->toDateString(),
            'mealType'       => null, // opcional, si usas este campo
            'totalPax'       => $totalPax,
            'totalMesas'     => $totalMesas,
            'statusFilter'   => null, // opcional, si luego agregas filtro de estado
            'timeFilter'     => $timeFilter,
            'nameFilter'     => $nameFilter,
            'now'            => Carbon::now(),
        ]);
    }


    // Actualizar estado de reserva (confirmada / no-show)
    public function updateStatus(Request $request, $id)
    {
        // Cambiar estado en DB
    }

    // Editar una reserva
    public function edit($id)
    {
        $reserva = Booking::with('user')->findOrFail($id);

        return view('Client.show', [
            'reserva' => $reserva
        ]);
    }

    public function update(Request $request, $id)
    {
        // Guardar cambios en la reserva
    }
}
