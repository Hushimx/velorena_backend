<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Appointment;
use App\Models\Designer;
use App\Models\Marketer;
use App\Models\Lead;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get basic statistics
        $stats = $this->getDashboardStats();
        
        // Get recent data
        $recentOrders = $this->getRecentOrders();
        $recentAppointments = $this->getRecentAppointments();
        $recentUsers = $this->getRecentUsers();
        
        // Get sales data for chart
        $salesData = $this->getSalesData();
        
        return view('admin.dashboard.main', compact(
            'stats',
            'recentOrders',
            'recentAppointments', 
            'recentUsers',
            'salesData'
        ));
    }
    
    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_appointments' => Appointment::count(),
            'total_designers' => Designer::count(),
            'total_leads' => Lead::count(),
            
            // Today's stats
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_appointments' => Appointment::whereDate('created_at', $today)->count(),
            
            // This month's stats
            'monthly_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('status', '!=', 'cancelled')
                ->sum('total'),
            
            // Pending items
            'pending_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            
            // Revenue stats
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];
    }
    
    /**
     * Get recent orders
     */
    private function getRecentOrders()
    {
        return Order::with(['user', 'items.product'])
            ->latest()
            ->limit(5)
            ->get();
    }
    
    /**
     * Get recent appointments
     */
    private function getRecentAppointments()
    {
        return Appointment::with(['user', 'designer'])
            ->latest()
            ->limit(5)
            ->get();
    }
    
    /**
     * Get recent users
     */
    private function getRecentUsers()
    {
        return User::latest()
            ->limit(5)
            ->get();
    }
    
    /**
     * Get sales data for chart (last 7 days)
     */
    private function getSalesData()
    {
        $data = [];
        $startDate = Carbon::now()->subDays(6);
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayStart = $date->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            $orders = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $data[] = [
                'period' => $date->format('M d'),
                'revenue' => $orders->sum('total'),
                'orders' => $orders->count(),
            ];
        }
        
        return $data;
    }
    
    /**
     * Get sales data for specific period (for AJAX requests)
     */
    public function getSalesDataForPeriod(Request $request)
    {
        $days = $request->get('days', 7);
        $data = [];
        
        if ($days === 'today') {
            // Hourly data for today
            $today = Carbon::today();
            for ($i = 0; $i < 24; $i++) {
                $hour = $today->copy()->addHours($i);
                $hourStart = $hour->startOfHour();
                $hourEnd = $hour->copy()->endOfHour();
                
                $orders = Order::whereBetween('created_at', [$hourStart, $hourEnd])
                    ->where('status', '!=', 'cancelled')
                    ->get();
                
                $data[] = [
                    'period' => $hour->format('H:00'),
                    'revenue' => $orders->sum('total'),
                    'orders' => $orders->count(),
                ];
            }
        } else {
            // Daily data
            $startDate = Carbon::now()->subDays($days - 1);
            
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dayStart = $date->startOfDay();
                $dayEnd = $date->copy()->endOfDay();
                
                $orders = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                    ->where('status', '!=', 'cancelled')
                    ->get();
                
                $data[] = [
                    'period' => $date->format('M d'),
                    'revenue' => $orders->sum('total'),
                    'orders' => $orders->count(),
                ];
            }
        }
        
        $totalRevenue = collect($data)->sum('revenue');
        $totalOrders = collect($data)->sum('orders');
        
        return response()->json([
            'data' => $data,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
        ]);
    }
}
