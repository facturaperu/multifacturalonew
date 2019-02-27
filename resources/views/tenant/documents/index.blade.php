@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-index :is-client="{{ json_encode($is_client) }}"></tenant-documents-index>

@endsection

@push('scripts')
<script type="text/javascript">
	$(function(){
    'use strict';
        $(".tableScrollTop,.tableWide-wrapper").scroll(function(){
            $(".tableWide-wrapper,.tableScrollTop")
                .scrollLeft($(this).scrollLeft());
        });
    });
</script>
@endpush