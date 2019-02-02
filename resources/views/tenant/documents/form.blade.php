@extends('tenant.layouts.app')

@push('styles')
    <style type="text/css">
        .v-modal {
            opacity: 0.2 !important;
        }
        .border-custom {
            border-color: rgba(0,136,204, .5) !important;
        }
    </style>
@endpush

@section('content')

    <tenant-documents-invoice></tenant-documents-invoice>

@endsection