<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::all();
        return view('province', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:provinces,name'
        ]);
        DB::beginTransaction();
        try {
            Province::create($request->only('name'));
            DB::commit();
            return response()->json(['message' => 'successfully creating province resource'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'failed creating province resource'], 524);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $province = Province::find($id);
        if ($province) {
            return response()->json(['message' => 'successfully showing province resource', 'data' => $province], 200);
        } else {
            return response()->json(['message' => 'failed showing province resource', 'data' => $province], 404);
        }
    }
    public function populationReport()
    {
        $data = DB::table('provinces as p')->select('p.name', DB::raw('SUM(r.population) as population'))->join('regencies as r', 'p.id', '=', 'r.provinceId')->groupBy('p.id', 'p.name')->get()->toArray();
        $pdf = Pdf::loadView('province-population-report', compact('data'));
        return $pdf->download('province-population-report-' . now('Asia/Jakarta')->format('Y-m-d@h:i:s') . '.pdf');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:provinces,name,' . $id
        ]);
        DB::beginTransaction();
        try {
            Province::find($id)->update($request->only('name'));
            DB::commit();
            return response()->json(['message' => 'successfully updating province resource'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'failed updating province resource'], 524);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $countRegency = Regency::where('provinceId', $id)->count();
        if ($countRegency == 0) {
            DB::beginTransaction();
            Province::destroy($id);
            DB::commit();
            return response()->json(['message' => 'successfully deleting province resource'], 200);
        } else {
            return response()->json(['message' => 'failed deleting province resource, the resource is still used in another table'], 524);
        }
    }
}
