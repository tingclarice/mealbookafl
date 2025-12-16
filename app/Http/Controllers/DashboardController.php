<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function dashboardMeal(Request $request){
        // $meals = Meal::orderBy('created_at', 'desc')->get();
        $meals = $request->user()->meals()->get();

        return view('dashboard.menuDashboard', compact('meals'));
    }

    function dashboardUsers(){
        $users = User::all();
        return view('dashboard.userDashboard', compact('users'));
    }

    // Analytics for Shop
    public function analytics(Request $request){
        $user = $request->user();
        $shop = $user->shops()->wherePivot('role', 'OWNER')->first();

        if (!$shop) {
            return redirect()->route('home')->with('error', 'You do not own any shop.');
        }

        // Get date range (default: last 30 days)
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        // Parse dates if they're strings
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // ===== OVERVIEW STATISTICS =====
        $stats = [
            // Total orders in period
            'total_orders' => Order::where('shop_id', $shop->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            // Pending orders (waiting for payment)
            'pending_orders' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'PENDING')
                ->count(),

            // Paid orders (need to be prepared)
            'paid_orders' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'PAID')
                ->count(),

            // Orders being prepared
            'preparing_orders' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'PREPARING')
                ->count(),

            // Orders ready for pickup
            'ready_orders' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'READY')
                ->count(),

            // Completed orders
            'completed_orders' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'COMPLETED')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            // Total revenue (only from completed orders)
            'total_revenue' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'COMPLETED')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),

            // Average order value
            'avg_order_value' => Order::where('shop_id', $shop->id)
                ->where('order_status', 'COMPLETED')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('total_amount'),
        ];

        // ===== DAILY REVENUE CHART (last 7 days) =====
        $dailyRevenue = Order::where('shop_id', $shop->id)
            ->where('order_status', 'COMPLETED')
            ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ===== TOP SELLING MEALS =====
        $topMeals = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('meals', 'order_items.meal_id', '=', 'meals.id')
            ->where('orders.shop_id', $shop->id)
            ->where('orders.order_status', 'COMPLETED')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'meals.name',
                'meals.image_url',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('meals.id', 'meals.name', 'meals.image_url')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ===== ORDER STATUS DISTRIBUTION =====
        $orderStatusDistribution = Order::where('shop_id', $shop->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status');

        // ===== RECENT ORDERS =====
        $recentOrders = Order::where('shop_id', $shop->id)
            ->with(['user', 'items.meal'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ===== PEAK HOURS =====
        $peakHours = Order::where('shop_id', $shop->id)
            ->where('order_status', 'COMPLETED')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('hour')
            ->orderByDesc('orders')
            ->limit(5)
            ->get();

        return view('dashboard.analytics', compact(
            'shop',
            'stats',
            'dailyRevenue',
            'topMeals',
            'orderStatusDistribution',
            'recentOrders',
            'peakHours',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Orders Dashboard - View and manage orders
     */
    public function dashboardOrders(Request $request)
    {
        $user = $request->user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return redirect()->route('home')->with('error', 'You are not assigned to any shop.');
        }

        // Get filter from request
        $status = $request->input('status', 'all');

        // Build query
        $query = Order::where('shop_id', $shop->id)
            ->with(['user', 'items.meal', 'payment']);

        // Apply status filter
        if ($status !== 'all') {
            $query->where('order_status', strtoupper($status));
        }

        // Get orders
        $orders = $query->orderByDesc('created_at')->paginate(20);

        // Get counts for filter badges
        $counts = [
            'all' => Order::where('shop_id', $shop->id)->count(),
            'pending' => Order::where('shop_id', $shop->id)->where('order_status', 'PENDING')->count(),
            'paid' => Order::where('shop_id', $shop->id)->where('order_status', 'PAID')->count(),
            'preparing' => Order::where('shop_id', $shop->id)->where('order_status', 'PREPARING')->count(),
            'ready' => Order::where('shop_id', $shop->id)->where('order_status', 'READY')->count(),
            'completed' => Order::where('shop_id', $shop->id)->where('order_status', 'COMPLETED')->count(),
        ];

        return view('dashboard.orders', compact('orders', 'status', 'counts', 'shop'));
    }

    /**
     * Scan QR code to complete order
     */
    public function scanOrderQR(Request $request, Order $order)
    {
        $user = $request->user();
        $shop = $user->shops()->first();

        // Verify this order belongs to the shop
        if ($order->shop_id !== $shop->id) {
            return response()->json([
                'success' => false,
                'message' => 'This order does not belong to your shop.'
            ], 403);
        }

        // Verify order is ready for pickup
        if ($order->order_status !== 'READY') {
            return response()->json([
                'success' => false,
                'message' => 'This order is not ready for pickup yet.',
                'current_status' => $order->order_status
            ], 400);
        }

        // Validate QR code
        $qrCode = $request->input('qr_code');
        if ($qrCode !== $order->qr_code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.'
            ], 400);
        }

        // Mark order as completed
        $order->update(['order_status' => 'COMPLETED']);

        return response()->json([
            'success' => true,
            'message' => 'Order completed successfully!',
            'order' => $order->load('user', 'items.meal')
        ]);
    }
}