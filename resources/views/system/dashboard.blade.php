@extends('system.layouts.app')

@section('content')

    <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Dashboard</span></li>
            </ol>


            <div class="right-wrapper pull-right">
                <!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a> -->
            </div>
        </header>

    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-6">
            <section class="card card-body panel panel-featured-left panel-featured-primary">
                <div class="panel-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon bg-info">
                                <i class="fa fa-building"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Clientes Registrados</h4>
                                <div class="info">
                                    <strong class="amount">{{ $clients }}</strong>
                                    <span class="text-primary"></span>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="{{route('system.clients.index')}}" class="text-muted text-uppercase">Ver todos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection