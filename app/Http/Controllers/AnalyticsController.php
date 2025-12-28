<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shops()->first();

        if (!$shop) {
            return redirect()->route('dashboard')
                ->with('error', 'No shop found');
        }

        // Date filter (default: last 30 days)
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $analytics = $this->getAnalytics($shop->id, $startDate, $endDate);

        return view('analytics.index', compact('analytics', 'shop', 'startDate', 'endDate'));
    }

    private function getAnalytics($shopId, $startDate, $endDate)
    {
        return [
            'revenue' => $this->getRevenueMetrics($shopId, $startDate, $endDate),
            'orders' => $this->getOrderMetrics($shopId, $startDate, $endDate),
            'products' => $this->getProductMetrics($shopId, $startDate, $endDate),
            'customers' => $this->getCustomerMetrics($shopId, $startDate, $endDate),
            'payments' => $this->getPaymentMetrics($shopId, $startDate, $endDate),
            'peak_hours' => $this->getPeakHoursData($shopId, $startDate, $endDate),
            'charts' => $this->getChartData($shopId, $startDate, $endDate),
        ];
    }

    private function getRevenueMetrics($shopId, $startDate, $endDate)
    {
        $totalRevenue = Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $todayRevenue = Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        // Calculate previous period for growth rate
        $daysDiff = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $previousStart = Carbon::parse($startDate)->subDays($daysDiff);

        $previousPeriodRevenue = Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereBetween('created_at', [$previousStart, $startDate])
            ->sum('total_amount');

        $growthRate = $previousPeriodRevenue > 0
            ? (($totalRevenue - $previousPeriodRevenue) / $previousPeriodRevenue) * 100
            : 0;

        return [
            'total' => $totalRevenue,
            'today' => $todayRevenue,
            'growth_rate' => round($growthRate, 1),
            'average_order_value' => $this->getAverageOrderValue($shopId, $startDate, $endDate),
        ];
    }

    private function getOrderMetrics($shopId, $startDate, $endDate)
    {
        $totalOrders = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $todayOrders = Order::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->count();

        $statusDistribution = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get()
            ->pluck('count', 'order_status');

        return [
            'total' => $totalOrders,
            'today' => $todayOrders,
            'status_distribution' => $statusDistribution,
            'completion_rate' => $this->getCompletionRate($shopId, $startDate, $endDate),
        ];
    }

    private function getProductMetrics($shopId, $startDate, $endDate)
    {
        $topSelling = OrderItem::whereHas('order', function ($q) use ($shopId, $startDate, $endDate) {
            $q->where('shop_id', $shopId)
                ->where('payment_status', 'PAID')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->select('meal_id', 'meal_name')
            ->selectRaw('SUM(quantity) as total_sold')
            ->selectRaw('SUM(price * quantity) as revenue')
            ->groupBy('meal_id', 'meal_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return [
            'top_selling' => $topSelling,
        ];
    }

    private function getChartData($shopId, $startDate, $endDate)
    {
        // Revenue trend (daily)
        $revenueTrend = Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'revenue_trend' => $revenueTrend,
        ];
    }

    private function getAverageOrderValue($shopId, $startDate, $endDate)
    {
        return Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('total_amount') ?? 0;
    }

    private function getCompletionRate($shopId, $startDate, $endDate)
    {
        $total = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $completed = Order::where('shop_id', $shopId)
            ->where('order_status', 'COMPLETED')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    // Phase 2: Customer Analytics
    private function getCustomerMetrics($shopId, $startDate, $endDate)
    {
        // Total unique customers
        $totalCustomers = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        // New customers (first order in this shop during period)
        $newCustomers = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotExists(function ($query) use ($shopId, $startDate) {
                $query->select(DB::raw(1))
                    ->from('orders as o2')
                    ->whereColumn('o2.user_id', 'orders.user_id')
                    ->where('o2.shop_id', $shopId)
                    ->where('o2.created_at', '<', $startDate);
            })
            ->distinct('user_id')
            ->count('user_id');

        // Returning customers (more than 1 order)
        $returningCustomers = DB::table('orders')
            ->where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->having('order_count', '>', 1)
            ->count();

        // Customer Lifetime Value
        $clv = $totalCustomers > 0
            ? Order::where('shop_id', $shopId)
                ->where('payment_status', 'PAID')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount') / $totalCustomers
            : 0;

        return [
            'total' => $totalCustomers,
            'new' => $newCustomers,
            'returning' => $returningCustomers,
            'clv' => $clv,
            'retention_rate' => $totalCustomers > 0
                ? round(($returningCustomers / $totalCustomers) * 100, 1)
                : 0,
        ];
    }

    // Phase 2: Payment Analytics
    private function getPaymentMetrics($shopId, $startDate, $endDate)
    {
        // Payment method breakdown
        $paymentMethods = Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(total_amount) as revenue')
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        // Payment success rate
        $totalOrders = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $paidOrders = Order::where('shop_id', $shopId)
            ->where('payment_status', 'PAID')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'methods' => $paymentMethods,
            'success_rate' => $totalOrders > 0
                ? round(($paidOrders / $totalOrders) * 100, 1)
                : 0,
        ];
    }

    // Phase 2: Peak Hours Analysis
    private function getPeakHoursData($shopId, $startDate, $endDate)
    {
        // Orders by hour of day
        $hourlyOrders = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour');

        // Orders by day of week (1=Sunday, 7=Saturday)
        $dailyOrders = Order::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DAYOFWEEK(created_at) as day')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->pluck('count', 'day');

        // Map day numbers to names
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $dailyOrdersNamed = [];
        foreach ($dailyOrders as $dayNum => $count) {
            $dailyOrdersNamed[$dayNames[$dayNum - 1]] = $count;
        }

        return [
            'hourly' => $hourlyOrders,
            'daily' => collect($dailyOrdersNamed),
        ];
    }

    // Phase 3: Export to CSV
    public function export(Request $request)
    {
        $shop = $request->user()->shops()->first();

        if (!$shop) {
            return redirect()->route('dashboard')->with('error', 'No shop found');
        }

        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $analytics = $this->getAnalytics($shop->id, $startDate, $endDate);

        // Generate CSV
        $csv = "Analytics Report - {$shop->name}\n";
        $csv .= "Period: {$startDate} to {$endDate}\n\n";

        $csv .= "REVENUE METRICS\n";
        $csv .= "Total Revenue,Rp " . number_format($analytics['revenue']['total'], 0, ',', '.') . "\n";
        $csv .= "Today's Revenue,Rp " . number_format($analytics['revenue']['today'], 0, ',', '.') . "\n";
        $csv .= "Growth Rate," . $analytics['revenue']['growth_rate'] . "%\n";
        $csv .= "Average Order Value,Rp " . number_format($analytics['revenue']['average_order_value'], 0, ',', '.') . "\n\n";

        $csv .= "ORDER METRICS\n";
        $csv .= "Total Orders," . $analytics['orders']['total'] . "\n";
        $csv .= "Today's Orders," . $analytics['orders']['today'] . "\n";
        $csv .= "Completion Rate," . $analytics['orders']['completion_rate'] . "%\n\n";

        $csv .= "TOP PRODUCTS\n";
        $csv .= "Rank,Product Name,Quantity Sold,Revenue\n";
        foreach ($analytics['products']['top_selling'] as $index => $product) {
            $csv .= ($index + 1) . "," . $product->meal_name . "," . $product->total_sold . ",Rp " . number_format($product->revenue, 0, ',', '.') . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="analytics-' . $shop->name . '-' . now()->format('Y-m-d') . '.csv"');
    }
}
