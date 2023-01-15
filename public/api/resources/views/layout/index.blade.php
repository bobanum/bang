<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="{{asset('css/style.css')}}"/>
    <title>{{$title}}</title>
</head>
<body>
    <div class="main-container">
        @include('layout.header')
        @include('layout.nav')
        <div class="body">
            @section("content")
            @show
        </div>
        @include('layout.footer')
    </div>
</body>
</html>