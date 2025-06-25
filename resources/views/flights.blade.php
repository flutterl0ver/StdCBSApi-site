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
    <script>
        const response = JSON.parse('{!! json_encode(session('response'), JSON_UNESCAPED_UNICODE) !!}');
        const token = '{{ session('response')['respond']['token'] }}';
    </script>
    <h3 id="header">Результаты поиска</h3>
    <div class="table" id="table_div">
        <?php
            $data = session('response')['respond'];
            $i = 0;

            function getLocationsBegin($flightData): void
            {
                foreach ($flightData['itineraries']['itinerary'] as $itinerary) {
                    foreach ($itinerary['flights']['flight'] as $flight) {
                        $segment = $flight['segments']['segment'][0];
                        echo $segment['locationBegin']['name'] . '(' . $segment['locationBegin']['code'] . ')<br>';
                        $datetime = DateTime::createFromFormat('Y-m-dH:i:s', str_replace('T', '', $segment['dateBegin']));
                        echo $datetime->format('d.m.y ');
                        echo '<span style="color: var(--alt-button-color)">' . $datetime->format('H:i') . '</span><br>';
                    }
                }
            }

            function getLocationsEnd($flightData): void
            {
                foreach ($flightData['itineraries']['itinerary'] as $itinerary) {
                    foreach ($itinerary['flights']['flight'] as $flight) {
                        $segment = end($flight['segments']['segment']);
                        echo $segment['locationEnd']['name'] . '(' . $segment['locationEnd']['code'] . ')<br>';
                        $datetime = DateTime::createFromFormat('Y-m-dH:i:s', str_replace('T', '', $segment['dateEnd']));
                        echo $datetime->format('d.m.y ');
                        echo '<span style="color: var(--button-color)">' . $datetime->format('H:i') . '</span><br>';
                    }
                }
            }

            function getNumber($flightData): void
            {
                foreach ($flightData['itineraries']['itinerary'] as $itinerary) {
                    foreach ($itinerary['flights']['flight'] as $flight) {
                        $segment = $flight['segments']['segment'][0];
                        echo $flightData['carrier']['code'] . '-' . $segment['flightNumber'] . '<br>';
                    }
                }
            }

            function getEquipment($flightData): void
            {
                foreach ($flightData['itineraries']['itinerary'] as $itinerary) {
                    foreach ($itinerary['flights']['flight'] as $flight) {
                        $segment = $flight['segments']['segment'][0];
                        echo $flightData['carrier']['code'] . '-' . $segment['equipment']['name'] . '<br>';
                    }
                }
            }

            function formatDay($dayNumber): string
            {
                return match ((int)$dayNumber) {
                    0 => 'воскресенье',
                    1 => 'понедельник',
                    2 => 'вторник',
                    3 => 'среда',
                    4 => 'четверг',
                    5 => 'пятница',
                    6 => 'суббота',
                    default => '-',
                };
            }

            function formatMonth($monthNumber): string
            {
                return match ((int)$monthNumber) {
                    1 => 'января',
                    2 => 'февраля',
                    3 => 'марта',
                    4 => 'апреля',
                    5 => 'мая',
                    6 => 'июня',
                    7 => 'июля',
                    8 => 'августа',
                    9 => 'сентября',
                    10 => 'октября',
                    11 => 'ноября',
                    12 => 'декабря',
                    default => '-'
                };
            }

            function getPath($segment, $name): void
            {
                $datetime = DateTime::createFromFormat('Y-m-dH:i:s', str_replace('T', '', $segment['date' . $name]));
                echo formatDay($datetime->format('w')) . ', ' .
                    $datetime->format('j ') . formatMonth($datetime->format('n')) . ', ' . $datetime->format('H:i');
                echo '<br>';
                echo $segment['city' . $name]['name'] . ', ' . $segment['location' . $name]['name'] . ' (' . $segment['location' . $name]['code'] . ')';
            }
            ?>
        <table>
            <tr class="headers">
                <th>Вылет</th>
                <th>Прилёт</th>
                <th>Авиакомпания</th>
                <th>Рейс №</th>
                <th>Борт</th>
                <th>Стоимость</th>
                <th>...</th>
            </tr>
            @foreach($data['flightsGroup']['flightGroup'] as $flightData)
                    <?php if ($i >= 20) break; ?>
                <tr <?php if ($i % 2 == 1) echo 'class="dark"'; else echo 'class="bright"' ?>>
                    <td><?php getLocationsBegin($flightData) ?></td>
                    <td><?php getLocationsEnd($flightData) ?></td>
                    <td>{{ $flightData['carrier']['name'] }}</td>
                    <td><?php getNumber($flightData) ?></td>
                    <td><?php getEquipment($flightData) ?></td>
                    <td>{{ number_format($flightData['fares']['fareTotal']['total'], 2, ',', ' ') }} ₽</td>
                    <td>
                        <button class="btn-link" onclick="SwitchMoreInfo({{ $i }})">подробнее</button>
                    </td>
                </tr>
                <tr <?php if ($i % 2 == 1) echo 'class="dark"' ?>>
                    <td class="moreinfo" id="moreinfo{{ $i }}" colspan="7" style="display: none">
                        <table class="path">
                            @foreach($flightData['itineraries']['itinerary'] as $itinerary)
                                <tr>
                                    <th>Участок {{ $itinerary['token'] + 1 }}</th>
                                    <th colspan="4">
                                            <?php
                                            $firstSegment = $itinerary['flights']['flight'][0]['segments']['segment'][0];
                                            $lastSegment = end($itinerary['flights']['flight'][0]['segments']['segment']);

                                            echo $firstSegment['locationBegin']['name'] . ' — ';
                                            echo $lastSegment['locationEnd']['name'] . ', ';

                                            $datetime = DateTime::createFromFormat('Y-m-dH:i:s', str_replace('T', '', $firstSegment['dateBegin']));
                                            echo formatDay($datetime->format('w')) . ', ' .
                                                $datetime->format('j ') . formatMonth($datetime->format('n')) . ', ' . $datetime->format('H:i'); ?>
                                    </th>
                                </tr>
                                @foreach($itinerary['flights']['flight'] as $flight)
                                    @foreach($flight['segments']['segment'] as $segment)
                                        <tr>
                                            <td>
                                                {{ $segment['carrier']['name'].'('.$segment['carrier']['code'].')' }}
                                                <br>
                                                {{ $flightData['carrier']['code'].' - '.$segment['flightNumber'] }}
                                                <br>
                                                Мест: {{ $flight['seatsAvailable'] }}
                                            </td>
                                            <td>
                                                    <?php getPath($segment, 'Begin') ?>
                                            </td>
                                            <td>
                                                    <?php getPath($segment, 'End') ?>
                                            </td>
                                            <td>
                                                    <?php
                                                    $date1 = DateTime::createFromFormat('Y-m-dH:i:s', str_replace('T', '', $segment['dateBegin']));
                                                    $date2 = DateTime::createFromFormat('Y-m-dH:i:s', str_replace('T', '', $segment['dateEnd']));
                                                    $diff = $date1->diff($date2);
                                                    if ($diff->h > 0) echo $diff->h . ' час ';
                                                    echo $diff->i . ' мин';
                                                    ?>
                                            </td>
                                            <td>
                                                {{ $segment['serviceClass'].'('.$segment['bookingClass'].')' }}<br>
                                                {{ $segment['fareBasis'] }}<br>
                                                Багаж: {{ $segment['baggage']['value'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </table>
                        <t>Расписание стоимости</t>
                        <table class="cost">
                            <tr>
                                <th>тип пассажира</th>
                                <th>тариф</th>
                                <th>таксы</th>
                                <th>серв. сбор</th>
                                <th>сбор суб.</th>
                                <th>общая стоимость</th>
                                <th>кол-во</th>
                            </tr>
                                <?php $totalCount = 0; ?>
                            @foreach($flightData['fares']['fareSeats']['fareSeat'] as $fareSeat)
                                <tr>
                                    <td>
                                            <?php
                                            echo match ($fareSeat['passengerType']) {
                                                'ADULT' => 'Взрослый',
                                                'CHILD' => 'Ребенок',
                                                'INFANT' => 'Младенец без места'
                                            };
                                            ?>
                                    </td>
                                    <td>{{ number_format($fareSeat['tarif'], 2, ',', ' ') }} ₽</td>
                                    <td>{{ number_format($fareSeat['tax'], 2, ',', ' ') }} ₽</td>
                                    <td>{{ number_format($fareSeat['disc_ag'], 2, ',', ' ') }} ₽</td>
                                    <td>{{ number_format($fareSeat['fee_sa'], 2, ',', ' ') }} ₽</td>
                                    <td>{{ number_format($fareSeat['total'], 2, ',', ' ') }} ₽</td>
                                    <td>* {{ $fareSeat['count'] }}</td>
                                        <?php $totalCount += $fareSeat['count']; ?>
                                </tr>
                            @endforeach
                            @if($totalCount > 1)
                                <tr>
                                    <td>Итого за {{ $totalCount }} пассажиров</td>
                                    <td>{{ number_format($flightData['fares']['fareTotal']['tarif'], 2, ',', ' ') }}
                                        ₽
                                    </td>
                                    <td>{{ number_format($flightData['fares']['fareTotal']['tax'], 2, ',', ' ') }}
                                        ₽
                                    </td>
                                    <td>{{ number_format($flightData['fares']['fareTotal']['disc_ag'], 2, ',', ' ') }}
                                        ₽
                                    </td>
                                    <td>{{ number_format($flightData['fares']['fareTotal']['fee_sa'], 2, ',', ' ') }}
                                        ₽
                                    </td>
                                    <td>{{ number_format($flightData['fares']['fareTotal']['total'], 2, ',', ' ') }}
                                        ₽
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <div>
                            @if(false)
                                <button class="sendreq" onclick="SendSubClassesRequest({{ $i }})">Запрос подклассов</button>
                            @endif
                            <button class="sendreq" onclick="SendUptRequest({{ $i }})">Получить УПТ</button>
                            <button class="select right" onclick="SendSelectRequest({{ $i }})">Отправить запрос выбора</button>
                        </div>
                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach
        </table>
    </div>

    <div style="display: none" id="upt_div">
        <button onclick="CLoseUpt()">Назад</button>
        <t class="loaderText" id="uptLoaderText">Загрузка УПТ перелёта...</t>
        <span class="loader" id="uptLoader"></span>
        <div id="upt_content"></div>
    </div>
@else
    <t class="loaderText" id="loaderText">Загрузка перелётов...</t>
    <span class="loader" id="loader"></span>
    <form method="POST" action="/search" id="form" onsubmit="StartSearching()">
        @csrf
        <label for="token">Запросить результаты по токену</label><br>
        <div>
            <input type="text" autocomplete="off" name="token" id="token" class="textinput" value="{{ request()->query('token') }}">
            <input type="submit" class="submit" value="Запросить">
        </div>
    </form>
    @if(request()->query('token'))
        <script>
            StartSearching();
            document.getElementById('form').submit();
        </script>
    @endif
@endif
</body>
</html>
