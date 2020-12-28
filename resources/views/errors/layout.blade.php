@extends('layout.admin')

@section('title-suffixed', 'Stránka nenalezena')

@section('content-withmenu')
    <div class="error">
        <h1>Error @yield('code')</h1>
        @yield('error-description')
        <a href="https://docs.google.com/forms/d/e/1FAIpQLSfry7CQD0vPpuC_VB7xGR6NUF2WdPUytQwX8KipKoZcIYxbdA/viewform?usp=pp_url&entry.1025781741={{ urlencode(url()->full()) }}&entry.456507920=@yield('code')"
            target="_blank"
            class="btn btn-secondary">
            <i class="fas fa-exclamation-triangle pr-1"></i> NAHLÁSIT
        </a>
    </div>
@endsection
