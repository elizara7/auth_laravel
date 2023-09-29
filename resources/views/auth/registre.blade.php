@extends('layouts.app')
@section('title', 'Registre')

@section('content')
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center min-vh-100">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h2 class="fw-bold text-secondary">Registre</h2>
                    </div>
                    <div class="card-body p-5">
                        <div id="show_success_alert"></div>
                        <form action="#" method="POST" id="registre_form">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="name" id="name" class="form-control rounded-0"
                                    placeholder="Nom complet">
                                <div class="invalid-feedback"></div>
                            </div>

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
                                <input type="password" name="cpassword" id="cpassword" class="form-control rounded-0"
                                    placeholder="Confirm Password">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3 d-grid">
                                <input type="submit" value="Registre" class="btn btn-dark rounded-0" id="registre_btn">
                            </div>

                            <div class="text-center text-secondary">
                                <div>Already have an account? <a href="/" class="text-decoration-none">Connectez-vous
                                        ici</a></div>
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
            $("#registre_form").submit(function(e) {
                e.preventDefault();
                $("#registre_btn").val('Please wait....');
                $.ajax({
                    url: '{{ route('auth.registre') }}',
                    method: 'post',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 400) {
                            showError('name', res.messages.name);
                            showError('email', res.messages.email);
                            showError('password', res.messages.password);
                            showError('cpassword', res.messages.cpassword);
                            $("#registre_btn").val('Registre');
                        } else if (res.status == 200) {
                            $("#show_success_alert").html(showMessage('success', res.messages));
                            $("#registre_form")[0].reset();
                            removeValidationClasses("#registre_form");
                            $("#registre_btn").val('Registre');
                        }
                    }
                });
            });
        });
    </script>

@endsection
