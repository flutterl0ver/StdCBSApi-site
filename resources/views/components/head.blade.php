@php use App\Services\ContextService; @endphp

<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/renderjson/renderjson.js') }}"></script>
<script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>

<div class="head">
    <h6>TTBooking API</h6>
    <h2><a href="/" @if($page == 'index') class="active" @endif >Начальная страница</a></h2>
    <h2><a href="/flights" @if($page == 'flights') class="active" @endif >Результаты поиска</a></h2>
</div>

<div class="jsonPopupBackground" id="jsonPopupBackground" onclick="JsonClick()">
    <div style="width: 100%; padding: 150px 0; height: max-content">
        <div class="jsonPopup" id="jsonPopup">
            <button class="closeButton" onclick="CloseJson()"></button>
            <h3>Просмотр запроса</h3>
            <hr>
            <div id="jsonText"></div>
        </div>
    </div>
</div>
<script>
    document.getElementById('jsonPopupBackground').addEventListener('click', JsonClick);
</script>

