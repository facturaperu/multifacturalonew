@extends('tenant.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <tenant-currency-types-index></tenant-currency-types-index>
        </div>
        <div class="col-lg-6 col-md-12">
            <tenant-unit_types-index></tenant-unit_types-index>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <tenant-bank_accounts-index></tenant-bank_accounts-index>
        </div>
        <div class="col-lg-6 col-md-12">
            <tenant-banks-index></tenant-banks-index>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <tenant-attribute_types-index></tenant-attribute_types-index>
        </div>
    </div> 
@endsection