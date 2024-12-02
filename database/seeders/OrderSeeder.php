<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\OrderProduct;
use Takshak\Ashop\Models\Shop\Product;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderStatus = array_keys(config('ashop.order.status'));
        $paymentModes = array_keys(config('ashop.payment.modes'));

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, 500);
        $progressBar->start();

        for ($i = 0; $i < 500; $i++) {
            shuffle($orderStatus);
            shuffle($paymentModes);

            $order = new Order();
            $order->user_id = User::inRandomOrder()->first()?->id;
            $order->user_ip = fake()->ipv4();
            $order->subtotal = 0;
            $order->name = fake()->name();
            $order->mobile = fake()->phoneNumber();
            $order->address_line_1 = fake()->address();
            $order->address_line_2 = fake()->streetAddress();
            $order->landmark = fake()->streetName();
            $order->city = fake()->city();
            $order->pincode = rand(100000, 999999);
            $order->state = fake()->state();
            $order->country = 'India';
            $order->shipping_charge = rand(5, 25) * 10;
            $order->discount = 0;
            $order->payment_mode = end($paymentModes);
            $order->order_status = end($orderStatus);
            $order->save();

            for ($j = 0; $j < rand(1, 5); $j++) {
                $product = Product::inRandomOrder()->first();
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image_sm,
                    'net_price' => $product->net_price,
                    'price' => $product->price,
                    'quantity' => rand(1, 10),
                    'others' => []
                ]);

                $order->subtotal += $orderProduct->subtotal;
            }

            $order->save();

            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln('');
    }
}
