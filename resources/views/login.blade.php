<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</head>

<body class="d-flex flex-column min-vh-100" data-bs-theme="dark">
    <body class="vh-100" data-bs-theme="light">
    <nav class="navbar bg-dark border-bottom border-body fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <button onclick="cambiarTema()" class="btn btn-sm rounded-fill text-center p-0 m-0">
                <i id="dl-icon" class="bi bi-moon-fill"></i>
            </button>
        </div>
    </nav>
    <div class="container d-flex justify-content-center align-items-center flex-grow-1">
        <div class="card shadow" style="width: 90%; max-width: 600px;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <label style="color: #9F2241; font-size: 30px; font-weight: bold;">
                        Inventario
                    </label>
                </div>
                <div class="text-center mb-3">
                    @if ($errors->has('login_error'))
                    <div class="alert alert-sm alert-danger text-center" role="alert">
                        {{ $errors->first('login_error') }}
                    </div>
                    @endif
                </div>
                <form action="{{ route('login') }}" method="POST">

                    @csrf
                    <div class="mb-3">
                        <label class="form-label" style="color: #55585a; font-weight: bold;">Usuario:</label>
                        <input type="text" name="login" id="correo" required class="form-control text-center">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="color: #55585a; font-weight: bold;">Contraseña:</label>
                        <input type="password" name="password" id="password" required class="form-control text-center">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn w-50" style="color: #9F2241; font-weight: bold;">Iniciar
                            sesión</button>
                    </div>
            </div>
        </div>
    </div>
    </form>

<script>
    const temaOscuro = () => {
        document.querySelector("body").setAttribute("data-bs-theme", "dark");
        document.querySelector("#dl-icon").setAttribute("class", "bi bi-sun-fill text-warning");
        document.querySelector("body").style.backgroundImage = "none";
        localStorage.setItem('tema', 'dark');
    }

    const temaClaro = () => {
        document.querySelector("body").setAttribute("data-bs-theme", "light");
        document.querySelector("#dl-icon").setAttribute("class", "bi bi-moon-fill text-white");
        document.querySelector("body").style.backgroundImage = "url('imagenes/fondo1.jpg')";
        document.querySelector("body").style.backgroundSize = "cover";
        document.querySelector("body").style.backgroundPosition = "center";
        localStorage.setItem('tema', 'light');
    }

    const cambiarTema = () => {
        if (document.querySelector("body").getAttribute("data-bs-theme") === "light") {
            temaOscuro();
        } else {
            temaClaro();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const temaGuardado = localStorage.getItem('tema');
        if (temaGuardado === 'dark') {
            temaOscuro();
        } else {
            temaClaro();
        }
    });
    </script>
</body>

</html>