<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('css/theme/dark.css') }}" id="darkTheme" disabled="disabled">
<link rel="stylesheet" href="{{ asset('css/theme/light.css') }}" id="lightTheme">

<script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    let isDarkMode = false;
    if(getCookie('darkmode') === 'true') switchDarkMode();
</script>

<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="head">
    <h6>TTBooking API</h6>
    <h2><a href="/" @if($page == 'index') class="active" @endif >Начальная страница</a></h2>
    <h2><a href="/flights" @if($page == 'flights') class="active" @endif >Результаты поиска</a></h2>
    <h2><a href="/select" @if($page == 'select') class="active" @endif >Выбор перелёта</a></h2>
    <button class="darkmode" id="darkmodeButton" onclick="switchDarkMode()"></button>
</div>
