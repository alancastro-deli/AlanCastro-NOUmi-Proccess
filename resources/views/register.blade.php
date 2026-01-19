@extends('app')
@section('content')

<div class="d-flex flex-column min-vh-100">
    <div class="container d-flex justify-content-center align-items-center flex-grow-1">
        <div class="card w-50 mt-4 mx-auto">
            <div class="card-header">
                <h4 class="card-title text-center" style="color:#BC955C;">Nuevo Usuario</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
                @endif
                 @if ($errors->has('register_error'))
                    <div class="alert alert-sm alert-danger text-center" role="alert">
                        {{ $errors->first('register_error') }}
                    </div>
                    @endif
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" style="color: #55585a; font-weight: bold;">Correo:</label>
                        <input type="email" name="correo" required class="form-control text-center">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color: #55585a; font-weight: bold;">Contraseña:</label>
                        <input type="password" name="password" required class="form-control text-center">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-25">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection