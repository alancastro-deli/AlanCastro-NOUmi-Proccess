<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class inventarioController extends Controller
{

     public function showLoginForm()
    {
     
        if (Auth::check()) {
            return redirect('/');
        }

       
        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'login' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt(['correo' => $request->login, 'password' => $request->password])) {
        $user = Auth::user();
        $username = $user->correo;

        if ($user->activo != 1) {
            DB::table('usuarios_ingreso')->insert([
                'id_user' => $user->id,
                'fecha_acceso' => now(),
                'ingreso' => 0,
                'nom_usuario' => $username,
            ]);

            Auth::logout();
            return back()->withErrors(['login_error' => 'Tu cuenta está inactiva.']);
        }

        DB::table('usuarios_ingreso')->insert([
            'id_user' => $user->id,
            'fecha_acceso' => now(),
            'ingreso' => 1,
            'nom_usuario' => $username,
        ]);

        return redirect()->route('mostrar.inicio');
    }

    return back()->withErrors(['login_error' => 'Credenciales incorrectas.']);
}
    
    public function logout()
    {
        // Cerrar sesión
        Auth::logout();
    
        // Eliminar los datos de la sesión
        session()->forget('user_id');
        session()->forget('username');
    
        // Redirigir a la página de login
        return redirect()->route('login');
    }

    public function register(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'correo' => 'required|email',
        'password' => 'required|min:3|max:255',
    ]);

    $correo = $request->correo;
    $password = $request->password;

    // Verificar si el correo ya existe en la base de datos
    $existeCorreo = User::where('correo', $correo)->exists();

    if ($existeCorreo) {
        return back()->withErrors(['register_error' => 'El correo ya está registrado.'])->withInput();
    }

    // Crear el usuario
    $user = User::create([
        'correo' => $correo,
        'password' => Hash::make($password),
        'activo' => 1,
        'rol' => 2,
    ]);

    // Mensaje de éxito
    return redirect()->back()->with('success', 'Usuario registrado correctamente.');
}

/*---------------------------------------------------------------*/

    public function mostrarInicio(Request $request)
{
    $user = Auth::user();

    // ===========================
    // ARTÍCULOS DISPONIBLES
    // ===========================
    $articulos_disp = DB::table('inventario')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->leftJoin('prestamos', function ($join) {
            $join->on('inventario.id', '=', 'prestamos.id_articulo')
                 ->where('prestamos.estatus_prestamo', '=', 1);
        })
        ->leftJoin('dañados', function ($join) {
            $join->on('inventario.id', '=', 'dañados.id_art')
                 ->where('dañados.estatus_dañado', '=', 1);
        })
        ->select(
            'inventario.id',
            'inventario.nombre',
            'imagenes.nombre as nombre_articulo'
        )
        ->whereNull('prestamos.id')
        ->whereNull('dañados.id')
        ->where('inventario.estatus', 1)
        ->get();

    // ===========================
    // ARTÍCULOS DISPONIBLES PARA DAÑADOS
    // ===========================
    $articulos_disponibles = DB::table('inventario')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->leftJoin('dañados', function ($join) {
            $join->on('inventario.id', '=', 'dañados.id_art')
                 ->where('dañados.estatus_dañado', '=', 1);
        })
        ->leftJoin('prestamos', function ($join) {
            $join->on('inventario.id', '=', 'prestamos.id_articulo')
                 ->where('prestamos.estatus_prestamo', '=', 1);
        })
        ->select(
            'inventario.id',
            'inventario.nombre as num_serie',
            'imagenes.nombre as nombre_art'
        )
        ->whereNull('dañados.id')
        ->whereNull('prestamos.id')
        ->where('inventario.estatus', 1)
        ->get();

    // ===========================
    // PRESTAMOS
    // ===========================
    $prestamotes = DB::table('prestamos')
        ->join('inventario', 'prestamos.id_articulo', '=', 'inventario.id')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->select(
            'inventario.nombre as serie',
            'prestamos.id',
            'prestamos.id_articulo',
            'prestamos.nombre',
            'prestamos.ubicacion',
            'prestamos.ingeniero',
            'prestamos.estatus_prestamo',
            'prestamos.created_at',
            'imagenes.nombre as nombre_articulo'
        )
        ->where('prestamos.estatus_prestamo', 1)
        ->when($user->rol != 1, function ($query) use ($user) {
            // Si NO es admin → solo sus préstamos
            return $query->where('prestamos.id_usuario', $user->id);
        })
        ->get();

    // ===========================
    // DAÑADOS
    // ===========================
    $dañotes = DB::table('dañados')
        ->join('inventario', 'dañados.id_art', '=', 'inventario.id')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->select(
            'inventario.nombre as num_serie',
            'dañados.id',
            'dañados.id_art',
            'dañados.descripcion',
            'dañados.estatus_dañado',
            'dañados.created_at',
            'imagenes.nombre as nombre_art'
        )
        ->where('dañados.estatus_dañado', 1)
        ->when($user->rol != 1, function ($query) use ($user) {
            // Si NO es admin → solo sus dañados
            return $query->where('dañados.id_usuario', $user->id);
        })
        ->get();

    return view('inicio', compact('articulos_disp', 'articulos_disponibles', 'prestamotes', 'dañotes'));
}


