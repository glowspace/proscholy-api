@extends('layout.admin')

@section('title-suffixed', 'Přihlášení')
@section('wrapper-classes', 'hide-report')

@push('scripts')
    <script>document.getElementById('email').focus();</script>
@endpush

@section('content-withmenu')
    <div class="container-login d-flex justify-content-center">
        <div class="w-100" style="max-width:400px">
            <div class="card border-primary bg-primary-light mb-3 text-center" style="font-size:110%">
                <div class="card-body">
                    <p class="card-text">Přihlášení pro autorizované členy týmu ProScholy.</p>
                    <p class="card-text">
                        Chceš se podílet na tvorbě projektu?
                        <br>Napiš na <a href="mailto:redakce@proscholy.cz">redakce@proscholy.cz</a>.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Přihlašovací formulář</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="Přihlašovací formulář" id="loginForm" class="mb-0">
                        @csrf
                        <div class="form-group row flex-nowrap overflow-hidden">
                            <label for="email" class="col-1 text-right h5 mb-0 d-flex align-items-center flex-shrink-0">
                                <i class="far fa-envelope"></i>
                            </label>

                            <div class="col-11">
                                <input id="email" placeholder="E-mail" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row flex-nowrap overflow-hidden">
                            <label for="password" class="col-1 text-right h5 mb-0 d-flex align-items-center flex-shrink-0">
                                <i class="fas fa-key"></i>
                            </label>

                            <div class="col-11">
                                <input id="password" placeholder="Heslo" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        @if ($errors->has('email') || $errors->has('password'))
                            <div class="form-group row">
                                <div class="col-11 offset-1">
                                    <span class="text-danger d-block" role="alert">
                                        <strong>
                                            {{ $errors->has('email') ? $errors->first('email') : $errors->first('password') }}
                                        </strong>
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row">
                            <div class="col-11 offset-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ !old() || old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        Zapamatovat si přihlášení
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-11 offset-1">
                                <button type="submit" class="btn btn-primary btn-outline">
                                    Přihlásit se
                                </button>

                                {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Zapomněl jsem heslo
                                </a> --}}
                            </div>
                        </div>

                        <recaptcha site-key="{{ config('recaptcha.key') }}"></recaptcha>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
