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

<body class="vh-100" data-bs-theme="light">
    <nav class="navbar bg-dark border-bottom border-body fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/inicio">Inicio</a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <button onclick="cambiarTema()" class="btn btn-sm rounded-fill text-center p-0 m-0">
                <i id="dl-icon" class="bi bi-moon-fill"></i>
            </button>
            <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm border-0 dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li style="font-size: x-small;" class="text-center">{{ Auth::user()->correo }}</li>
                        @if(Auth::user()->rol == 1)
                        <li><a class="dropdown-item " href="/register"><i class="bi bi-person-add"></i>
                                Nuevo
                                usuario</a></li>
                        <li>
                        @endif
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"
                                    style="background: none; border: none;">
                                    <i class="bi bi-box-arrow-left"></i>&nbsp;Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main class="container mt-5">
        @yield('content')
    </main>

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