/*---------------------------------------------------------------*/

    public function insertarPrestamo(Request $request)
{
    // Validar campos
    $request->validate([
        'articulo' => 'required',
        'prestatario' => 'required',
        'ubicacion' => 'required',
        'ingeniero' => 'required',
    ]);

    // Insertar préstamo con el usuario actual
    $id = DB::table('prestamos')->insertGetId([
        'created_at' => now(),
        'nombre' => $request->input('prestatario'),
        'ubicacion' => $request->input('ubicacion'),
        'ingeniero' => $request->input('ingeniero'),
        'estatus_prestamo' => 1,
        'id_articulo' => $request->input('articulo'),
        'id_usuario' => Auth::user()->id, // 👈 usuario logueado
    ], 'id');

    session()->flash('mensaje', 'El prestamo ha sido guardado correctamente con el numero de serie: ' . $id);

    return redirect('/inicio');
}


/*---------------------------------------------------------------*/

    public function insertarDaño(Request $request){

    // Validar campos
    $request->validate([
            'art' => 'required',
            'descripcion' => 'required'
        ]);
    
    // Insertar daños con el usuario actual
        $id = DB::table('dañados')->insertGetId([
            'created_at' => now(),
            'descripcion' => $request->input('descripcion'),
            'estatus_dañado' => 1,
            'id_art' => $request->input('art'),
            'id_usuario' => Auth::user()->id, // 👈 usuario logueado
        ], 'id');

        session()->flash('mensaje', 'El prestamo ha sido guardado correctamente con el numero de serie: ' . $id);

        return redirect('/inicio');
    }

