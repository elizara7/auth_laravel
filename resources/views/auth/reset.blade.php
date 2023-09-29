@extends('layouts.app')
@section('title', 'Reset password')

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="fw-bold text-secondary">Reset password</h2>
                </div>
                <div class="card-body p-5">
                    <form action="#" method="POST" id="reset_form">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control rounded-0" placeholder="E-mail">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="npass" id="npass" class="form-control rounded-0" placeholder="New Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="cnpass" id="cnpass" class="form-control rounded-0" placeholder="Confirm new Password">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3 d-grid">
                            <input type="submit" value="Update password" class="btn btn-dark rounded-0" id="reset_btn">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

@endsection
