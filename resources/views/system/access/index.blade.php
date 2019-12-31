@extends('system.layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <section class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Listado Reciente</h4>
                </div>
                <div class="card-body">
                    <table class="table table-responsive-md table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Archivo</th>
                                <th colspan="3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $user)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$user->email}}</td>
                                    <td colspan="3" class="p-0">
                                        <table class="table table-responsive-md m-0">
                                            <tr>
                                                <th>IP</th>
                                                <th>Ultimo ingreso</th>
                                                <th>Ultima salida</th>
                                            </tr>
                                @foreach($user->authentications as $log)
                                    <tr>
                                        <td>{{$log->ip_address}}</td>
                                        <td>{{$log->login_at}}</td>
                                        <td>{{$log->logout_at}}</td>
                                    </tr>
                                @endforeach
                                        </table>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>
            </section>
        </div>
    </div>

@endsection