/*---------------------------------------------------------------*/

    /* Inserta un nuevo registro en el inventario. */

    public function insertarInventario(Request $request){
    // Validar los datos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'imagen_seleccionada' => 'required|integer', // debe ser un ID
    ]);

    $imagenId = $request->input('imagen_seleccionada'); // el id de la imagen seleccionada

    // Insertar el registro en la tabla "inventario"
    DB::table('inventario')->insert([
        'nombre' => $request->input('nombre'),
        'imagen' => $imagenId,  // guarda el ID
        'estatus' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Producto agregado correctamente al inventario.');
    }

/*---------------------------------------------------------------*/

    /* Sube una nueva imagen al catálogo */

    public function subirImagen(Request $request){
    // Validar la imagen y el nombre opcional
    $request->validate([
        'imagen_nueva' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        'nombre_imagen' => 'nullable|string|max:255',
    ]);

    $imagen = $request->file('imagen_nueva');
    $nombreOriginal = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $imagen->getClientOriginalExtension();

    // Usar el nombre proporcionado por el usuario o el nombre original
    $nombreArchivo = $request->input('nombre_imagen') 
        ? $request->input('nombre_imagen') . '.' . $extension 
        : time() . '_' . $imagen->getClientOriginalName();

    $imagen->move(public_path('images'), $nombreArchivo);

    $ruta = 'images/' . $nombreArchivo;

    // Insertar la imagen en la tabla "imagenes"
    DB::table('imagenes')->insert([
        'imagen' => $ruta, // ruta real de la imagen
        'nombre' => $request->input('nombre_imagen') ?: $nombreOriginal,
    ]);

    return redirect()->back()->with('success', 'Imagen subida correctamente al catálogo.');
    }

/*---------------------------------------------------------------*/

    public function mostrarInventario(Request $request){
    // Captura el valor ingresado en el campo de búsqueda
    $busqueda = trim($request->input('busqueda'));

    // Construimos la consulta base
    $query = DB::table('inventario')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->select(
            'inventario.id',
            'inventario.nombre',
            'inventario.estatus',
            'imagenes.imagen as url_inventario'
        )
        ->orderBy('inventario.estatus', 'ASC');

    // Si hay algo que buscar
    if (!empty($busqueda)) {
        $query->where(function ($q) use ($busqueda) {
            // Buscar por número de serie (ID)
            if (is_numeric($busqueda)) {
                $q->where('inventario.id', '=', $busqueda);
            }

            // Buscar por nombre del artículo
            $q->orWhere('inventario.nombre', 'LIKE', "%{$busqueda}%");

            // (Opcional) Buscar por nombre de la imagen
            $q->orWhere('imagenes.nombre', 'LIKE', "%{$busqueda}%");
        });
    }

    // Ejecutar la consulta
    $inventarios = $query->get();

    // Obtener todas las imágenes (si las necesitas en la vista)
    $imagenes = DB::table('imagenes')->get();

    // Retornar la vista con los datos
    return view('inventario', compact('inventarios', 'imagenes'));
    }

/*---------------------------------------------------------------*/

    public function cambiarEstatus($id){
    $estatus = DB::table('inventario')->where('id', $id)
    ->select('estatus')
    ->first();
   $actualizado = $estatus->estatus == 1 ? 0 : 1;
    //Buscar el registro por ID
    $actualizar = DB::table('inventario')->where('id', $id)
    ->update([
      'estatus' => $actualizado
    ]);

    if (!$actualizar) {
        return redirect()->back()->with('error', 'Elemento no encontrado');
    }

    return redirect()->back()->with('success', 'Estatus actualizado correctamente');
    }
    
/*---------------------------------------------------------------*/

    public function cambiarEstatusPrestamo($id){
    // Buscar el préstamo específico por su ID
    $prestamo = DB::table('prestamos')->where('id', $id)->first();

    if (!$prestamo) {
        return redirect()->back()->with('error', 'Préstamo no encontrado');
    }

    // Cambiar el estatus (1 -> 0 o 0 -> 1)
    $nuevoEstatus = $prestamo->estatus_prestamo == 1 ? 0 : 1;

    // Actualizar solo ese registro
    DB::table('prestamos')
        ->where('id', $id)
        ->update([
            'estatus_prestamo' => $nuevoEstatus,
            'finished_at' => now()
        ]);

    return redirect()->back()->with('success', 'Estatus actualizado correctamente');
    }

/*---------------------------------------------------------------*/

    public function cambiarEstatusDañado($id){
    $estatus_dañado = DB::table('dañados')->where('id', $id)
    ->select('estatus_dañado')
    ->first();
   $actualizado = $estatus_dañado->estatus_dañado == 1 ? 0 : 1;
    //Buscar el registro por ID
    $actualizar = DB::table('dañados')->where('id', $id)
    ->update([
      'estatus_dañado' => $actualizado,
      'finished_at' => now()
    ]);

    if (!$actualizar) {
        return redirect()->back()->with('error', 'Elemento no encontrado');
    }

    return redirect()->back()->with('success', 'Estatus actualizado correctamente');
    }

/*---------------------------------------------------------------*/

    public function ConsultaReportes(Request $request)
{
    $busqueda = $request->input('busqueda');

    $query = DB::table('prestamos')
        ->join('inventario', 'prestamos.id_articulo', '=', 'inventario.id')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->select(
            'prestamos.id as id_prestamo',
            'inventario.id as id_articulo',
            'inventario.nombre as numero_serie',
            'imagenes.nombre as nombre_articulo',
            'prestamos.nombre as prestatario',
            'prestamos.ubicacion',
            'prestamos.ingeniero',
            'prestamos.created_at as fecha_prestamo',
            'prestamos.finished_at as fecha_recibido',
            DB::raw("
                CASE 
                    WHEN prestamos.estatus_prestamo = 1 THEN 'Prestado'
                    ELSE 'Devuelto'
                END as estado
            ")
        );
        if(Auth::user()->rol==2){
        $query->where('prestamos.id_usuario', Auth::user()->id); // solo reportes del usuario logueado
        }
        

    // Filtro de búsqueda
    if (!empty($busqueda)) {
        $query->where(function($q) use ($busqueda) {
            if (is_numeric($busqueda)) {
                $q->where('prestamos.id', '=', $busqueda)
                  ->orWhere('inventario.id', '=', $busqueda);
            }

            $q->orWhereRaw('LOWER(inventario.nombre) LIKE LOWER(?)', [$busqueda])
              ->orWhereRaw('LOWER(imagenes.nombre) LIKE LOWER(?)', [$busqueda])
              ->orWhereRaw('LOWER(prestamos.nombre)LIKE LOWER(?)', [$busqueda])
              ->orWhereRaw('LOWER(prestamos.ubicacion)LIKE LOWER(?)', [$busqueda]);
        });
    }

    $historial = $query->orderBy('prestamos.created_at', 'desc')->get();

    return view('reportes', compact('historial'));
}

/*---------------------------------------------------------------*/

    public function exportExcel(Request $request)
{
    $busqueda = $request->get('busqueda');

    $query = DB::table('prestamos')
        ->join('inventario', 'prestamos.id_articulo', '=', 'inventario.id')
        ->join('imagenes', 'inventario.imagen', '=', 'imagenes.id')
        ->select(
            'prestamos.id',
            'prestamos.id_articulo',
            'prestamos.nombre',
            'prestamos.ubicacion',
            'prestamos.estatus_prestamo',
            'prestamos.ingeniero',
            'prestamos.created_at',
            'prestamos.finished_at'
        );

    if (!empty($busqueda)) {
        $busquedaLike = "%{$busqueda}%";

        $query->where(function ($q) use ($busqueda, $busquedaLike) {

            if (is_numeric($busqueda)) {
                $q->where('prestamos.id', $busqueda)
                  ->orWhere('inventario.id', $busqueda);
            }

            $q->orWhereRaw('LOWER(inventario.nombre) LIKE LOWER(?)', [$busquedaLike])
              ->orWhereRaw('LOWER(imagenes.nombre) LIKE LOWER(?)', [$busquedaLike])
              ->orWhereRaw('LOWER(prestamos.nombre) LIKE LOWER(?)', [$busquedaLike])
              ->orWhereRaw('LOWER(prestamos.ubicacion) LIKE LOWER(?)', [$busquedaLike]);
        });
    }

    $registros = $query
        ->orderBy('prestamos.id', 'desc')
        ->get();

    $filename = "Reporte_Prestamos_" . now()->format('Ymd_His') . ".xls";

    $output = '<table border="1">
        <thead>
            <tr style="background-color:#343a40;color:white;">
                <th>ID</th>
                <th>ID Articulo</th>
                <th>Nombre</th>
                <th>Ubicacion</th>
                <th>Estatus del Prestamo</th>
                <th>Ingeniero</th>
                <th>Fecha de Prestamo</th>
                <th>Fecha de Recepcion</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($registros as $r) {

        $estatus = $r->estatus_prestamo == 1 ? 'Devuelto' :
                   ($r->estatus_prestamo == 0 ? 'Prestado' : 'No disponible');

        $output .= '<tr>
            <td>' . $r->id . '</td>
            <td>' . $r->id_articulo . '</td>
            <td>' . htmlspecialchars($r->nombre) . '</td>
            <td>' . htmlspecialchars($r->ubicacion) . '</td>
            <td>' . $estatus . '</td>
            <td>' . htmlspecialchars($r->ingeniero) . '</td>
            <td>' . $r->created_at . '</td>
            <td>' . $r->finished_at . '</td>
        </tr>';
    }

    $output .= '</tbody></table>';

    return response($output)
        ->header("Content-Type", "application/vnd.ms-excel")
        ->header("Content-Disposition", "attachment; filename={$filename}");
}
}