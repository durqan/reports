@extends('head')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Имя клиента</th>
            <th scope="col">Количество заказов</th>
            <th scope="col">Сумма заказов</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user_data as $user)
            <tr>
                <td>{{$user['name']}}</td>
                <td>{{$user['count']}}</td>
                <td>{{$user['sum']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection