<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAreaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $orders = $user->orders()
            ->with(['items.product', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $appointments = $user->appointments()
            ->with(['designer', 'order'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('users.client-area', compact('orders', 'appointments'));
    }
    
    public function orders()
    {
        $user = Auth::user();
        
        $orders = $user->orders()
            ->with(['items.product', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('users.orders', compact('orders'));
    }
    
    public function appointments()
    {
        $user = Auth::user();
        
        $appointments = $user->appointments()
            ->with(['designer', 'order.items.product'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);
            
        return view('users.appointments', compact('appointments'));
    }
    
    public function orderDetails($id)
    {
        $user = Auth::user();
        
        $order = $user->orders()
            ->with(['items.product', 'appointment.designer'])
            ->findOrFail($id);
            
        return view('users.order-details', compact('order'));
    }
    
    public function appointmentDetails($id)
    {
        $user = Auth::user();
        
        $appointment = $user->appointments()
            ->with(['designer', 'order.items.product'])
            ->findOrFail($id);
            
        return view('users.appointment-details', compact('appointment'));
    }
}
