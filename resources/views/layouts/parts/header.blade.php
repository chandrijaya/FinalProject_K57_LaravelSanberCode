<style>
    .full-height {
        height: 60px;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

</style>

<header>
    
		<div class="container-fluid position-relative no-side-padding">
            <div class="flex-center position-ref full-height">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="#">{{ ucfirst(Auth()->user()->name)}}</a>
                            <a href="{{url('logout')}}" class="text-danger">Logout</a>
                        @else
                            <a href="{{ route('login') }}">Login</a>
            
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
			<a href="#" class="logo"><img src={{ asset("/Bona/images/logo.png")}} alt="Logo Image"></a>

			<div class="menu-nav-icon" data-nav-menu="#main-menu"><i class="ion-navicon"></i></div>

			<ul class="main-menu visible-on-click" id="main-menu">
				<li><a href="/">Home</a></li>
				<li><a href="/forum">Forum</a></li>
				<li><a href="/forum/create">Create</a></li>
			</ul><!-- main-menu -->

			<div class="src-area">
				<form>
					<button class="src-btn" type="submit"><i class="ion-ios-search-strong"></i></button>
					<input class="src-input" type="text" placeholder="Type of search">
				</form>
			</div>

        </div><!-- conatiner -->
    </div>
</header>