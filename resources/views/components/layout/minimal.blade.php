<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>📚 {{ $title ?? 'Knowledger' }}</title>

    {{ $head }}

    @vite('resources/css/app.css')
</head>
<body class="select-none {{ $bodyClass ?? '' }}">

    {{ $body }}

</body>
</html>
