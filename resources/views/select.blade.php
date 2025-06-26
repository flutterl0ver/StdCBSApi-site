<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <?php $page = 'select'?>
    @include('components.head')

    <title>Выбор перелёта</title>
    <link rel="stylesheet" href="{{ asset('css/select.css') }}">

    <script src="{{ asset('js/select.js') }}"></script>
</head>

<body>
    <t class="loaderText" id="loaderText">Загрузка данных перелёта...</t>
    <span class="loader" id="loader"></span>
    <form method="POST" action="/select" id="form" onsubmit="startSearching()">
        @csrf
        <label for="token">Получить результаты выбора по токену</label><br>
        <div>
            <input type="text" autocomplete="off" name="token" id="token" class="textinput" value="{{ request()->query('token') }}">
            <input type="submit" class="submit" value="Запросить">
        </div>
    </form>
</body>
</html>
