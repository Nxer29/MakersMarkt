<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $start = Carbon::now()->subDays(6)->startOfDay();

        $totalProducts = Product::count();
        $ordersLast7Days = Order::where('created_at', '>=', $start)->count();

        $productsByType = Product::query()
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $days = collect(range(0, 6))->map(fn ($i) => $start->copy()->addDays($i)->format('Y-m-d'));

        $ordersPerDayRaw = Order::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $start)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->pluck('total', 'day');

        $ordersPerDay = $days->map(fn ($day) => (int) ($ordersPerDayRaw[$day] ?? 0));

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'ordersLast7Days' => $ordersLast7Days,
            'productsByType' => $productsByType,
            'orderDays' => $days,
            'ordersPerDay' => $ordersPerDay,
        ]);
    }
}
