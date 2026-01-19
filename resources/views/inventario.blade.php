@extends('app')
@section('content')

<br>
<h1 class="text-muted text-center">INVENTARIO</h1>

{{-- Insertar nuevo artículo --}}
<form action="{{ route('insertarInventario') }}" method="POST" enctype="multipart/form-data" class="mb-5 border p-3 rounded">
    @csrf
    <h5 class="text-center">Agregar artículo</h5>
    <hr>

    <div class="row">
        <div class="col-md-4">
            <label for="nombre" class="form-label">No. Serie:</label>
            <input type="text" id="nombre" name="nombre" class="form-control form-control-sm" required>
        </div>

        <div class="col-md-4">
            <label for="imagen_seleccionada" class="form-label">Seleccionar imagen:</label>
            <select class="form-select form-select-sm" name="imagen_seleccionada" required>
                <option value=""></option>
                @foreach($imagenes as $imagen)
                    <option value="{{ $imagen->id }}">{{ $imagen->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-success w-100">Guardar producto</button>
        </div>
    </div>
</form>

{{-- Subir nueva imagen al catálogo --}}
<form action="{{ route('subirImagen') }}" method="POST" enctype="multipart/form-data" class="border p-3 rounded">
    @csrf
    <h5 class="text-center">Agregar imagen</h5>
    <hr>

    <div class="row">
        <div class="col-md-4">
            <label for="imagen_nueva" class="form-label">Seleccionar nueva imagen:</label>
            <input type="file" name="imagen_nueva" id="imagen_nueva" class="form-control" accept="image/*" required>
        </div>
        <div class="col-md-4">
            <label for="nombre_imagen" class="form-label">Nombre de la imagen:</label>
            <input type="text" name="nombre_imagen" class="form-control" placeholder="Inserte nombre">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-primary w-100">Subir imagen</button>
        </div>
    </div>
</form>

<br><br><br><br>

{{-- Tabla de inventario --}}
<div style="max-height: 850px; overflow-y: auto;" class="table-responsive">
    <table class="table-sm table table-hover table-striped text-center">
        <form method="GET" action="{{ route('mostrar.Inventario') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4 offset-md-6">
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar por ID o nombre..."
                        value="{{ request('busqueda') }}">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="{{ route('mostrar.Inventario') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>


<br>

        <thead class="table-dark">
            <tr>
                <th scope="col">Imagen</th>
                <th scope="col">No. Serie</th>
                <th scope="col">Disponibilidad</th>
            </tr>
        </thead>
        <tbody class="table-group-divider text-center align-middle">
            @foreach($inventarios as $inventario)
            <tr>
                <td>
                    <img width="120" src="{{ asset($inventario->url_inventario) }}" alt="" class="img-fluid rounded">
                </td>
                <td>{{ $inventario->nombre }}</td>
                <td>
                    <a href="{{ route('inventario.cambiarEstatus', $inventario->id) }}"
                        class="btn btn-sm {{ $inventario->estatus == 1 ? 'btn-danger' : 'btn-success' }}">
                        Cambiar a {{ $inventario->estatus == 1 ? 'No disponible' : 'Disponible' }}
                    </a>
                </td>
            </tr>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection