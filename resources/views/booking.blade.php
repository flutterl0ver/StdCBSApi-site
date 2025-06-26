<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <?php $page = 'booking' ?>
    @include('components.head')

    <title>Выбор перелёта</title>
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">

    <script src="{{ asset('js/booking.js') }}"></script>
</head>

<body>
@if (session('response') && session('response')['respond']['token'] != '')
    <script>
        const response = JSON.parse('{!! json_encode(session('response'), JSON_UNESCAPED_UNICODE) !!}');
        const token = '{{ session('response')['respond']['token'] }}';
    </script>
    <h3 id="header">Бронирование перелёта</h3>
    <div class="table" id="table_div">
        <?php
            $flights = session('response')['respond']['flightsGroup']['flightGroup'];
            ?>

        @include('components.flights-table')
    </div>

    <h3 id="uptHeader" style="display: none">Просмотр УПТ перелёта</h3>
    <div style="display: none" id="upt_div">
        <button onclick="CloseUpt()">Назад</button>
        <t class="loaderText" id="uptLoaderText">Загрузка УПТ перелёта...</t>
        <span class="loader" id="uptLoader"></span>
        <div id="upt_content"></div>
    </div>
@else
    <t class="loaderText" id="loaderText">Загрузка данных перелёта...</t>
    <span class="loader" id="loader"></span>
    <form method="POST" action="/select-result" id="form" onsubmit="startSearching()">
        @csrf
        <label for="token">Получить результаты выбора по токену</label><br>
        <div>
            <input type="text" autocomplete="off" name="token" id="token" class="textinput"
                   value="{{ request()->query('token') }}">
            <input type="submit" class="submit" value="Запросить">
        </div>
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
