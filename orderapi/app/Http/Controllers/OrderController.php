<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
     private $rules = [
        'legalization_date' => 'required|date|date_format:Y-m-d',
        'address' => 'required|string|min:3|max:50',
        'city' => 'required|string|min:3|max:80',
        'causal_id' => 'required|numeric|min:1|max:99999999999999999999',
        'observation_id' => 'max:99999999999999999999'
    ];

    private $traductionAttributes = [
        'legalization_date' => 'fecha de legalización',
        'address' => 'dirección',
        'city' => 'ciudad',
        'causal_id' => 'causal id',
        'observation_id' => 'observación id'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        $orders->load(['causal', 'observation']);
        return response()->json($orders, Response::HTTP_OK);
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

        $order = Order::create($request->all());
        $response = [
            'message' => 'Orden creada exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        $order->load(['causal', 'observation']);
        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if (!empty($data)) {
            return $data;
        }

        $order->update($request->all());
        $response = [
            'message' => 'Orden actualizada exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order->delete();
        $response = [
            'message' => 'Orden eliminada exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_OK);
    }

     public function add_activity(string $order_id, string $activity_id)
    {
        $order = Order::find($order_id);
        if(!$order)
        {
            $response = [
                'errors' => 'No se encuentra la orden',
                'data' => [$order_id, $activity_id]
            ];
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        $activity = Activity::find($activity_id);
        if(!$activity)
        {
           $response = [
                'errors' => 'No se encuentra la activiad',
                'data' => [$order_id, $activity_id]
            ];
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        //guardar la actividad en order_activity
        $order->activities()->attach($activity_id);
        $response = [
            'message' => 'Actividad agregada exitosamente',
            'order_activity' => $order->activities
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function remove_activity(string $order_id, string $activity_id)
    {
        $order = Order::find($order_id);
        if(!$order)
        {
            $response = [
                'errors' => 'No se encuentra la orden',
                'data' => [$order_id, $activity_id]
            ];
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        $activity = Activity::find($activity_id);
        if(!$activity)
        {
           $response = [
                'errors' => 'No se encuentra la activiad',
                'data' => [$order_id, $activity_id]
            ];
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        //eliminar la actividad en order_activity
        $order->activities()->detach($activity_id);
        $response = [
            'message' => 'Actividad eliminada exitosamente',
            'order_activity' => $order->activities
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
