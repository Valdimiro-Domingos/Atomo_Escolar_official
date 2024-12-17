<?php

namespace App\Http\Controllers\secretary;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransportResource;
use App\Models\Company;
use App\Models\Transport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transports = Transport::get();
            return response(
                ['transports'=> TransportResource::collection($transports)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['transports'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        

        try {
            
            $transport = Transport::create([
                'description'=> $request->description,
                'designation'=> $request->designation,
                'company_id'=> Auth::user()->company->id,
            ]);
            $transports = Transport::get();
            return response(
                ['transports'=> TransportResource::collection($transports)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['transports'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transports = Transport::findOrfail(intval($id));
            return response(
                ['transports'=> TransportResource::make($transports)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['transports'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { 
        try {
            $transport = Transport::findOrFail($id);
            $transport->update([
                'description'=> $request->description,
                'designation'=> $request->designation,
            ]);
            return response(
                ['transports'=> TransportResource::collection(Transport::get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['transports'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $transport = Transport::findOrFail($id);
            $transport->delete();
            return response(
                ['transports'=> TransportResource::collection(Transport::get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['transports'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }
}
