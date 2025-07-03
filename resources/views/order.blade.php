<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <?php $page = 'order' ?>
    @include('components.head')

    <title>Действия над заказом</title>
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">

    <script src="{{ asset('js/order.js') }}"></script>
</head>

<body>
    @if (session('response') && session('response')['respond']['token'] != '')
        <script>
            const token = '{{ session('response')['respond']['token'] }}';
        </script>

        <h3>Заказ №{{ session('response')['respond']['token'] }}</h3>

        <div class="table" id="table_div">
            <?php
                $flights = session('response')['respond']['bookingFile']['reservations']['reservation'][0]['products']['airTicket'];
                $buttons = false;
            ?>

            @include ('components.flights-table')
        </div>
    @else
        <span class="loaderText" id="loaderText">Загрузка данных бронирования...</span>
        <span class="loader" id="loader"></span>
        <form method="POST" action="/display-order" id="form" onsubmit="startSearching()">
            @csrf
            <label for="token">Получить данные заказа по токену</label><br>
            <div>
                <input type="text" autocomplete="off" name="token" id="token" class="textinput"
                       value="{{ request()->query('token') ?? old('token') }}">
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
