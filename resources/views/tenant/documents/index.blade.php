@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-index :is-client="{{ json_encode($is_client) }}"
                            :type-user="{{ json_encode(auth()->user()->type) }}"
                            :import_documents="{{ json_encode($import_documents) }}"
                            :import_documents_second="{{ json_encode($import_documents_second) }}"
                            :import_documents_xml="{{ json_encode($import_documents_xml) }}"></tenant-documents-index>

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