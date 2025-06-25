<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>

<div class="head">
    <h6>TTBooking API</h6>
    <h2><a href="/" @if($page == 'index') class="active" @endif >Начальная страница</a></h2>
    <h2><a href="/flights" @if($page == 'flights') class="active" @endif >Результаты поиска</a></h2>
    <h2><a href="/select" @if($page == 'select') class="active" @endif >Выбор перелёта</a></h2>
</div>

