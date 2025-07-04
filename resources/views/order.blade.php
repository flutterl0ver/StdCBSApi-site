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
    @if (session('response') && ['token'] != '')
        <?php $response = session('response')['respond']; ?>
        <script>
            const token = '{{ $response['token'] }}';
        </script>
        <h3>Заказ №{{ $response['token'] }}</h3>
        @if ($response['bookingFile']['status'] == 'CANCELLED')
            <h3 class="cancelled">(отменён)</h3>
        @else
            <span class="loaderText" id="loaderText">Отмена заказа...</span>
            <span class="loader" id="loader"></span>

            <div id="deletedDiv" style="display: none">
                <h3>Заказ успешно отменён.</h3>
                <a class="back" href="/"><button class="back">На главную</button></a>
            </div>
        @endif

        <div class="table" id="table_div">
            <?php
                $bookingFile = $response['bookingFile'];
                $flights = [$bookingFile['reservations']['reservation'][0]['products']['airTicket'][0]];
                $buttons = false;
                $i = 0;
                $countries = \App\Models\Country::select('code', 'name_ru')->get();
            ?>

            @include ('components.flights-table')
        </div>
        <div class="info" id="info">
            @foreach ($bookingFile['reservations']['reservation'][0]['products']['airTicket'] as $ticket)
                <?php $passenger = $ticket['passenger'] ?>
                <div>
                    <h1>Пассажир №{{ $i + 1 }} ({{ match($passenger['type']) { "ADULT" => 'Взрослый', "CHILD" => "Ребёнок", "INFANT" => "Младенец" } }})</h1><br>
                    <div class="column">
                        <div>ФИО: {{ $passenger['passport']['lastName'] }} {{ $passenger['passport']['firstName'] }} {{ $passenger['passport']['middleName'] }}</div>
                        <div>Пол: {{ match($passenger['passport']['gender']) { "MALE" => 'Мужской', "FEMALE" => 'Женский' } }}</div>
                        <div>Дата рождения: <?php
                                                $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', $passenger['passport']['birthday']);
                                                echo $datetime->format('d.m.Y');
                                                ?></div>
                        <div>Номер телефона: {{ $passenger['countryCode'] }} ({{ $passenger['areaCode'] }}) {{ $passenger['phoneNumber'] }}</div>
                        <div>Email: <?php
                                        $refused = $passenger['isEmailRefused'];
                                        $absent = $passenger['isEmailAbsent'];
                                        if($refused) echo 'Отказ';
                                        else if($absent) echo 'Нет';
                                        else echo strtolower($passenger['email']);
                                        ?></div>
                    </div>
                    <hr>
                </div>
                <?php $i++; ?>
            @endforeach
            @if ($response['bookingFile']['status'] != 'CANCELLED')
                <button class="submit red" onclick="cancelBooking()">Отменить заказ</button><br>
            @endif
            <span class="error" id="errorText"></span>
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
