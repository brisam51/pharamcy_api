<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePharamcyRequest;
use App\Http\Requests\UpdatePharamcyRequest;
use App\Http\Resources\Pharamcy\PharamcyCollection;
use App\Http\Resources\PharamcyReceorce;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Pharamcy;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;

class PharamcyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
         * Fetch all pharmacies with optional filtering and sorting
         * @return \Illuminate\Http\JsonResponse
         * http://127.0.0.1:8000/api/pharamcy?filter[status]=inactive how to filter
         * http://127.0.0.1:8000/api/pharamcy?filter[email]=b it will give email that contain 
         * http://127.0.0.1:8000/api/pharamcy?sort=status how to sort
         */
        try {
            $pharamcy =  QueryBuilder::for(Pharamcy::class)
                ->defaultSort('name')
                ->allowedFilters(['name', 'email', 'address', 'status','subscription_start_date','subscription_end_date'])
                ->allowedSorts(['name', 'email', 'address', 'status'])
                ->paginate();


            if ($pharamcy->isEmpty()) {
                return response()->json([
                    'message' => 'No pharmacies found'
                ], 404);
            }
            return response()->json([
                "message" => "Pharmacies fetched successfully",
                'count' => $pharamcy->count(),
                'status' => 200,
                'data' => new PharamcyCollection($pharamcy)



            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePharamcyRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $filename);
                $validated['logo'] = 'images/' . $filename;
            }
            $pharamcy = Pharamcy::create($validated);
            return response()->json([
                'message' => 'Pharmacy created successfully',
                'status' => 201,
                'data' => $pharamcy,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'message' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $pharamcy = new PharamcyReceorce(Pharamcy::findOrFail($id));
            return response()->json([
                "message" => "Pharmacy fetched successfully",
                'status' => 200,
                'data' => $pharamcy,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pharmacy not found',

            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                "name" => "sometimes|required|string|max:255",
                "national_id" => "sometimes|required",
                "email" => "sometimes|required|email|unique:pharamcies,email," . $id . "|max:255",
                "address" => "sometimes|required|string|max:255",
                "logo" => "sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
                "subscription_start_date" => "sometimes|required|date",
                "subscription_end_date" => "sometimes|required|date|after:subscription_start_date",
                "status" => "sometimes|required|in:active,inactive",
                "user_id" => "sometimes|required|exists:users,id",

            ]);
            $pharamcy = Pharamcy::find($id);
            if ($pharamcy) {
                if ($request->hasFile('logo')) {
                    $file = $request->file('logo');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images'), $filename);
                    $validated['logo'] = 'images/' . $filename;
                    if (!empty($pharamcy->logo)) {
                        $oldPhotoPath = public_path($pharamcy->logo);
                        if (file_exists($oldPhotoPath)) {
                            unlink($oldPhotoPath);
                        }
                    }
                }
                $pharamcy->update($request->all());
                return response()->json([
                    'message' => 'Pharmacy updated successfully',
                    'status' => 200,
                    'data' => null,
                ]);
            } else {
                return response()->json([
                    'message' => 'Pharmacy not found',
                    'status' => 404,
                    'data' => null,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update pharmacy',
                'status' => 500,
                'data' => null,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pharamcy $pharamcy)
    {
        try {
            if ($pharamcy) {
                if (!empty($pharamcy->logo)) {
                    $oldPhotoPath = public_path('images/' . $pharamcy->logo);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $pharamcy->delete();
                return response()->json([
                    'message' => 'Pharmacy deleted successfully',
                    'status' => 200,
                    'data' => null,
                ]);
            } else if ($pharamcy->is_empty()) {
                return response()->json([
                    "message" => "Pharmacy Not Found",
                    "data" => null,
                    "status" => 404
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete pharmacy',
                'status' => 500,
                'data' => null,
            ]);
        }
    }
}
