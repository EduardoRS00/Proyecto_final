<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
     public function index($id)
    {
        $restaurante = User::findOrFail($id);
        return view('booking.index', compact('restaurante')); 
    }
   public function create($id)
    {
        // Buscar restaurante y mostrar formulario
    }

    // Guardar reserva
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id'     => 'required|exists:users,id',
            'booking_date'      => 'required|date',
            'booking_time'      => 'required',
            'num_people'        => 'required|integer|min:1',
            'mesa'              => 'required|string|max:50',
            'tipo'              => 'required|string|max:100',
            'customer_name'     => 'required|string|max:100',
            'customer_lastname' => 'required|string|max:250',
            'contact_email'     => 'required|email|max:150',
            'contact_phone'     => 'required|string|max:30',
            'terms_accepted'    => 'accepted',
            'prefix' => 'required|string|max:5',
        ]);

        $booking = new Booking();
        $booking->restaurant_id     = $validated['restaurant_id'];
        $booking->booking_date      = $validated['booking_date'];
        $booking->booking_time      = $validated['booking_time'];
        $booking->num_people        = $validated['num_people'];
        $booking->table_type        = $request->input('mesa');
        $booking->menu              = $request->input('tipo');
        $booking->customer_name     = $validated['customer_name'];
        $booking->customer_lastname = $validated['customer_lastname'];
        $booking->contact_email     = $validated['contact_email'];
        $booking->contact_phone     = $validated['contact_phone'] = $request->input('prefix') . ' ' . $request->input('contact_phone');
        $booking->comments          = $request->input('comments');
        $booking->baby_stroller     = $request->input('baby_stroller');
        $booking->high_chair        = $request->input('high_chair');
        $booking->wheelchair        = $request->input('wheelchair');
        $booking->allergies         = $request->input('allergies');
        $booking->promo_opt_in      = $request->has('promo_opt_in');
        $booking->terms_accepted    = $request->has('terms_accepted');
        $booking->save();


        return view('booking.confirmacion', ['reserva' => $booking]);
    }


    // Mostrar página de confirmación
    public function confirm()
    {
        return view('bookings.confirm');
    }

    public function monthAvailability($restaurant, $year, $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = Carbon::create($year, $month, 1)->endOfMonth();

        $restaurante = User::findOrFail($restaurant);
        $maxCap = $restaurante->max_capacity;

        // Obtener reservas del mes actual de ese restaurante
        $reservas = Booking::where('restaurant_id', $restaurante->id)
            ->whereBetween('booking_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get();

        $ocupacion = [];

        foreach ($reservas as $reserva) {
            $inicio = Carbon::parse("{$reserva->booking_date} {$reserva->booking_time}");
            $fin = $inicio->copy()->addHours(2); // Duración de la reserva: 2 horas
            $ultimaHora = Carbon::parse("{$reserva->booking_date} 23:45");

            for ($slot = $inicio->copy(); $slot < $fin; $slot->addMinutes(15)) {
                if ($slot->greaterThan($ultimaHora)) {
                    break; // Evitar bloques más allá de las 23:45
                }

                $fechaKey = $slot->format('Y-m-d');
                $horaKey  = $slot->format('H:i');

                // Sumar ocupación por franja
                $ocupacion[$fechaKey][$horaKey] = ($ocupacion[$fechaKey][$horaKey] ?? 0) + $reserva->num_people;
            }
        }

        return response()->json($ocupacion);
    }
}














