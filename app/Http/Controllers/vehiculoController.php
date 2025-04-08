<?php
namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiculoController extends Controller
{
    public function index()
    {
        return Vehiculo::with(['estado', 'categoria'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca' => 'required|string|min:2|max:50',
            'modelo' => 'required|string|min:2|max:50',
            'anio' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'placa' => 'required|string|min:6|max:10|unique:vehiculos,placa',
            'id_estado' => 'required|integer|exists:estados,id_estado',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar la foto
        ]);

        // Manejar la subida de la foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('vehiculos', 'public'); // Guardar en storage/app/public/vehiculos
        }

        // Crear el vehículo con la ruta de la foto
        $vehiculo = Vehiculo::create(array_merge(
            $request->except('foto'),
            ['foto' => $fotoPath]
        ));

        return response()->json($vehiculo, 201);
    }

    public function show($id)
    {
        return Vehiculo::with(['estado', 'categoria'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marca' => 'sometimes|string|min:2|max:50',
            'modelo' => 'sometimes|string|min:2|max:50',
            'anio' => 'sometimes|integer|digits:4|min:1900|max:' . date('Y'),
            'placa' => 'sometimes|string|min:6|max:10|unique:vehiculos,placa,' . $id . ',id_vehiculo',
            'id_estado' => 'sometimes|integer|exists:estados,id_estado',
            'id_categoria' => 'sometimes|integer|exists:categorias,id_categoria',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar la foto
        ]);

        $vehiculo = Vehiculo::findOrFail($id);

        // Manejar la subida de la nueva foto (si se proporciona)
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($vehiculo->foto) {
                Storage::disk('public')->delete($vehiculo->foto);
            }

            // Subir la nueva foto
            $vehiculo->foto = $request->file('foto')->store('vehiculos', 'public');
        }

        // Actualizar los demás campos
        $vehiculo->update($request->except('foto'));

        return response()->json($vehiculo, 200);
    }

    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        // Eliminar la foto asociada si existe
        if ($vehiculo->foto) {
            Storage::disk('public')->delete($vehiculo->foto);
        }

        // Eliminar el vehículo
        $vehiculo->delete();

        return response()->json(['message' => 'Vehículo eliminado'], 200);
    }

    public function vehiculosSinReserva()
    {
        $vehiculosReservados = Reservacion::pluck('id_vehiculo'); // Obtener todos los vehículos con reservas
        $vehiculosSinReserva = Vehiculo::whereNotIn('id_vehiculo', $vehiculosReservados)->get();

        return response()->json($vehiculosSinReserva);
    }

    public function vehiculosConReserva()
    {
        $vehiculosReservados = Vehiculo::whereHas('reservaciones')->with('reservaciones')->get();

        return response()->json($vehiculosReservados);
    }

    public function vehiculosPorCategoria($id_categoria)
    {
        // Obtener los vehículos que pertenecen a la categoría especificada
        $vehiculos = Vehiculo::where('id_categoria', $id_categoria)
            ->with(['estado', 'categoria'])
            ->get();

        if ($vehiculos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron vehículos para esta categoría'], 404);
        }

        return response()->json($vehiculos);
    }
}