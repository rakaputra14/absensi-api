<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Employees;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = Employees::orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $employees,
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
            'user_id' => 'required',
            'phone' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 422);
        }
        try {
            $employees = Employees::create([
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'status' => $request->is_active,
            ]);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $employees], 201);
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
            $employees = Employees::with('user')->findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Showing Data successfully', 'data' => $employees], 201);
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
            'user_id' => 'required',
            'phone' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 422);
        }
        try {
            $data = [
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
                'status' => $request->is_active,
            ];
            $employees = Employees::findOrFail($id);
            $employees->update($data);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $employees], 201);
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
        //
    }
}
