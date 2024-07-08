<?php

return [
    'shop' => [
        'name'  =>  config('app.name'),
        'home'  =>  config('app.url'),
        'email' =>  env('MAIL_PRIMARY', 'shop@example.com'),
        'mobile' => env('SHOP_MOBILE', "+91 9876 987 987"),
        'address' => env('SHOP_ADDRESS', 'Shop No. 12B, Shanti Apartments, Laxmi Nagar, Delhi - 110092')
    ],
    'currency' => [
        'code'  =>  'INR',
        'sign'  =>  '₹'
    ],
    'brands' => [
        'images' => [
            'width' => 800,
            'height' => 900,
        ]
    ],
    'categories' => [
        'images' => [
            'width' => 800,
            'height' => 900,
        ]
    ],
    'products' => [
        'images' => [
            'width' => 800,
            'height' => 900,
        ]
    ],

    'order' => [
        'initial_status' => 'order_placed',
        'final_status' => 'delivered',
        'default_shipping' => [
            'type' => 'percent', // may be percent of total order or flat amount
            'amount' => 99
        ],
        'status' => [
            'order_placed' => 'Order Placed',
            'order_shipped' => 'Order Shipped',
            'out_of_delivery' => 'Out For Delivery',
            'delivered' => 'Delivered',
            'return' => 'Return',
            'refund' => 'Refund',
            'cancelled' => 'Order Cancelled',
        ]
    ]
];
