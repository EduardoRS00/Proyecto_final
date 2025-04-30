<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class BookingAdminController extends Controller
{
    // Listado de reservas del restaurante logueado
    public function index(Request $request)
    {
        $restaurantId = auth()->id();
        $today = Carbon::today();

        $bookings = Booking::with('user')
            ->where('restaurant_id', $restaurantId)
            ->whereDate('booking_date', $today)
            ->orderBy('arrival')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        $totalPax = $bookings->sum('num_people');
        $totalMesas = $bookings->count();

        $user = \App\Models\User::find($restaurantId); 
        $restaurantName = $user?->restaurant_name ?? $user?->name ?? 'Restaurante';

        return view('Client.index', [
            'bookings'       => $bookings,
            'restaurantName' => $restaurantName,
            'selectedDate'   => $today->format('Y-m-d'),
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
} // <<<<---- aquí cerramos bien el método de llegada

    public function filter(Request $request, $id)
    {
        $today = Carbon::today();

        $selectedDate = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : $today;

        $timeFilter = $request->input('time');
        $nameFilter = $request->input('search');

        // Consulta inicial
        $query = Booking::with('user')
            ->where('restaurant_id', $id);

        // Si no hay ningún filtro
        if (!$request->filled('date') && !$request->filled('time') && !$request->filled('search')) {
            $query->whereDate('booking_date', $today);
        } else {
            // Aplicar filtros
            if ($request->filled('date')) {
                $query->whereDate('booking_date', $selectedDate);
            }

            if (!empty($timeFilter)) {
                $query->whereTime('booking_time', '=', $timeFilter);
            }

            if (!empty($nameFilter)) {
                $query->where(function ($q) use ($nameFilter) {
                    $q->whereRaw('LOWER(customer_name) LIKE ?', ['%' . strtolower($nameFilter) . '%'])
                        ->orWhereRaw('LOWER(customer_lastname) LIKE ?', ['%' . strtolower($nameFilter) . '%']);
                });
            }
        }

        // Ordenar
        $query->orderBy('arrival')
            ->orderBy('booking_date')
            ->orderBy('booking_time');


        // Obtener resultados
        $bookings = $query->get();

        // Totales
        $totalPax = $bookings->sum('num_people');
        $totalMesas = $bookings->count();

        // Restaurante
        $restaurant = auth()->user();

        return view('Client.index', [
            'bookings'       => $bookings,
            'restaurantName' => $restaurant->restaurant_name ?? $restaurant->name,
            'selectedDate'   => $selectedDate->toDateString(),
            'mealType'       => null,
            'totalPax'       => $totalPax,
            'totalMesas'     => $totalMesas,
            'statusFilter'   => null,
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
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_lastname' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'num_people' => 'required|integer|min:1',
            'table_type' => 'nullable|string|max:255',
            'menu' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
            'allergies' => 'nullable|string',
            'wheelchair' => 'nullable|integer',
            'baby_stroller' => 'nullable|integer',
            'high_chair' => 'nullable|integer',
            'arrival' => 'nullable'
        ]);

        $reserva = Booking::findOrFail($id);

        $datos = $request->only([
            'customer_name',
            'customer_lastname',
            'contact_phone',
            'contact_email',
            'booking_date',
            'booking_time',
            'num_people',
            'table_type',
            'menu',
            'comments',
            'allergies',
            'wheelchair',
            'baby_stroller',
            'high_chair',
            'arrival'
        ]);

        // 🔥 Convertimos arrival correctamente:
        if (isset($datos['arrival']) && $datos['arrival'] === '1') {
            $datos['arrival'] = 1;
        } else {
            $datos['arrival'] = null;
        }

        $reserva->update($datos);

        return redirect()->route('reservas.edit', $id)->with('success', 'Reserva actualizada correctamente.');
    }



    public function destroy($id)
    {
        // Buscar la reserva por ID o lanzar 404 si no existe
        $reserva = Booking::findOrFail($id);

        // Eliminar la reserva de la base de datos
        $reserva->delete();

        // Redirigir a la lista de reservas con mensaje de éxito
        return redirect()
            ->route('reservas.index', $reserva->restaurant_id ?? null)
            ->with('success', 'Reserva eliminada correctamente.');
    }
    public function marcarNoLlegado($id)
    {
        $reserva = Booking::findOrFail($id);
        $reserva->arrival = null;
        $reserva->save();

        return redirect()->route('reservas.index', ['id' => auth()->id()])->with('success', 'Reserva marcada como no llegada.');
    }
}
