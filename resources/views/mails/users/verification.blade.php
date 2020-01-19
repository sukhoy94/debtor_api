<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirm registration</title>
</head>
<body>
<p>
    Hello {{$user->name}}!
</p>
<p>
    Thank you for your registration on {{ $app_name }}! Please activate your account with this link:
    <a href="{{ $confirmation_link }}">{{ $confirmation_link }}</a>
</p>
</body>
</html>
