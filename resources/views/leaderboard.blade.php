<html lang="en"><head>
    <meta charset="UTF-8">
    <title>岩锦</title>
    <link rel="stylesheet" href="{{URL::asset('css/leaderboard/normalize.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/leaderboard/leaderboard.css')}}">
    <script>
        window.console = window.console || function(t) {};
    </script>
    <script>
        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }
    </script>
</head>
<body translate="no">
<div id="app">
    <leaderboard-component></leaderboard-component>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>