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
    <form method="post" class="bigform">
        <div>
            <h1>Информация о покупателе</h1><br>

            <div class="row">
                <div style="margin-right: 20px">
                    <label for="customer_phone">Номер телефона</label><br>
                    <input type="text" class="textinput" name="customer_phone">
                </div>
                <div>
                    <label for="customer_email">Адрес электронной почты</label><br>
                    <input type="text" class="textinput" name="customer_email">
                </div>
            </div>
            <hr>
        </div>
        @for ($i = 0; $i < 5; $i++)
        <div>
            <h1>Пассажир №{{ $i + 1 }} (Взрослый)</h1><br>
            <div class="column">
                <div class="row passenger">
                    <div class="small">
                        Пол<br>
                        <div class="row">
                            <button class="male" type="button">М</button>
                            <button class="female" type="button">Ж</button>
                        </div>
                    </div>
                    <div>
                        <label for="surname">Фамилия</label><br>
                        <input type="text" class="textinput" name="surname">
                    </div>
                    <div>
                        <label for="name">Имя</label><br>
                        <input type="text" class="textinput" name="name">
                    </div>
                    <div>
                        <label for="patronymic">Отчество (при наличии)</label><br>
                        <input type="text" class="textinput" name="patronymic">
                    </div>
                    <div>
                        <label for="birth_date">Дата рождения</label><br>
                        <input type="date" class="textinput" name="birth_date">
                    </div>
                </div>
                <div class="row passenger">
                    <div>
                        <label for="nationality">Гражданство</label><br>
                        <select name="nationality">
                            <option value="ru">Россия</option>
                        </select>
                    </div>
                    <div>
                        <label for="document">Тип документа</label><br>
                        <select name="document">
                            <option value="ru_passport">Паспорт гражданина РФ</option>
                            <option value="ru_travel_passport">Паспорт гражданина РФ</option>
                            <option value="birth_certificate">Свидетельство о рождении</option>
                            <option value="foreign_passport">Паспорт иностранного гражданина</option>
                        </select>
                    </div>
                    <div>
                        <label for="document_number">Номер документа</label><br>
                        <input type="text" class="textinput" name="document_number">
                    </div>
                    <div>
                        <label for="document_expire_date">Срок действия</label><br>
                        <input type="date" class="textinput" name="document_expire_date">
                    </div>
                </div>
                <div class="row passenger">
                    <div class="small">
                        <label for="passenger_phone">Телефон пассажира</label><br>
                        <input type="text" class="textinput" name="passenger_phone">
                    </div>
                    <div class="small">
                        <div class="row">
                            <div>
                                <label for="passenger_email">E-mail пассажира</label>
                                <input type="text" class="textinput" name="passenger_email">
                            </div>
                            <div style="margin-left: 25px; font-size: 0.9em; margin-top: auto">
                                <div>
                                    <input type="radio" name="no_email">отказ клиента
                                </div>
                                <div>
                                    <input type="radio" name="no_email">нет у клиента
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($i != 4) <hr> @endif
        </div>
            @endfor
    </form>

    @if(false)
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
@endif
</body>
</html>
