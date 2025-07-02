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

    <form method="post" class="bigform" action="/make-booking">
        @csrf
        <div>
            <h1>Информация о покупателе</h1><br>

            <div class="row">
                <div style="margin-right: 20px">
                    <label for="customer_phone">Номер телефона</label><br>
                    <input type="text" class="textinput @if ($errors->first('customer_phone')) error @endif" name="customer_phone" value="{{ old('customer_phone') }}">
                    <t class="error">{{ $errors->first('customer_phone') }}</t>
                </div>
                <div>
                    <label for="customer_email">Адрес электронной почты</label><br>
                    <input type="text" class="textinput" name="customer_email">
                </div>
            </div>
            <hr>
        </div>
        <?php
            $i = 0;
            $countries = \App\Models\Country::select('code', 'name_ru')->get();
        ?>
        @foreach ($flights[0]['fares']['fareSeats']['fareSeat'] as $seat)
            @for ($j = 0; $j < $seat['count']; $j++)
                <div>
                    <h1>Пассажир №{{ $i + 1 }} ({{ match($seat['passengerType']) { "ADULT" => 'Взрослый', "CHILD" => "Ребёнок", "INFANT" => "Младенец" } }})</h1><br>
                    <div class="column">
                        <div class="row passenger">
                            <div class="small">
                                Пол<br>
                                <div class="row">
                                    <button class="male" id="male{{ $i }}" type="button" onclick="SwitchGender({{ $i }}, 'male')">М</button>
                                    <button class="female" id="female{{ $i }}" type="button" onclick="SwitchGender({{ $i }}, 'female')">Ж</button>
                                </div>
                                <input type="hidden" name="gender{{ $i }}" id="gender{{ $i }}">
                            </div>
                            <div>
                                <label for="surname{{ $i }}">Фамилия</label><br>
                                <input type="text" class="textinput" name="surname{{ $i }}">
                            </div>
                            <div>
                                <label for="name{{ $i }}">Имя</label><br>
                                <input type="text" class="textinput" name="name{{ $i }}">
                            </div>
                            <div>
                                <label for="patronymic{{ $i }}">Отчество (при наличии)</label><br>
                                <input type="text" class="textinput" name="patronymic{{ $i }}">
                            </div>
                            <div>
                                <label for="birth_date{{ $i }}">Дата рождения</label><br>
                                <input type="date" class="textinput" name="birth_date{{ $i }}">
                            </div>
                        </div>
                        <div class="row passenger">
                            <div>
                                <label for="citizenship{{ $i }}">Гражданство</label><br>
                                <select  name="citizenship{{ $i }}">
                                    @foreach ($countries as $country)
                                        <option value={{ $country->code }} @if ($country->code == 'RU') selected="selected" @endif>{{ $country->name_ru }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="document_type{{ $i }}">Тип документа</label><br>
                                <select name="document_type{{ $i }}">
                                    <option value="ru_passport" selected="selected">Паспорт гражданина РФ</option>
                                    <option value="ru_travel_passport">Заграничный паспорт РФ</option>
                                    <option value="birth_certificate">Свидетельство о рождении</option>
                                    <option value="foreign_passport">Паспорт иностранного гражданина</option>
                                </select>
                            </div>
                            <div>
                                <label for="document_number{{ $i }}">Номер документа</label><br>
                                <input type="text" class="textinput" name="document_number{{ $i }}">
                            </div>
                            <div>
                                <label for="document_expire_date{{ $i }}">Срок действия</label><br>
                                <input type="date" class="textinput" name="document_expire_date{{ $i }}">
                            </div>
                        </div>
                        <div class="row passenger">
                            <div class="small">
                                <label for="passenger_phone{{ $i }}">Телефон пассажира</label><br>
                                <input type="text" class="textinput" name="passenger_phone{{ $i }}">
                            </div>
                            <div class="small">
                                <div class="row">
                                    <div>
                                        <label for="passenger_email{{ $i }}">E-mail пассажира</label>
                                        <input type="text" class="textinput" onfocus="EnableEmail({{ $i }})" id="passenger_email{{ $i }}" name="passenger_email{{ $i }}">
                                    </div>
                                    <div style="margin-left: 25px; font-size: 0.9em; margin-top: auto">
                                        <div>
                                            <input type="radio" onclick="DisableEmail({{ $i }})" id="email_refused{{ $i }}" name="no_email{{ $i }}" value="refused">отказ клиента
                                        </div>
                                        <div>
                                            <input type="radio" onclick="DisableEmail({{ $i }})" id="email_absent{{ $i }}" name="no_email{{ $i }}" value="absent">нет у клиента
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <input type="hidden" name="passenger_type{{ $i }}" value="{{ $seat['passengerType'] }}">
                <?php $i++; ?>
            @endfor
        @endforeach
        <input type="hidden" name="passengers_count" value="{{ $i }}">
        <input type="hidden" name="token" value="{{ session('response')['respond']['token'] }}">
        <div>
            <input class="submit" type="submit">
        </div>
    </form>
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
