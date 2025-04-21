<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Booking;

class AdminController extends Controller
{
   public function login()
    {
        return view('client.login');
    }

    public function index(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
        $user = Auth::user(); // Restaurante logueado

        // Obtener filtros desde la URL
        $selectedDate = $request->input('date');
        $timeFilter = $request->input('time');
        $nameFilter = $request->input('search');

        // Empezar con las reservas del restaurante autenticado
        $query = Booking::where('restaurant_id', $user->id);

        // Aplicar filtros si están presentes
        if (!empty($selectedDate)) {
            $query->where('booking_date', $selectedDate);
        }

        if (!empty($timeFilter)) {
            $query->where('booking_time', $timeFilter);
        }

        if (!empty($nameFilter)) {
            $query->where(function ($q) use ($nameFilter) {
                $q->where('customer_name', 'like', "%$nameFilter%")
                    ->orWhere('customer_lastname', 'like', "%$nameFilter%");
            });
        }

        // Obtener resultados finales
        $bookings = $query->get();

        // Datos adicionales para la vista
        $restaurantName = $user->name;
        $totalPax = $bookings->sum('num_people');
        $totalMesas = $bookings->count(); // Puedes cambiar esto si tienes lógica para mesas

        // Pasar todo a la vista
        return view('client.index', compact(
            'bookings',
            'restaurantName',
            'totalPax',
            'totalMesas',
            'selectedDate',
            'timeFilter',
            'nameFilter'
        ));
        }

        // Si las credenciales fallan
        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->withInput();
    }
    public function marcarLlegada(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->arrival = true;
        $booking->save();

        $user = Auth::user();

        // Recuperar filtros desde el request para no romper la vista
        $selectedDate = $request->input('date');
        $timeFilter = $request->input('time');
        $nameFilter = $request->input('search');

        $query = Booking::where('restaurant_id', $user->id);

        if (!empty($selectedDate)) {
            $query->where('booking_date', $selectedDate);
        }

        if (!empty($timeFilter)) {
            $query->where('booking_time', $timeFilter);
        }

        if (!empty($nameFilter)) {
            $query->where(function ($q) use ($nameFilter) {
                $q->where('customer_name', 'like', "%$nameFilter%")
                    ->orWhere('customer_lastname', 'like', "%$nameFilter%");
            });
        }

        $bookings = $query->get();
        $restaurantName = $user->name;
        $totalPax = $bookings->sum('num_people');
        $totalMesas = $bookings->count();

        return view('client.index', compact(
            'bookings',
            'restaurantName',
            'totalPax',
            'totalMesas',
            'selectedDate',
            'timeFilter',
            'nameFilter'
        ));
    }
}
    

