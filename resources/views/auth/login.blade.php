@extends('layouts.app')
@section('title', 'Login')

@section('content')
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center min-vh-100">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h2 class="fw-bold text-secondary">Login</h2>
                    </div>
                    <div class="card-body p-5">
                        <div id="login_alert"></div>
                        <form action="#" method="POST" id="login_form">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email" id="email" class="form-control rounded-0"
                                    placeholder="E-mail">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <input type="password" name="password" id="password" class="form-control rounded-0"
                                    placeholder="Password">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <a href="/forgot" class="text-decoration-none">Forgot Password?</a>
                            </div>

                            <div class="mb-3 d-grid">
                                <input type="submit" value="Login" class="btn btn-dark rounded-0" id="login_btn">
                            </div>

                            <div class="text-center text-secondary">
                                <div>Vous n’avez pas de compte ? <a href="/registre"
                                        class="text-decoration-none">Inscrivez-vous ici</a></div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function() {
            $("#login_form").submit(function(e) {
                e.preventDefault();
                $("#login_btn").val('Veuillez patientez...');
                $.ajax({
                    url: '{{ route('auth.login') }}',
                    method: 'post',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 400) {
                            showError('email', res.messages.email);
                            showError('password', res.messages.password);
                            $("#loginBtn").val('Login');
                        } else if (res.status == 401) {
                            $("#login_alert").html(showMessage('danger', res.messages));
                            $("#login_btn").val('Login');
                        } else {
                            if (res.status == 200 && res.messages == 'success') {
                                window.location = '{{ route('profile') }}';
                            }
                        }
                    }
                });
            });
        });
    </script>

@endsection
