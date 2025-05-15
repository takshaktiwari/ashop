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
    'dataTables' => [
        'actionBtns' => [
            'exportExcel' =>  false,
            'exportCSV' =>  true,
            'exportPDF' =>  false,
            'print' =>  true,
            'reset' =>  false,
            'reload' =>  true,
        ]
    ],
    'shop' => [
        'name'  =>  config('app.name'),
        'website'  =>  config('app.url'),
        'email' =>  env('MAIL_PRIMARY', 'shop@example.com'),
        'mobile' => env('SHOP_MOBILE', "+91 9876 987 987"),
        'address' => env('SHOP_ADDRESS', 'Shop No. 12B, Shanti Apartments, Laxmi Nagar, Delhi - 110092'),
        'state' => env('SHOP_STATE', 'Delhi'),
        'country' => env('SHOP_COUNTRY', 'India'),
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
            'amount' => 15
        ],
        'status' => [
            'order_placed' => 'Order Placed',
            'order_processing' => 'Order Processing',
            'order_shipped' => 'Order Shipped',
            'out_of_delivery' => 'Out For Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Order Cancelled',
            'replace_requested' => 'Replacement Requested',
            'replace_rejected' => 'Replacement Rejected',
            'replace_initiated' => 'Replacement Initiated',
            'replaced' => 'Replaced',
            'return_requested' => 'Return Requested',
            'return_rejected' => 'Return Rejected',
            'return_initiated' => 'Return Initiated',
            'returned' => 'Returned',
            'refund_initiated' => 'Refund Initiated',
            'refunded' => 'Refunded',
        ],
        'status_messages' => [
            'order_placed' => 'Thank you for your order! Your order has been successfully placed and is now being processed. We\'ll notify you with updates shortly!',
            'order_processing' => 'Your order is currently being processed and will be prepared for shipment shortly. Thank you for your patience!',
            'order_shipped' => 'Great news! Your order has been shipped and is on its way to you. You can track its journey using the provided details.',
            'out_of_delivery' => 'Your order is out for delivery and will reach you soon. Please ensure someone is available to receive it. Thank you!',
            'delivered' => 'Your order has been successfully delivered. We hope you enjoy your purchase! Thank you for choosing us.',
            'cancelled' => 'Your order has been cancelled. If you have any questions or need further assistance, please feel free to contact us.',
            'replace_requested' => 'Your replacement request has been received and is being processed. We\'ll keep you updated on the next steps shortly.',
            'replace_rejected' => 'Your replacement request has been reviewed and, unfortunately, it has been rejected. For more details or assistance, please contact our support team.',
            'replace_initiated' => 'Your replacement order has been initiated and will be processed shortly. We\'ll provide further updates once it\'s ready for shipment.',
            'replaced' => 'Your order has been successfully replaced and is on its way to you. Thank you for your patience!',
            'return_requested' => 'Your return request has been successfully submitted and is being processed. We\'ll update you with the next steps shortly.',
            'return_rejected' => 'Your return request has been reviewed and, unfortunately, it has been rejected. If you have any questions or need further assistance, please contact our support team.',
            'return_initiated' => 'Your order return has been initiated and is being processed. We will keep you informed of the next steps shortly.',
            'returned' => 'Your order has been successfully returned. Thank you for your cooperation, and if you need any further assistance, please don\'t hesitate to reach out.',
            'refund_initiated' => 'Your refund has been initiated and will be processed shortly. You will receive the amount back to your original payment method soon.',
            'refunded' => 'Your refund has been successfully processed and the amount has been credited to your original payment method. Thank you for your patience!',
        ],
        'cancel' => [
            'status' => true,
            'within' => 7,
            'order_status' => ['order_placed', 'order_processing']
        ],
        'return' => [
            'status' => true,
            'within' => 7,
            'order_status' => ['delivered']
        ],
        'replace' => [
            'status' => true,
            'within' => 7,
            'order_status' => ['delivered']
        ]
    ],
    'queues' => [
        'emails' => env('MAIL_QUEUE', 'emails')
    ],
    'taxes' => [
        'GST' => 18,
        'CGST' => 9,
        'SGST' => 9,
        'IGST' => 18
    ],
    'invoices' => [
        'prefix' => 'INV',
    ]
];
