<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\offices;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $offices = Offices::orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $offices,
            ]);
        } catch (\Throwable $th) {
            log::error('failed to get employees' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'office_name' => 'required',
            'office_lat' => 'required',
            'office_long' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 422);
        }
        try {
            $offices = Offices::create([
                'office_name' => $request->office_name,
                'office_phone' => $request->office_phone,
                'office_address' => $request->office_address,
                'office_lat' => $request->office_lat,
                'office_long' => $request->office_long,
                'office_status' => $request->office_status,
            ]);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $offices], 201);
        } catch (\Throwable $th) {
            log::error('failed to insert : ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $offices = offices::with('user')->findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Showing Data successfully', 'data' => $offices], 201);
        } catch (\Throwable $th) {
            log::error('failed to show : ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'office_name' => 'required',
            'office_lat' => 'required',
            'office_long' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 422);
        }
        try {
            $data = [
                'office_name' => $request->office_name,
                'office_phone' => $request->office_phone,
                'office_address' => $request->office_address,
                'office_lat' => $request->office_lat,
                'office_long' => $request->office_long,
                'office_status' => $request->is_active,
            ];
            $offices = offices::findOrFail($id);
            $offices->update($data);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $offices], 201);
        } catch (\Throwable $th) {
            log::error('failed to insert : ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employees = offices::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Employees deleted successfully'], 201);
        } catch (\Throwable $th) {
            log::error('failed to delete : ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
