<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav" style="background-color:lightblue">
    @auth
        {{ auth()->user()->email }}



    @endauth
    @guest

    @endguest

    <div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">        <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbarResponsive" style="width: 100%">
        <ul class="navbar-nav ml-auto">
            @guest
                <li><a class="nav-link js-scroll-trigger" href="{{ route('login') }}">{{ __('Login') }}</a></li>
            @else
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="{{ route('profile') }}">
                        <i class="fa fa-user fa-2x" aria-hidden="true" style="filter: invert(0%);"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="{{ route('logout') }}">
                        {{ __('Logout') }}
                    </a>
                </li>
            @endguest
        </ul>
    </div>
</nav>
<br><br>
