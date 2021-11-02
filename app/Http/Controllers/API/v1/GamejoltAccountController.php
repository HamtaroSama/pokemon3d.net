<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Models\GameJoltAccount;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\v1\GamejoltAccountResource;

class GamejoltAccountController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:api']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $gja = GameJoltAccount::with(['user.roles.permissions', 'bans'])->where('id', $id)->firstOrFail();
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'error' => 'Token does not have access!',
            ]);
        }
        return new GamejoltAccountResource($gja);
    }
}