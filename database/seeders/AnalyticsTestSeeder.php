<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Shop;
use App\Models\Meal;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AnalyticsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates test orders with READY status to test the analytics dashboard.
     * It creates orders for the last 30 days with various statuses and payment states.
     */
    public function run(): void
    {
        // Get all shops
        $shops = Shop::all();

        if ($shops->isEmpty()) {
            echo "Error: No shops found. Please run ShopSeeder first.\n";
            return;
        }

        // Get a user for creating orders
        $user = User::where('email', '!=', 'admin@example.com')->first();
        if (!$user) {
            echo "Error: Could not find a user to create orders for.\n";
            return;
        }

        // Create test orders for each shop
        foreach ($shops as $shop) {
            $meals = $shop->meals()->take(5)->get();
            if ($meals->isEmpty()) {
                echo "⚠️  Skipping {$shop->name} - no meals found.\n";
                continue;
            }

            echo "📊 Creating test data for: {$shop->name}\n";
            $this->createTestOrders($shop, $user, $meals);
        }

        echo "✅ Analytics test data created successfully!\n";
        echo "   - Created 15 READY status orders with PAID payment status (per shop)\n";
        echo "   - Created 8 CONFIRMED status orders with PAID payment status (per shop)\n";
        echo "   - Created 5 PENDING status orders with PENDING payment status (per shop)\n";
        echo "   - Created 2 COMPLETED status orders with PAID payment status (per shop)\n";
    }

    /**
     * Create test orders with various statuses
     */
    private function createTestOrders($shop, $user, $meals)
    {
        $ordersCreated = 0;

        // Create READY status orders (these should be counted in analytics)
        // Distributed across the last 30 days
        for ($i = 0; $i < 15; $i++) {
            $daysAgo = rand(0, 29);
            $createdAt = Carbon::now()->subDays($daysAgo)->setHour(rand(8, 20))->setMinute(rand(0, 59));

            $totalAmount = 0;
            $meal = $meals->random();
            $quantity = rand(1, 3);
            $itemPrice = $meal->price ?? 50000;
            $totalAmount += $itemPrice * $quantity;

            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'order_status' => 'READY',
                'payment_status' => 'PAID',
                'midtrans_order_id' => 'READY-' . $shop->id . '-' . uniqid() . '-' . $i,
                'payment_method' => ['BCA Virtual Account', 'BNI Virtual Account', 'GCash', 'Transfer Bank'][rand(0, 3)],
                'total_amount' => $totalAmount,
                'payment_time' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'price' => $itemPrice,
                'quantity' => $quantity,
            ]);

            $ordersCreated++;
        }

        // Create CONFIRMED status orders (should not be counted in analytics)
        for ($i = 0; $i < 8; $i++) {
            $daysAgo = rand(0, 29);
            $createdAt = Carbon::now()->subDays($daysAgo)->setHour(rand(8, 20))->setMinute(rand(0, 59));

            $totalAmount = 0;
            $meal = $meals->random();
            $quantity = rand(1, 3);
            $itemPrice = $meal->price ?? 50000;
            $totalAmount += $itemPrice * $quantity;

            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'order_status' => 'CONFIRMED',
                'payment_status' => 'PAID',
                'midtrans_order_id' => 'CONFIRMED-' . $shop->id . '-' . uniqid() . '-' . $i,
                'payment_method' => ['GCash', 'Transfer Bank', 'BCA Virtual Account'][rand(0, 2)],
                'total_amount' => $totalAmount,
                'payment_time' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'price' => $itemPrice,
                'quantity' => $quantity,
            ]);

            $ordersCreated++;
        }

        // Create PENDING status orders with PENDING payment (should not be counted)
        for ($i = 0; $i < 5; $i++) {
            $daysAgo = rand(0, 29);
            $createdAt = Carbon::now()->subDays($daysAgo)->setHour(rand(8, 20))->setMinute(rand(0, 59));

            $totalAmount = 0;
            $meal = $meals->random();
            $quantity = rand(1, 2);
            $itemPrice = $meal->price ?? 50000;
            $totalAmount += $itemPrice * $quantity;

            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'order_status' => 'PENDING',
                'payment_status' => 'PENDING',
                'midtrans_order_id' => 'PENDING-' . $shop->id . '-' . uniqid() . '-' . $i,
                'payment_method' => null,
                'total_amount' => $totalAmount,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'price' => $itemPrice,
                'quantity' => $quantity,
            ]);

            $ordersCreated++;
        }

        // Create COMPLETED status orders (for comparison with old analytics)
        for ($i = 0; $i < 2; $i++) {
            $daysAgo = rand(0, 29);
            $createdAt = Carbon::now()->subDays($daysAgo)->setHour(rand(8, 20))->setMinute(rand(0, 59));

            $totalAmount = 0;
            $meal = $meals->random();
            $quantity = rand(1, 3);
            $itemPrice = $meal->price ?? 50000;
            $totalAmount += $itemPrice * $quantity;

            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'order_status' => 'COMPLETED',
                'payment_status' => 'PAID',
                'midtrans_order_id' => 'COMPLETED-' . $shop->id . '-' . uniqid() . '-' . $i,
                'payment_method' => 'Transfer Bank',
                'total_amount' => $totalAmount,
                'payment_time' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'price' => $itemPrice,
                'quantity' => $quantity,
            ]);

            $ordersCreated++;
        }
    }
}
