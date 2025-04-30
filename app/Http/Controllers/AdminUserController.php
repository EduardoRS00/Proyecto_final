<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $usuarios = User::withCount('bookings')->get();
        $hoy = \Carbon\Carbon::now();
        $usuariosActualizados = [];

        foreach ($usuarios as $usuario) {
            if ($usuario->payment_date && $usuario->is_active_payment) {
                $fechaExpiracion = \Carbon\Carbon::parse($usuario->payment_date)->addYear();

                if ($hoy->greaterThan($fechaExpiracion)) {
                    $usuario->is_active_payment = false;
                    $usuario->save();
                    $usuariosActualizados[] = $usuario->name; // Guardamos nombres actualizados
                }
            }
        }

        return view('admin.users.index', [
            'usuarios' => $usuarios,
            'usuariosActualizados' => $usuariosActualizados
        ]);
    }

    public function togglePago(User $user)
    {
        $user->is_active_payment = !$user->is_active_payment;
        $user->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    public function verReservas(User $user)
    {
        $reservas = $user->bookings()->orderBy('booking_date')->orderBy('booking_time')->get();
        return view('admin.users.reservas', compact('user', 'reservas'));
    }

public function create()
{
    return view('admin.users.create');
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'city'           => 'required|string|max:100',
            'street'         => 'required|string|max:100',
            'street_number'  => 'required|string|max:50',
            'phone'          => 'required|string|max:20',
            'max_capacity'   => 'required|integer|min:1',
            'payment_date'   => 'nullable|date',
            'slogan'         => 'nullable|string|max:255', 
            'password'       => 'required|string|min:6',
        ]);


        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active_payment'] = true; 

        User::create($validated);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function alta(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'city'           => 'required|string|max:100',
            'street'         => 'required|string|max:100',
            'street_number'  => 'required|string|max:50',
            'phone'          => 'required|string|max:20',
            'max_capacity'   => 'required|integer|min:1',
            'payment_date'   => 'nullable|date',
            'password'       => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active_payment'] = true; // o false según lo que quieras por defecto

        User::create($validated);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }
}
