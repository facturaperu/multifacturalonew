@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <tenant-configurations-form></tenant-configurations-form>
        </div>
        <div class="col-lg-6 col-md-12">
            <tenant-options-form></tenant-options-form>
        </div>
    </div>
@endsection