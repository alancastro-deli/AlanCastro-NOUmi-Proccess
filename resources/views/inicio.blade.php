@extends('app')
@section('content')

<br><br>
<div class="row text-center d-flex justify-content-center align-items-center flex-grow-1">
    @if(Auth::user()->rol == 1)
    <div class="col-3">
        <a class="btn btn-outline-secondary btn-lg" href="/inventario"><img width="200" class="img-fluid"
                src="/11536552.png" alt=""></a>
        <h4 class="text-muted">Inventario</h4>
    </div>
    @endif
    <div class="col-3">
        <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal"><img width="200" class="img-fluid" src="/10986551.png" alt=""></button>
        <!-- Modal -->
        <form action="{{ route('insertarPrestamo') }}" method="post">
            @csrf
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-center" id="exampleModalLabel">Prestamo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row-md-3 text-start">
                                <label for="articulo">Artículo:</label>
                                <select class="form-select form-select-sn" name="articulo" id="">
                                    <option value="">Seleccionar</option>
                                    @foreach($articulos_disp as $articulos)
                                    <option value="{{ $articulos->id}}">{{ $articulos->nombre }} &nbsp; - &nbsp;
                                        {{ $articulos->nombre_articulo }}</option>
                                    @endforeach
                                </select>
                                <br>
                                <div class=" row-md-3 text-start">
                                    <label class="form-label" for="prestatario">Nombre:</label><input id="prestatario"
                                        name="prestatario" class="form-control" type="text"></input>
                                    <br>
                                    <label class="form-label" for="ubicacion">Ubicación:</label><input id="ubicacion"
                                        name="ubicacion" class="form-control" type="text"></input>
                                    <br>
                                    <label class="form-label" for="ingeniero">Prestador:</label><input id="ingeniero"
                                        name="ingeniero" class="form-control" type="text"></input>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <h4 class="text-muted">Prestamo</h4>
    </div>
    <div class="col-3">
        <button class="btn btn-outline-secondary btn-lg" type="button" data-bs-toggle="modal"
            data-bs-target="#exampleModal2"><img width="200" class="img-fluid" src="/14488605.png" alt=""></button>
        <!-- Modal -->
        <form action="{{ route('insertarDaño') }}" method="post">
            @csrf
            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 text-center" id="exampleModalLabel2">Dañados</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row-md-3 text-start">
                                <label for="art">Artículo:</label>
                                <select class="form-select form-select-sn" name="art" id="">
                                    <option value="">Seleccionar</option>
                                    @foreach($articulos_disponibles as $art)
                                    <option value="{{ $art->id}}">{{ $art->num_serie }} &nbsp; - &nbsp;
                                        {{ $art->nombre_art }}</option>
                                    @endforeach
                                </select>
                                <br>
                                <div class=" row-md-3 text-start">
                                    <label class="form-label" for="descripcion">Descripción del daño:</label><input
                                        id="descripcion" name="descripcion" class="form-control" type="text"></input>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <h4 class="text-muted">Dañados</h4>
    </div>
    <div class="col-3">
        <a class="btn btn-outline-secondary btn-lg" href="/reportes"><img width="200" class="img-fluid"
                src="/11342850.png" alt=""></a>
        <h4 class="text-muted">Reportes</h4>
    </div>
</div>
<br><br><br>
<div style="max-height: 450px; overflow-y: auto;" class="table-responsive">
    <table class="table-sm table table-hover table-striped text-center">
        <thead>
            <h4 class="text-center text-bg-warning p-3">EN PRESTAMO</h4>
        </thead>
        <thead class="table-warning">
            <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Nombre</th>
                <th scope="col">Ubicación</th>
                <th scope="col">Prestador</th>
                <th scope="col">Artículo</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>
        <tbody class="table-group-divider text-center align-middle">
            @foreach($prestamotes as $prestamo)
            <tr>

                <td>{{ $prestamo->created_at }}</td>
                <td>{{ $prestamo->nombre }}</td>
                <td>{{ $prestamo->ubicacion }}</td>
                <td>{{ $prestamo->ingeniero }}</td>
                <td>{{ $prestamo->serie }} &nbsp; - &nbsp; {{ $prestamo->nombre_articulo }}</td>
                <td>
                    <a href="{{ route('inventario.cambiarEstatusPrestamo', $prestamo->id) }}"
                        class="btn btn-sm {{ $prestamo->estatus_prestamo == 1 ? 'btn-danger' : 'btn-success' }}">
                        {{ $prestamo->estatus_prestamo == 1 ? 'Eliminar prestamo' : 'En inventario' }}
                    </a>
                </td>
                @endforeach
        </tbody>
    </table>
</div>
<br><br>
<div style="max-height: 450px; overflow-y: auto;" class="table-responsive">
    <table class="table-sm table table-hover table-striped text-center">
        <thead>
            <h4 class="text-center text-bg-danger p-3">DAÑADOS</h4>
        </thead>
        <thead class="table-danger">
            <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Artículo</th>
                <th scope="col">Descripción del daño</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>
        <tbody class="table-group-divider text-center align-middle">
            @foreach($dañotes as $dañados)
            <tr>

                <td>{{ $dañados->created_at}}</td>
                <td>{{ $dañados->num_serie}} &nbsp; - &nbsp; {{ $dañados->nombre_art}}</td>
                <td>{{ $dañados->descripcion}}</td>
                <td>
                    <a href="{{ route('inventario.cambiarEstatusDañado', $dañados->id) }}"
                        class="btn btn-sm {{ $dañados->estatus_dañado == 1 ? 'btn-danger' : 'btn-success' }}">
                        {{ $dañados->estatus_dañado == 1 ? 'Eliminar artículo' : 'En inventario' }}
                    </a>
                </td>
                @endforeach
        </tbody>
    </table>
</div>

@endsection