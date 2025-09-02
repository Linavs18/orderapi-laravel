<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

     public function applyValidator( Request $request, $rules, $traductionAttributes){ 
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($traductionAttributes);

        $data = [];
        if ($validator->fails()) {
                $data = response()->json([
                    'errors' => $validator->errors(),
                    'data' => $request->all()
                ], Response::HTTP_BAD_REQUEST);
        }
     }

}
