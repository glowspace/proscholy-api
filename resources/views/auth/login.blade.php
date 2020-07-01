@extends('layout.client')

@push('scripts')
    <script>document.getElementById('email').focus();</script>
@endpush

@section('content')
    <div class="center-container">
        <div class="w-100" style="max-width:400px">
            <div class="card text-white bg-info mb-3 text-center">
                <div class="card-body">
                    <p class="card-text">Dál už můžeme jenom my, redaktoři ProScholy.cz.</p>
                    <p class="card-text">
                        Chceš se stát jedním z&nbsp;nás?
                        <br>Napiš na <a class="text-white" href="mailto:redakce@proscholy.cz">redakce@proscholy.cz</a>.
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

                        <div class="form-group row">
                            <div class="col-11 offset-1">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-11 offset-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

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
