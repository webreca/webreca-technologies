<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Webreca Technologies</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet">

    <!-- Styles / Scripts -->
    {{-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else

        @endif --}}
    <style>
        #button {
            display: inline-block;
            background-color: #E678F2;
            width: 50px;
            height: 50px;
            text-align: center;
            border-radius: 4px;
            position: fixed;
            bottom: 30px;
            right: 30px;
            transition: background-color .3s,
                opacity .5s, visibility .5s;
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
        }

        #button::after {
            content: "\f077";
            font-family: FontAwesome;
            font-weight: normal;
            font-style: normal;
            font-size: 2em;
            line-height: 50px;
            color: #fff;
        }

        #button:hover {
            cursor: pointer;
            background-color: #333;
        }

        #button:active {
            background-color: #555;
        }

        #button.show {
            opacity: 1;
            visibility: visible;
        }

        /* Styles for the content section */

        .content {
            width: 77%;
            margin: 50px auto;
            font-family: 'Merriweather', serif;
            font-size: 17px;
            color: #6c767a;
            line-height: 1.9;
        }

        @media (min-width: 500px) {
            .content {
                width: 43%;
            }

            #button {
                margin: 30px;
            }
        }

        .content h1 {
            margin-bottom: -10px;
            color: #03a9f4;
            line-height: 1.5;
        }

        .content h3 {
            font-style: italic;
            color: #96a2a7;
        }
    </style>
</head>

<body class="content>
    <a id="button"></a>
    {{-- <video id="background-video" autoplay loop muted>
            <source src="{{ asset('assets/videos/coming-soon.mp4') }}" type="video/mp4">
        </video> --}}
    <img src="{{ asset('assets/temp/1.png') }}" width="100%" height="100%">
    <img src="{{ asset('assets/temp/2.png') }}" width="100%" height="100%">
    <img src="{{ asset('assets/temp/3.png') }}" width="100%" height="100%">
    <img src="{{ asset('assets/temp/4.png') }}" width="100%" height="100%">
    <img src="{{ asset('assets/temp/5.png') }}" width="100%" height="100%">
    <img src="{{ asset('assets/temp/6.png') }}" width="100%" height="100%">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var btn = $('#button');

        $(window).scroll(function() {
            if ($(window).scrollTop() > 300) {
                btn.addClass('show');
            } else {
                btn.removeClass('show');
            }
        });

        btn.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, '300');
        });
    </script>
</body>

</html>
