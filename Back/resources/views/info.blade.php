<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body>
    @section('content')
    <div>
        <h1>
            Текущее подключение к базе данных
        </h1>
        <h2>
            Тип драйвера =>
        </h2>
        <p>{{ $connectionConfig['driver'] ?? 'Неизвестно' }}</p>
        <h2>
            Хост =>
        </h2>
        <p>{{ $connectionConfig['host'] ?? 'Неизвестно' }}</p>
        <h2>
            Порт =>
        </h2>
        <p>{{ $connectionConfig['port'] ?? 'Неизвестно' }}</p>
        <h2>
            База данных =>
        </h2>
        <p>{{ $database ?? 'public' }}</p>
        <h2>
            Имя схемы =>
        </h2>
        <p>{{ $connectionConfig['shema'] ?? 'Неизвестно' }}</p>
        <h2>
            Имя пользователя =>
        </h2>
        <p>{{ $connectionConfig['driver'] ?? 'Неизвестно' }}</p>
        @endsection
    </div>
</body>


</html>