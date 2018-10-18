@extends('layouts.app')

@section('extraHead')
    {{-- Styles --}}
    @if(config('laravelusers.enableBootstrapCssCdn'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelusers.bootstrapCssCdn') }}">
    @endif
    @if(config('laravelusers.enableAppCss'))
        <link rel="stylesheet" type="text/css" href="{{ asset(config('laravelusers.appCssPublicFile')) }}">
    @endif

    @yield('template_linked_css')

    {{-- Scripts --}}
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
@endsection


@section('scripts')
    {{-- Scripts --}}
    @if(config('laravelusers.enablejQueryCdn'))
        <script src="{{ asset(config('laravelusers.jQueryCdn')) }}"></script>
    @endif
    @if(config('laravelusers.enableBootstrapPopperJsCdn'))
        <script src="{{ asset(config('laravelusers.bootstrapPopperJsCdn')) }}"></script>
    @endif
    @if(config('laravelusers.enableBootstrapJsCdn'))
        <script src="{{ asset(config('laravelusers.bootstrapJsCdn')) }}"></script>
    @endif
    @if(config('laravelusers.enableAppJs'))
        <script src="{{ asset(config('laravelusers.appJsPublicFile')) }}"></script>
    @endif
    @include('laravelusers::scripts.toggleText')

    @yield('template_scripts')
@endsection
