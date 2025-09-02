<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TechnicianController extends Controller
{

    private $rules = [
        'name' => 'required|string|min:3|max:80',
        'speciality' => 'max:50',
        'phone' => 'max:30'
    ];

    private $traductionAttributes = [
        'document' => 'documento',
        'name' => 'nombre',
        'speciality' => 'especialidad',
        'phone' => 'teléfono'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $technicians = Technician::all();
        return response()->json($technicians, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if (!empty($data)) {
            return $data;
        }

        $technician = Technician::create($request->all());
        $response = [
            'message' => 'Tecnico creado exitosamente',
            'technician' => $technician
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
         return response()->json($technician, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Technician $technician)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if (!empty($data)) {
            return $data;
        }

        $technician->update($request->all());
        $response = [
            'message' => 'Tecnico actualizado exitosamente',
            'technician' => $technician
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();
        $response = [
            'message' => 'Tecnico eliminado exitosamente',
            'technician' => $technician
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
