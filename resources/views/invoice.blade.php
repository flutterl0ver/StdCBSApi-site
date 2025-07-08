<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Квитанция №{{ $token }}{{ request()->query('passenger') }}</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
    <script>console.log(JSON.parse('{!! json_encode($ticket, JSON_UNESCAPED_UNICODE) !!}'))</script>
</head>

<?php
    function formatDay($dayNumber): string
    {
        return match ((int)$dayNumber) {
            0 => 'Вс',
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
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
?>

<body>
    <div class="head">
        <img alt="logo" src="{{ asset('images/logo.png') }}">
        <div class="column">
            <b>Ураура</b>
        </div>
        <div class="right column">
            По всем вопросам обращайтесь по номеру телефона или адресу электронной почты<br>
            +7 777 1231231<br>
            krutoy.email@gmail.com
        </div>
    </div>
    <div style="padding: 20px" class="row">
        <div>
            <span style="font-size: 20px">ЭЛЕКТРОННЫЙ БИЛЕТ</span><br>
            <b>(маршрут-квитанция для пассажира)</b>
        </div>
        <div class="right">
            <span style="font-size: 18px">Заказ №{{ $token }}</span><br>
            Код бронирования: ?
        </div>
    </div><hr>
    <div style="padding: 15px">
        <table style="width: 100%">
            <tr>
                <th>Пассажир</th>
                <th>Номер документа</th>
                <th>Номер билета</th>
                <th>Бонусная карта</th>
                <th>Продажа</th>
            </tr>
            <tr>
                <td>{{ $ticket['passenger']['passport']['lastName'] }} {{ $ticket['passenger']['passport']['firstName'] }}</td>
                <td>{{ $ticket['passenger']['passport']['number'] }}</td>
                <td>{{ $ticket['number'] }}</td>
                <td></td>
                <td>{{ date('d.m.Y', strtotime($ticket['issueDate'])) }}</td>
            </tr>
        </table>
        <br>
        Рейс под брендом авиакомании {{ $ticket['carrier']['name'] }}
    </div><hr>
    <div style="padding: 15px">
        <?php $i = 0 ?>
        @foreach ($ticket['itineraries']['itinerary'] as $itinerary)
            @foreach ($itinerary['flights']['flight'] as $flight)
                @foreach ($flight['segments']['segment'] as $segment)
                    @if($i != 0) <hr class="dotted"> @endif
                    <table>
                        <tr>
                            <td>{{ $segment['cityBegin']['name'] }}, {{ $segment['locationBegin']['name'] }} ({{ $segment['locationBegin']['code'] }})</td>
                            <td>{{ $segment['cityEnd']['name'] }}, {{ $segment['locationEnd']['name'] }} ({{ $segment['locationEnd']['code'] }})</td>
                            <th>Авиакомпания-перевозчик</th>
                            <th>Рейс</th>
                            <th>Тариф</th>
                            <th>Багаж</th>
                            <th>Статус</th>
                        </tr>
                        <tr>
                            <?php
                                $dateBegin = new DateTime($segment['dateBegin']);
                                $dateEnd = new DateTime($segment['dateEnd']);
                                $dayBegin = formatDay($dateBegin->format('w'));
                                $dayEnd = formatDay($dateEnd->format('w'));
                                $monthBegin = formatMonth($dateBegin->format('m'));
                                $monthEnd = formatMonth($dateEnd->format('m'));

                                echo "<td>{$dateBegin->format('H:i')}<br>".
                                "{$dayBegin}, {$dateBegin->format('j')} {$monthBegin} {$dateBegin->format('Y')}</td>";

                                echo "<td>{$dateEnd->format('H:i')}<br>".
                                    "{$dayEnd}, {$dateEnd->format('j')} {$monthEnd} {$dateEnd->format('Y')}</td>";
                            ?>
                            <td>{{ $segment['carrier']['name'] }}</td>
                            <td>{{ $segment['carrier']['code'] }}-{{ $segment['flightNumber'] }}</td>
                            <td>{{ $segment['serviceClass'] }}<br>{{ $segment['fareBasis'] }}</td>
                            <td>{{ $segment['baggage']['value'] }}</td>
                            <td>OK</td>
                        </tr>
                    </table>
                    <?php $i++ ?>
                @endforeach
            @endforeach
        @endforeach
    </div><hr>
    <div style="padding: 15px">
        Расчёт тарифа:<br><br>
        <table class="cost">
            <tr>
                <td>ТАРИФ:</td>
                <td>{{ $ticket['fares']['fareTotal']['tarif'] }} ₽</td>
                <td></td>
            </tr>
            <tr>
                <td>СБОР/TAX:</td>
                <td>{{ $ticket['fares']['fareTotal']['tax'] }} ₽</td>
                <td></td>
            </tr>
            <tr>
                <td>СБОР СА:</td>
                <td>{{ $ticket['fares']['fareTotal']['fee_sa'] }} ₽</td>
                <td></td>
            </tr>
            <tr>
                <td>СБОР АСБ:</td>
                <td>{{ $ticket['fares']['fareTotal']['disc_ag'] }} ₽</td>
                <td>(НДС не облагается)</td>
            </tr>
            <tr><td colspan="3"><hr class="dotted"></td></tr>
            <tr>
                <td>ВСЕГО К ОПЛАТЕ:</td>
                <td>{{ $ticket['fares']['fareTotal']['total'] }} ₽</td>
                <td>В т.ч. НДС 10%: 0 ₽</td>
            </tr>
        </table>
    </div><hr>
    <div style="padding: 15px">
        Время вылета и прилёта указано местное.<br>
        Для регистрации и посадки на рейс пассажирам необходимо иметь при себе оригинал документа, внесённый
        при бронировании.<br><br>В случае не использования пассажиром полётного участка маршрута, без предварительного
        отказа от места, последующие участки будут автоматически аннулированны.<br><br>Пассажиры, перевозка которых
        имеет пункт назначения или остановку не в стране отправления, уведомляются о том, что положения международных
        договоров, известных как Монреальская конвенция или предшествующая ей Варшавская конвенция с дополнительными
        соглашениями к ней, могут применяться в отношении всей перевозки, включая любой отрезок, находящийся в пределах
        территории страны. Для таких пассажиров применимая конвенция, включая особые условия перевозки, обусловленные
        тарифами, регулируют и могут ограничивать ответственность перевозчика.<br>Внимание: Кассовый чек является
        обязательным документом при осуществлении расчетов (п. 2, ст. 1.2 ФЗ от 22.05.2003 No 54-ФЗ (ред. от
        26.07.2019)). Согласно письму Министерства финансов России No 03-03-07/69371 от 09.09.2019 оправдательными
        документами, подтверждающими расходы на приобретение авиабилета для предоставления по месту работы является
        маршрут квитанция на бумажном носителе и посадочный талон.<br><br>
        http://www.iatatravelcentre.com/e-ticket-notice/Russian_Federation/Russian/<br><br>Любую информацию по Вашему
        заказу (условие применения тарифа, условия возврата, допустимый багаж и т.д.) можете получить по телефону<br>
        +7 777 1231231
    </div><hr>
    <div style="padding: 20px">
        С ПРАВИЛАМИ ПРИМЕНЕНИЯ ТАРИФА, УСЛОВИЯМИ ОБМЕНА И ВОЗВРАТА АВИАБИЛЕТА ОЗНАКОМЛЕН(А), С ПРАВИЛАМИ ПЕРЕСЕЧЕНИЯ
        ГОСУДАРСТВЕННОЙ ГРАНИЦЫ И ВИЗОВЫМИ ТРЕБОВАНИЯМИ СТРАНЫ ПРЕБЫВАНИЯ ОЗНАКОМЛЕН(А) С ДАТАМИ ПЕРЕЛЕТА,
        НАПРАВЛЕНИЯМИ ВЫЛЕТА/ПРИБЫТИЯ ОЗНАКОМЛЕН(А), ФИО И ПАСПОРТНЫЕ ДАННЫЕ УКАЗАНЫ ВЕРНО<br><br>СОГЛАСЕН(А),
        ПРЕТЕНЗИЙ НЕ ИМЕЮ.<br><br>ПОДПИСЬ ПАССАЖИРА ________________<br><br>ДАТА «___» ___________ 20___Г.
    </div><hr>
    <span style="font-size: 25px; padding: 15px">Приятного Вам полёта!</span>
</body>
</html>
