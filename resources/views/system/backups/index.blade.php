@extends('system.layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <section class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Listado Reciente</h4>
                </div>
                <div class="card-body">
                    <table class="table table-responsive-md table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Archivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{str_replace("db-backup/", "", $file)}}</td>
                                    <td>
                                        <a class="text-info" href="{{ url("backups/".$file) }}">
                                            <i class="fas fa-download"></i>
                                        </a>
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
