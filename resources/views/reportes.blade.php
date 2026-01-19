@extends('app')
@section('content')


<br><br><br>
<div class="container mt-4">
    <h2 class="mb-4">Reporte de Prestamos</h2>

    {{-- Formulario de búsqueda --}}
    <form method="GET" action="{{ route('Consulta.Reportes') }}" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar por ID o nombre..."
                    value="{{ request('busqueda') }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ route('Consulta.Reportes') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                </a>

                <a href="{{ route('reportes.exportar', ['busqueda' => request('busqueda')]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
                </a>

            </div>

        </div>
</div>
</form>

{{-- Tabla de reportes --}}
<div style="max-height: 450px; overflow-y: auto;" class="table-responsive">
    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Número de Serie</th>
                <th>Artículo</th>
                <th>Estado</th>
                <th>Prestatario</th>
                <th>Ubicación</th>
                <th>Ingeniero</th>
                <th>Fecha de prestamo</th>
                <th>Fecha de recepción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historial as $item)
            <tr>
                <td>{{ $item->id_prestamo }}</td>
                <td>{{ $item->numero_serie }}</td>
                <td>{{ $item->nombre_articulo }}</td>
                <td>
                    @if($item->estado == 'Devuelto')
                    <span class="badge bg-success">Devuelto</span>
                    @elseif($item->estado == 'Prestado')
                    <span class="badge bg-danger">Prestado</span>
                    @else
                    <span class="badge bg-secondary">No disponible</span>
                    @endif
                </td>
                <td>{{ $item->prestatario ?? '-' }}</td>
                <td>{{ $item->ubicacion ?? '-' }}</td>
                <td>{{ $item->ingeniero ?? '-' }}</td>
                <td>{{ $item->fecha_prestamo ?? '-' }}</td>
                <td>{{ $item->fecha_recibido ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No se encontraron resultados para "{{ request('busqueda') }}"
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection