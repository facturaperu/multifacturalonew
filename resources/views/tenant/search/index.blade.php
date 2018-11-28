@extends('tenant.layouts.web')

@section('content')

    {{--<section class="body-sign" style="max-width: 800px;">--}}
        {{--<div class="center-sign">--}}
            {{--<div class="">--}}
                {{--<div class="card card-header card-primary" style="background:#0088CC">--}}
                    {{--<p class="card-title text-center">Busqueda de documentos</p>--}}
                {{--</div>--}}
                {{--<div class="card-body">--}}
                    {{--<form method="POST" action="{{ route('search') }}">--}}
                        {{--@csrf--}}
                        {{--<div class="row">--}}
                            {{--<div class="form-group mb-3 col-md-4">--}}
                                {{--<label>Tipo de Documento</label>--}}
                                {{--<select class="form-control" id="document_type" name="document_type">--}}
                                    {{--<option value="01">Factura</option>--}}
                                    {{--<option value="03">Boleta</option>--}}
                                    {{--<option value="07">Nota de Credito</option>--}}
                                    {{--<option value="08">Nota de Debito</option>--}}
                                {{--</select>--}}
                                {{--@if ($errors->has('document_type'))--}}
                                    {{--<label class="error">--}}
                                        {{--<strong>{{ $errors->first('document_type') }}</strong>--}}
                                    {{--</label>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<div class="form-group mb-3 col-md-4">--}}
                                {{--<label>Ruc del Cliente</label>--}}
                                {{--<input id="ruc" type="text" name="ruc" class="form-control" value="{{ old('ruc') }}">--}}
                                {{--@if ($errors->has('ruc'))--}}
                                    {{--<label class="error">--}}
                                        {{--<strong>{{ $errors->first('ruc') }}</strong>--}}
                                    {{--</label>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<div class="form-group mb-3 col-md-4">--}}
                                {{--<label>Fecha de Emisión</label>--}}
                                {{--<input name="date" id="date" type="text" class="form-control">--}}
                                {{--@if ($errors->has('date'))--}}
                                    {{--<label class="error">--}}
                                        {{--<strong>{{ $errors->first('date') }}</strong>--}}
                                    {{--</label>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-sm-12 text-right">--}}
                                {{--<button type="submit" class="btn btn-primary mt-2">Buscar</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</form>--}}

                    {{--@if(!empty($reports) && $reports->count())--}}
                        {{--<div class="callout callout-info">--}}
                            {{--<p>Se encontraron {{$reports->count()}} registros.</p>--}}
                        {{--</div>--}}
                        {{--<div class="box-body table-responsive no-padding">--}}
                            {{--<table class="table table-striped table-bordered table-hover">--}}
                                {{--<thead>--}}
                                    {{--<tr>--}}
                                        {{--<th class="">#</th>--}}
                                        {{--<th class="">Tipo Documento</th>--}}
                                        {{--<th class="">Número</th>--}}
                                        {{--<th class="">Fecha emisión</th>--}}
                                        {{--<th class="">Estado</th>--}}
                                        {{--<th class="">Fecha creación </th>--}}
                                        {{--<th class="">Descargas</th>--}}
                                    {{--</tr>--}}
                                {{--</thead>--}}
                                {{--<tbody>--}}
                                    {{--@foreach($reports as $key => $value)--}}
                                    {{--<tr>--}}
                                        {{--<td>{{$value->id}}</td>--}}
                                        {{--<td>{{$value->document_type_code}}</td>--}}
                                        {{--<td>{{$value->series}}-{{$value->number}}</td>--}}
                                        {{--<td>{{$value->date_of_issue->format('Y-m-d')}}</td>--}}
                                        {{--<td>{{$value->state_type->description}}</td>--}}
                                        {{--<td>{{$value->created_at}}</td>--}}
                                        {{--<td class="py-2">--}}
                                            {{--<div class="row no-gutters pt-1">--}}
                                                {{--@if ($value->external_id)--}}
                                                    {{--<div class="col">--}}
                                                        {{--<a href="{{asset($value->download_pdf)}}" class="row no-gutters text-center text-dark" target="_blank">--}}
                                                            {{--<div class="col-12">--}}
                                                                {{--<i class="fa fa-file-pdf" style="font-size: 1.2rem"></i>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-12">--}}
                                                                {{--PDF--}}
                                                            {{--</div>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col">--}}
                                                        {{--<a href="{{asset($value->download_xml)}}" class="row no-gutters text-center text-dark" target="_blank">--}}
                                                            {{--<div class="col-12">--}}
                                                                {{--<i class="fa fa-file-alt" style="font-size: 1.2rem"></i>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-12">--}}
                                                                {{--XML--}}
                                                            {{--</div>--}}
                                                        {{--</a>--}}
                                                    {{--</div>--}}
                                                    {{--@if($value->state_type_id !== '01')--}}
                                                        {{--<div class="col">--}}
                                                            {{--<a href="{{asset($value->download_cdr)}}" class="row no-gutters text-center text-dark" target="_blank">--}}
                                                                {{--<div class="col-12">--}}
                                                                    {{--<i class="fa fa-file-alt" style="font-size: 1.2rem"></i>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="col-12">--}}
                                                                    {{--CDR--}}
                                                                {{--</div>--}}
                                                            {{--</a>--}}
                                                        {{--</div>--}}
                                                    {{--@endif--}}
                                                {{--@endif--}}
                                            {{--</div>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--@endforeach--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                            {{--<div class="pagination-wrapper">--}}

                                {{--{!! $reports->appends(['search' => Session::get('form_document_list')])->render() !!}--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<p class="text-center text-muted mt-3 mb-3">&copy; Copyright {{ date('Y') }}. Todos los derechos reservados</p>--}}
        {{--</div>--}}
    {{--</section>--}}

    <tenant-search-index></tenant-search-index>

@endsection

{{--@push('scripts')--}}
{{--<script src="{{ asset('porto-light/vendor/jquery/jquery.js')}}"></script>--}}
{{--<script src="{{asset('porto-light/vendor/jquery-ui/jquery-ui.js')}}"></script>--}}
{{--<script src="{{ asset('porto-light/vendor/moment/moment.js') }}"></script>--}}
{{--<script src="{{ asset('porto-light/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>--}}
{{--<script>--}}
    {{--$('#date').daterangepicker({--}}
        {{--format: 'YYYY-MM-DD',--}}
        {{--autoApply: true,--}}
        {{--singleDatePicker: true,--}}
        {{--showDropdowns: true,--}}
        {{--locale: {--}}
            {{--daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],--}}
            {{--monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],--}}
            {{--firstDay: 1--}}
        {{--}--}}
    {{--});--}}
{{--</script>--}}

{{--@endpush--}}