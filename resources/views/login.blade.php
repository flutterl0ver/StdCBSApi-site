<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Вход</title>

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <style>
            form
            {
                display: flex;
                flex-direction: column;
                margin-left: 35%;
                margin-right: 35%
            }
            input.textinput
            {
                margin-bottom: 10px;
            }
            h1
            {
                text-align: center;
            }
            label.hint
            {
                font-size: 20px;
            }
        </style>
    </head>

    <body>
        <h1>Вход</h1>
        <form method="POST" action="/checklogin">
            @csrf
            Логин
            <input type="text" name="login" class="textinput" value="{{ session('login') }}">
            Пароль
            <input type="password" name="password" class="textinput" value="{{ session('password') }}">
            <input type="submit" value="Войти" class="submit">
            <label class="error">{!! session('error') !!}</label>
            <label class="hint">Нет аккаунта? <a href="/register">Зарегистрируйтесь!</a></label>
        </form>
    </body>
</html>
