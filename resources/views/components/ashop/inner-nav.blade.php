<x-slot name="style">
    <style>
        .inner-nav {  }
        .inner-nav .nav-item .nav-link { padding: 10px 12px; color: white; }
        .inner-nav .nav-item.active .nav-link { color: black!important; font-weight: 600; }
        .inner-nav .nav-item:hover, .inner-nav .nav-item.active { background-color: white; }
        .inner-nav .nav-item:hover .nav-link { color: black!important; }
    </style>
</x-slot>

<nav class="navbar navbar-expand-md bg-dark navbar-dark py-0 inner-nav">
    <!-- Brand -->
    <a class="navbar-brand font-weight-bold" href="#">{{ isset($title) ? $title : '' }}</a>

    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            @isset($links)
                @foreach($links as $link)
                    @php
                        $can = true;

                        if(isset($link['permission'])){
                            $can = request()->user()->can($link['permission']);
                        }
                    @endphp

                    @if($can)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $link['url'] }}">
                            {{ $link['text'] }}
                        </a>
                    </li>
                    @endif

                @endforeach
            @endisset
        </ul>
    </div>
</nav>
