<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title', 'Weibo App') - Laravel 入门教程</title>
        <!-- <link rel="stylesheet" href="css/app.css"> -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>

    <body>
        @include('layouts._header')
        <div class="container">
            <div class="offset-1-md col-md-10">
                 @yield('content')
                 @include('layouts._footer')
            </div>
        </div>
    </body>
</html>
