<?php

return [
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
