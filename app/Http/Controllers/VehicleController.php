<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        // Filtering, sorting, pagination
        $query = Vehicle::query();

        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%$search%")
                  ->orWhere('model', 'like', "%$search%")
                  ->orWhere('vehicleTitle', 'like', "%$search%")
                  ->orWhere('registrationNumber', 'like', "%$search%");
            });
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->bodyType) {
            $query->where('bodyType', $request->bodyType);
        }

        if ($request->fuelType) {
            $query->where('fuelType', $request->fuelType);
        }

        if ($request->transmission) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->stateOfRegistration) {
            $query->where('stateOfRegistration', $request->stateOfRegistration);
        }

        if ($request->minPrice) {
            $query->where('defaultPrice', '>=', $request->minPrice);
        }

        if ($request->maxPrice) {
            $query->where('defaultPrice', '<=', $request->maxPrice);
        }

        if ($request->minYear) {
            $query->where('year', '>=', $request->minYear);
        }

        if ($request->maxYear) {
            $query->where('year', '<=', $request->maxYear);
        }

        if ($request->sortBy) {
            $query->orderBy($request->sortBy, $request->sortOrder ?? 'asc');
        }

        $limit = min($request->input('limit', 20), 100);

        $vehicles = $query->paginate($limit);

        $statusCounts = [
            'all' => Vehicle::count(),
            'active' => Vehicle::where('status', 'active')->count(),
            'inactive' => Vehicle::where('status', 'inactive')->count(),
            'draft' => Vehicle::where('status', 'draft')->count(),
            'under_maintenance' => Vehicle::where('status', 'under_maintenance')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'vehicles' => $vehicles->items(),
                'pagination' => [
                    'currentPage' => $vehicles->currentPage(),
                    'totalPages' => $vehicles->lastPage(),
                    'totalItems' => $vehicles->total(),
                    'itemsPerPage' => $vehicles->perPage(),
                    'hasNext' => $vehicles->hasMorePages(),
                    'hasPrev' => $vehicles->currentPage() > 1,
                ],
                'filters' => [
                    'statusCounts' => $statusCounts
                ]
            ]
        ]);
    }

    public function show($id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VEHICLE_NOT_FOUND',
                    'message' => "Vehicle with ID $id not found",
                    'details' => []
                ]
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $vehicle]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicleTitle' => 'required|string',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'stateOfRegistration' => 'required|string',
            'transmission' => 'required|in:Manual,Auto',
            'fuelType' => 'required|in:Petrol,Diesel,Hybrid,Electric',
            'odometer' => 'required|integer',
            'registrationNumber' => 'required|string|unique:vehicles',
            'registrationExpiry' => 'required|date',
            'ctpExpiry' => 'required|date',
            'vin' => 'nullable|string',
            'bodyType' => 'nullable|string',
            'driveType' => 'nullable|in:2WD,4WD,AWD',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'color' => 'nullable|string',
            'securityBond' => 'nullable|integer',
            'bookingFrequency' => 'required|in:Daily,Weekly,Monthly',
            'defaultPrice' => 'required|numeric',
            'cancellationPolicy' => 'required|in:Flexible,Moderate,Strict',
            'deliveryOptions' => 'required|array',
            'extras' => 'nullable|array',
            'terms' => 'nullable|string',
            'pickupLocation' => 'required|string',
            'insuranceIncluded' => 'required|boolean',
            'insurancePrice' => 'nullable|numeric',
            'insuranceFrequency' => 'nullable|in:Daily,Per Booking,Weekly',
            'photos' => 'nullable|array',
            'videoUpload' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft,under_maintenance',
            'thumbnail' => 'nullable|string',
            'type' => 'required|string',
            'price' => 'required|string',
            'location' => 'required|string'
        ]);

        $vehicle = Vehicle::create($data);

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehicle created successfully'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VEHICLE_NOT_FOUND',
                    'message' => "Vehicle with ID $id not found",
                    'details' => []
                ]
            ], 404);
        }

        $data = $request->all();
        $vehicle->update($data);

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehicle updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VEHICLE_NOT_FOUND',
                    'message' => "Vehicle with ID $id not found",
                    'details' => []
                ]
            ], 404);
        }

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ]);
    }
}
