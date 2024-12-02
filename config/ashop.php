<?php

return [
    /**
     * Define the layout the pages,
     * Enable disable the sections and apply some limitations
     * You can change the sequence of the sections
     * Status: If you want to show the section
     * Items: count of items in that section
     */
    'sections' => [
        'home' => [
            'new_arrivals' => [
                'status' => true,
                'items'  =>  10
            ],
            'featured' => [
                'status' => true,
                'items'  =>  10
            ],
            'popular' => [
                'status' => true,
                'items'  =>  10
            ],
            'categories' => [
                'status' => true,
                'items'  =>  10
            ],
            /**
             * (featured_categories) List some products from featured categories
             * Items: count of featured categories
             * Products: count of products which each category will carry
             */
            'featured_categories' => [
                'status' => true,
                'items'  =>  4,
                'products'  =>  5,
            ],
            'brands' => [
                'status' => true,
                'items'  =>  12
            ],
            /**
             * (top_categories) List some products from top categories
             * Items: count of featured categories
             * Products: count of products which each category will carry
             */
            'top_categories' => [
                'status' => true,
                'items'  =>  4,
                'products'  =>  5,
            ]
        ],
        /**
         * Customize sections of products / shop page where
         * you are displaying the products
         */
        'products' => [
            # number of products per page
            'items' =>  24,

            # display the sidebar which has categories and filter
            'sidebar' =>  true,

            # display the icons to switch the product listing from grid and list style
            'display_style' =>  true,

            # display the sorting options
            'sorting' =>  true,

            # display the search option
            'search' =>  true,

            # display he subcategories of category which products you are currently viewing
            'show_subcategories' =>  true,

            # show the category banner which products you are currently viewing
            'show_banner' =>  true,
        ]
    ],
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

    'payment' => [
        'modes'     =>  [
            'cod'   =>  'Cash On Delivery',
            'online'    =>  'Pay Online',
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
