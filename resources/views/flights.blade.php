<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <?php $page = 'flights'?>
    @include('components.head')

    <title>Результаты поиска</title>
    <link rel="stylesheet" href="{{ asset('css/flights.css') }}">
    <script src="{{ asset('js/flights.js') }}"></script>
</head>
<body>
@if (session('response') && session('response')['respond']['token'] != '')
    <span class="loaderText" id="selectLoaderText">Загрузка данных перелёта...</span>
    <span class="loader" id="selectLoader"></span>
    <script>
        const response = JSON.parse('{!! json_encode(session('response'), JSON_UNESCAPED_UNICODE) !!}');
        const token = '{{ session('response')['respond']['token'] }}';
    </script>
    <h3 id="header">Результаты поиска</h3>
    <div class="table" id="table_div">
        <?php
            $flights = session('response')['respond']['flightsGroup']['flightGroup'];
            ?>

        @include('components.flights-table')
    </div>

    <h3 id="uptHeader" style="display: none">Просмотр УПТ перелёта</h3>
    <div style="display: none" id="upt_div">
        <button onclick="CloseUpt()">Назад</button>
        <span class="loaderText" id="uptLoaderText">Загрузка УПТ перелёта...</span>
        <span class="loader" id="uptLoader"></span>
        <div id="upt_content"></div>
    </div>
@else
    <span class="loaderText" id="loaderText">Загрузка перелётов...</span>
    <span class="loader" id="loader"></span>
    <form method="POST" action="/search-result" id="form" onsubmit="startSearching()">
        @csrf
        <label for="token">Запросить результаты по токену</label><br>
        <div>
            <input type="text" autocomplete="off" name="token" id="token" class="textinput" value="{{ request()->query('token') ?? old('token') }}">
            <input type="submit" class="submit" value="Запросить">
        </div>
        <span class="error">{{ $errors->first('error') }}</span>
    </form>
    @if(request()->query('token'))
        <script>
            startSearching();
            document.getElementById('form').submit();
        </script>
    @endif
@endif
</body>
</html>
