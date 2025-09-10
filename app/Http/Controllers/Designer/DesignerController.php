<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotDesigner;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DesignerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(RedirectIfNotDesigner::class);
    }

    /**
     * Show the designer dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $designer = Auth::guard('designer')->user();
        
        // Get appointment statistics
        $stats = $this->getAppointmentStats($designer);
        
        // Get recent appointments
        $recentAppointments = $this->getRecentAppointments($designer);
        
        return view('designer.dashboard.main', compact('stats', 'recentAppointments'));
    }
    
    /**
     * Get appointment statistics for the designer
     */
    private function getAppointmentStats($designer)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Total appointments
        $totalAppointments = Appointment::where('designer_id', $designer->id)->count();
        
        // Pending appointments
        $pendingAppointments = Appointment::where('designer_id', $designer->id)
            ->where('status', 'pending')
            ->count();
            
        // Completed appointments
        $completedAppointments = Appointment::where('designer_id', $designer->id)
            ->where('status', 'completed')
            ->count();
            
        // Today's appointments
        $todayAppointments = Appointment::where('designer_id', $designer->id)
            ->whereDate('appointment_date', $today)
            ->count();
            
        // This month's appointments
        $monthlyAppointments = Appointment::where('designer_id', $designer->id)
            ->where('appointment_date', '>=', $thisMonth)
            ->count();
            
        // Completion rate
        $completionRate = $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100, 1) : 0;
        
        // Available appointments count
        $availableAppointmentsCount = Appointment::whereNull('designer_id')
            ->where('status', 'pending')
            ->count();
        
        // Historical data for chart
        $todayCompleted = Appointment::where('designer_id', $designer->id)
            ->whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->count();
            
        $yesterdayAppointments = Appointment::where('designer_id', $designer->id)
            ->whereDate('appointment_date', $yesterday)
            ->count();
            
        $yesterdayCompleted = Appointment::where('designer_id', $designer->id)
            ->whereDate('appointment_date', $yesterday)
            ->where('status', 'completed')
            ->count();
        
        return [
            'total_appointments' => $totalAppointments,
            'pending_appointments' => $pendingAppointments,
            'completed_appointments' => $completedAppointments,
            'today_appointments' => $todayAppointments,
            'monthly_appointments' => $monthlyAppointments,
            'completion_rate' => $completionRate,
            'available_appointments' => $availableAppointmentsCount,
            'today_completed' => $todayCompleted,
            'yesterday_appointments' => $yesterdayAppointments,
            'yesterday_completed' => $yesterdayCompleted,
            'day_before_appointments' => 0,
            'day_before_completed' => 0,
            'three_days_ago_appointments' => 0,
            'three_days_ago_completed' => 0,
            'four_days_ago_appointments' => 0,
            'four_days_ago_completed' => 0,
            'five_days_ago_appointments' => 0,
            'five_days_ago_completed' => 0,
            'week_ago_appointments' => 0,
            'week_ago_completed' => 0,
        ];
    }
    
    /**
     * Get recent appointments for the designer
     */
    private function getRecentAppointments($designer)
    {
        return Appointment::where('designer_id', $designer->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Get available appointments that can be claimed by any designer
     */
    private function getAvailableAppointments()
    {
        return Appointment::whereNull('designer_id')
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
}
