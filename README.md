# Introduction  

This package is a part of **Adash** ecosystem. It is a shop implementation for Adash admin panel. It provides a simple and easy way to manage products, orders, customers and other related entities. It also comes with few inbuilt components that can be used to create a simple shop website.

# Installation

    composer require takshak/adash-shop

    php artisan migrate

    php artisan ashop:seed

Add argument 'fresh' (`php artisan ashop:seed fresh`) if you are seeding the first time. It will publish the seeders first and then see the database

# Publishable Files

    php artisan vendor:publish --tag=ashop-config
    php artisan vendor:publish --tag=ashop-views
    php artisan vendor:publish --tag=ashop-assets
    php artisan vendor:publish --tag=ashop-seeders

# In-built components

- **ashop-ashop:admin-sidebar-links**

    This component is designed to render a specific view that contains the admin sidebar links.

- **ashop-ashop:inner-nav**

    This component create a inner (in page) navigation in admin panel. This accepts 2 arguments:
    
    1. title (string) : display the title or the name of the navigation
    2. links (array) : this will construct the navigation links. It contains _permission_ (to check the permission), _text_ (displayed text of nav item) and _url_ (actual url of navigation). 

        For Example
        
        ```
            :links="[
                [
                    'permission' => 'products_details',
                    'text' => 'Product Detail',
                    'url' => 'route('admin.product.details')'
                ],
                [
                    'permission' => 'products_images',
                    'text' => 'Product Images',
                    'url' => 'route('admin.product.images')'
                ]
            ]"
        ```

- **ashop-ashop:product-nav**

    This component is made on the top of `ashop-ashop:inner-nav` where you need to pass the product's modal instance and this will create the product inner navigation for you. eg.
    ```
    <x-ashop-ashop:product-nav :product="$product" />
    ```

- **ashop-ashop:product-card**

    This component will create a card layout of the product for displaying, it accepts only one argument _product_.
    ```
    <x-ashop-ashop:product-card :product="$product" />
    ```

- **ashop-ashop:product-list-item**

    This component is similar to _ashop-ashop:product-card_, it display the product item as a list item

- **ashop-ashop:shop-sidebar**

    This will create a sidebar on products listing page at the front end to show the categories and filter options like color, size, pattern etc. in sidebar to let user filter the products. It can accept 2 arguments:

    1. category (optional): you can pass a category model to make that category as active category in the sidebar and all the filterable options / attributes will be shown in the sidebar which are associated to this category only if _filterAttributes_ argument is not passed saperately.

        If there is any category in query parameter (in current url), it will take that category as primary category.

    2. filterAttributes (array: optional): If you already got the filterable attributes you can pass here which will save a couple of queries

- **ashop-ashop:shop-header**

    This will have a header on product listing page which will contain search with categories, ordering and listing layout.

- **ashop-ashop:products-group**

    This will show a group of product in any section of the page, where you want to show latest, oldest, products of any category etc. You will have multiple options which might be changed according to out requirement that which products we need to show.

    1. title (string: optional): Show the title / heading for the group
    2. subtitle (string: optional): Show the subtitle in smaller font 
    3. heading (string|html: optional): If we want show some other content in the header
    4. buttons (array: optional): show some buttons / links in the heading like: show more or any other button. For Example.

        ```
        :buttons="[
            [
                'text' => 'See More',
                'url' => route('shop.products', ['order_by' => 'latest'])
            ]
        ]"
        ```
    5. type (string: optional): you will pass _featured_ in this argument which will get you only featured products.
    6. order (string: optional): we can set the order of the product listing, we can pass _latest, oldest_ or _rand_. _(default: 'latest')_
    7. ids (array: optional): if we want to list a group of some specific products, we will pass the array or products IDs, so that this will only show those products.
    8. categories (array: optional): if we want to show the products from some specific categories, we will pass the list categories in an array. This array can contain categories _id, name_ or _slug_.
    9. products (collection: options): if we got the collection of products we can pass here the products and it will list those product. This will not apply other arguments like, type, categories, ids etc.
    10. limit (number: optional): this will limit the products listing _(default: 10)_
    11. container (bool: options): if we want to render this section in a container or not. _(default: true)_
    12. columns (string: optional): to decide how many columns will be rendered in this section, it will accept bootstrap column classes. _(default: 'row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5')_

- **ashop-ashop:categories-group**

    This will show the list of categories in a section. we might use this to show latest categories, popular categories, featured categories etc. you can customize this  components with these parameters:

    1. title (string: optional): Show the title / heading for the group
    2. subtitle (string: optional): Show the subtitle in smaller font 
    3. buttons (array: optional): show some buttons / links in the heading like: show more or any other button. For Example.

        ```
        :buttons="[
            [
                'text' => 'See More',
                'url' => route('shop.products', ['order_by' => 'latest'])
            ]
        ]"
        ```
    4. parent (bool: optional): this will allow you to show only parent categories. _(default: true)_
    5. type (string: optional): you will pass *featured* or *is_top* in this.
    6. order (string: optional): we can set the order of the items listing, we can pass _latest, oldest_ or _rand_. _(default: 'latest')
    7. ids (array: optional): if we want to list a group of some specific items, we will pass the array or categories IDs.
    8. category_id (int: options): this will be useful when you want to show the child categories of any  specific category when _parent_ argument is set to _false_
    9. limit (number: optional): this will limit the items listing _(default: 10)_
    10. columns (string: optional): to decide how many columns will be rendered in this section, it will accept bootstrap column classes. _(default: 'row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5')_

- **ashop-ashop:brands-group**

    This component will be useful when we want to so the list to brands in a section. this will also contains the arguments similar to **ashop-ashop:categories-group** component.
    1. title (string: optional)
    2. subtitle (string: optional)
    3. buttons (string: optional)
    4. order (string: optional) (default: latest)
    5. ids (array: optional): will accept array of brands IDs
    6. categories (array: optional): we will pass the list categories in an array. This array can contain categories _id, name_ or _slug_
    7. limit (int: options) (default: 10)
    8. columns (string: optional) (default: 'row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6')

- **ashop-ashop:user-account**

    This will render the user dashboard layout with sidebar menu. We can pass the page title in title argument. This is not suppose to be a self closing component so you can pass the inner page content inside this. For example: 

        <x-ashop-ashop:user-account title="My Orders">
            {{-- orders page html content --}}
        </x-ashop-ashop:user-account>


- **ashop-ashop:user-bottom-nav**

    This will render main user pages navigation with icons.

- **ashop-ashop:products-viewed-history**

    This will show a section to show already viewed products list. this can contains some augments for tweaking.
    1. title (string: optional)
    2. subtitle (string: optional)
    7. limit (int: options) (default: 25)

- **ashop-ashop:mail-layout**
    This can be used when creating a emil to send to users.
