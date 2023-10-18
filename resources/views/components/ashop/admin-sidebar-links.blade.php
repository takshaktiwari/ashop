<li>
    <a href="{{ route('admin.shop.attributes.index') }}" class="waves-effect">
        <i class="fas fa-code-branch"></i>
        <span>Attributes</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.shop.variations.index') }}" class="waves-effect">
        <i class="fas fa-braille"></i>
        <span>Variations</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.shop.categories.index') }}" class="waves-effect">
        <i class="fas fa-tags"></i>
        <span>Categories</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.shop.brands.index') }}" class="waves-effect">
        <i class="fas fa-store"></i>
        <span>Brands</span>
    </a>
</li>
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="fas fa-box-open"></i>
        <span>Products</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">
        <li><a href="{{ route('admin.shop.products.create') }}">New Product</a></li>
        <li><a href="{{ route('admin.shop.products.index') }}">All Products</a></li>
        <li><a href="{{ route('admin.shop.products.export.sheets') }}">Export Products</a></li>
        <li><a href="{{ route('admin.shop.products.import.sheets') }}">Import Products</a></li>
    </ul>
</li>
