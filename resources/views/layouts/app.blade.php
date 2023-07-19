<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row my-2">
        <div class="col-lg-12 d-flex justify-content-between align-items-center mx-auto">
            <div>
                <h2 class="text-primary">@yield('heading')</h2>
            </div>
            <div class="d-flex align-items-center">
                @if(auth()->check())
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill mr-3">Logout</button>
                    </form>
                    <a href="@yield('link')" class="btn btn-primary rounded-pill">@yield('link_text')</a>
                @else
                    <a href="{{route('login-form')}}" class="btn btn-success rounded-pill mr-3">Login</a>
                    <a href="{{route('register-form')}}" class="btn btn-warning rounded-pill">Register</a>
                @endif
            </div>
        </div>
    </div>
    <hr class="my-2">
    @yield('content')
</div>
@yield('script')

<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
</body>

</html>
