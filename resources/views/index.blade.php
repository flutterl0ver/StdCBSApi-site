<!DOCTYPE html>

@php
    const MAX_PEOPLE = 6;
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <?php $page = 'index'?>
    @include('components.head')

    <title>Найти билеты</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    <script>
        const MAX_PEOPLE = {{ MAX_PEOPLE }};
    </script>
    <script src="{{ asset('js/index.js') }}"></script>
</head>

<body>
<t class="loaderText" id="loaderText">Выполняется поиск...</t>
<span class="loader" id="loader"></span>
<form method="POST" action="/get-token" id="form" onsubmit="StartSearching()">
    @csrf
    <div class="row">
        <div class="from">
            <label for="from">Откуда</label>
            <input type="text" name="from" id="from" class="textinput" autocomplete="off" onfocusin="SwitchAirports('from', 'block')" onfocusout="SwitchAirports('from', 'none')" oninput="SearchAirports('from')" value="{{ old('from') }}">
            <div class="airports" id="airports_from"></div>
        </div>
        <button class="switch" type="button" onclick="SwapPlaces()"></button>
        <div class="to">
            <label for="to">Куда</label>
            <input type="text" name="to" id="to" class="textinput" autocomplete="off" onfocusin="SwitchAirports('to', 'block')" onfocusout="SwitchAirports('to', 'none')" oninput="SearchAirports('to')" value="{{ old('to') }}">
            <div class="airports" id="airports_to"></div>
        </div>
        <div class="date_to">
            <label for="date_to">Дата вылета</label>
            <input type="date" name="date_to" id="date_to" class="textinput"
                   value="{{ old('date_to') == null ? date('Y-m-d') : old('date_to') }}">
        </div>
        <div class="date_from" id="date_from_div" style="display: @if (old('hasDateFrom') == 'true') block @else none @endif">
            <label for="date_from">Дата обратно</label>
            <button class="closeButton" type="button" onclick="SwitchDateFrom()"></button>
            <br>
            <input type="date" name="date_from" id="date_from" class="textinput"
                   value="{{ old('date_from') == null ? date('Y-m-d') : old('date_from') }}">
        </div>
        <button class="date_from" type="button" id="date_from_button" onclick="SwitchDateFrom()"
                style="display: @if (old('hasDateFrom') != 'true') inline-block @else none @endif">Обратно
        </button>
    </div>
    <div class="row fieldsets">
        <div>
            <label for="adults">Взрослых</label><br>
            <button type="button" id="adults1" class="radio active" onclick="ChangeAdults(1)">1</button>
            @for ($i = 2; $i <= MAX_PEOPLE; $i++)
                <button type="button" id="adults{{ $i }}" class="radio"
                        onclick="ChangeAdults({{ $i }})">{{ $i }}</button>
            @endfor
        </div>
        <div>
            <label for="children">Детей</label><br>
            <button type="button" id="children0" class="radio active" onclick="ChangeChildren(0)">0</button>
            @for ($i = 1; $i < MAX_PEOPLE; $i++)
                <button type="button" id="children{{ $i }}" class="radio" onclick="ChangeChildren({{ $i }})">{{ $i }}</button>
            @endfor
        </div>
        <div>
            <label for="infants">Младенцев</label><br>
            <button type="button" id="infants0" class="radio active" onclick="ChangeInfants(0)">0</button>
            @for ($i = 1; $i < MAX_PEOPLE; $i++)
                <button type="button" id="infants{{ $i }}" class="radio"
                        onclick="ChangeInfants({{ $i }})">{{ $i }}</button>
            @endfor
        </div>
    </div>
    <div class="row">
        <div class="submit">
            <input type="submit" class="submit" value="Начать поиск">
            <br>
            <t class="error" id="errorText"></t>
        </div>
    </div>

    <input type="hidden" name="hasDateFrom" id="hasDateFrom" value="{{ old('hasDateFrom') }}">

    <input type="hidden" name="adults" id="adults" value="1">
    <input type="hidden" name="children" id="children" value="0">
    <input type="hidden" name="infants" id="infants" value="0">

    <script>
        @if(old('adults'))
            ChangeAdults({{ old('adults') }});
        @endif
        @if(old('children'))
            ChangeChildren({{ old('children') }});
        @endif
        @if(old('infants'))
            ChangeInfants({{ old('infants') }});
        @endif
    </script>
</form>
</body>
</html>
