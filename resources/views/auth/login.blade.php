@extends('layouts.auth')

@section('title', 'Gestión de inventario')

@section('content')
<style>
body {

    background: linear-gradient(135deg, #0083C9, #FFD700);
    background-color: #0090cd; 
}
    .card-md {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        margin: auto;
        max-width: 400px; 
        padding: 20px;
    }
    .card-body {
        background-color: #f1f3f5;
        border-radius: 10px;
        padding: 30px;
    }
    h2 {
        color: #343a40;
    }
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 5px;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .form-label-description {
        display: block;
        text-align: center;
        margin-top: 10px;
    }
    .form-label-description a {
    color: #f9f9f9;
    text-decoration: none;
}
.form-label-description a:hover {
    text-decoration: underline;
}
.alert-danger {
    color: #ff0000;
    background-color: #ffcccc;
    border-color: #ff0000;
    text-align: center;
}
</style>

<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">
            Inventario | Login
        </h2>


        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">
                    Correo electrónico
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control"
                       placeholder="nombre@correo.com"
                       autocomplete="off"
                       value="{{ old('email') }}"
                >
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">
                    Contraseña
                </label>

                <div class="input-group input-group-flat">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Contraseña"
                           autocomplete="off"
                    >
                </div>
            </div>

            <div class="mb-2">
                <label for="remember" class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input"/>
                    <span class="form-check-label">Recuérdame</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    Ingresar
                </button>
            </div>
        </form>
    </div>
</div>

<span class="form-label-description">
    <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
</span>
@endsection
