<?php

namespace App\Http\Controllers;

use App\Models\offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function attendaceIn(Request $request)
    {
        //radius
        //Alternate
        // $office_id = $request->office_id;
        // $office = offices::where('office_status', 1)->where('id', $office_id)->first();
        $office = offices::where('office_status', 1)->where('id', $request->office_id)->first();
        $lat_from_employee = $request->lat_from_employee;
        $long_from_employee = $request->long_from_employee;
        $lat_from_office = $office->office_lat;
        $long_from_office = $office->office_long;
        $radius = $this->getDistanceBetweenPoints($lat_from_employee, $long_from_employee, $lat_from_office, $long_from_office);
        $meter = round($radius['meters']);
        if ($meter > 1000) {
            return response()->json([
                'message' => 'You are too far from the office',
            ], 422);
        }
        if ($meter < 1000) {
            return response()->json([
                'message' => 'You are in the office',
            ], 200);
        }
        // $meter = $meter / 1000;
        // return $meter;

    }
    protected function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }
    public function index()
    {
        try {
            $attendances = Attendance::orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $attendances,
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
            $attendances = Attendance::create([
                //FIll me!
            ]);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $attendances], 201);
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
            $attendaces = Attendance::with('user')->findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Showing Data successfully', 'data' => $attendaces], 201);
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
                //Fill me!
            ];
            $attendances = Attendance::findOrFail($id);
            $attendances->update($data);
            return response()->json(['success' => true, 'message' => 'Employees added successfully', 'data' => $attendances], 201);
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
            $attendances = Attendance::findOrFail($id)->delete();
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
