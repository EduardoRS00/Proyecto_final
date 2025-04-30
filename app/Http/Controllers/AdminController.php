<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Carbon;

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
           
            if (!$user->is_active_payment || (isset($user->payment_date) && \Carbon\Carbon::parse($user->payment_date)->addYear()->lt(now()))) {
                Auth::logout(); // Cierra la sesión
                return redirect()->route('login')->withErrors(['email' => 'Tu suscripción ha caducado. Contacta con administración.']);
            }

            // Sigue el flujo normal si tiene pago válido
            $selectedDate = $request->input('date');
            $timeFilter = $request->input('time');
            $nameFilter = $request->input('search');

            $query = Booking::where('restaurant_id', $user->id)
                ->whereDate('booking_date', \Carbon\Carbon::today());

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

        // Credenciales incorrectas
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
    

