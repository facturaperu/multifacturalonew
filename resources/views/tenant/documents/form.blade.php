@extends('tenant.layouts.app')

@push('styles')
    <style type="text/css">
        .v-modal {
            opacity: 0.2 !important;
        }
    </style>
@endpush

@section('content')

    <tenant-documents-invoice></tenant-documents-invoice>

@endsection