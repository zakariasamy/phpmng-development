<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @foreach($users['data'] as $user)
        <div>
            {{$user->id}} -- 
            {{$user->name}}
        </div>
        <div>
    @endforeach
    {!! Phpmng\Database\Database::links($users['current_page'], $users['pages'], 3) !!}
</body>
</html>
