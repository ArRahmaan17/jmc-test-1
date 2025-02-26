<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $regencies = Regency::select('regencies.*', 'p.name as province')->join('provinces as p', 'p.id', '=', 'regencies.provinceId');
        if ($request->filter) {
            $regencies->where('p.name', 'like', "%" . trim($request->filter) . "%")->orWhere('regencies.name', 'like', "%" . trim($request->filter) . "%");
        }
        $regencies = $regencies->orderBy('p.id')->get()->toArray();
        $filter_report = implode(',', array_unique(array_map(function ($provinceRegency) {
            return $provinceRegency['province'];
        }, $regencies)));
        $provinces = Province::all();
        return view('regency', compact('regencies', 'provinces', 'filter_report'));
    }

    public function populationReport(Request $request)
    {
        $data = DB::table('provinces as p')->select('r.name', 'p.name as province', DB::raw('SUM(r.population) as population'))->join('regencies as r', 'p.id', '=', 'r.provinceId')->groupBy('r.id', 'r.name', 'p.name');
        if ($request->filter != "") {
            $data->where('p.name', 'like', "%" . trim($request->filter) . "%");
        }
        $data = $data->orderBy('p.id')->get()->toArray();
        $filter_report = implode(',', array_unique(array_map(function ($provinceRegency) {
            return $provinceRegency->province;
        }, $data)));
        $pdf = Pdf::loadView('regency-population-report', compact('data', 'filter_report'));
        return $pdf->download('regency-population-report-' . now('Asia/Jakarta')->format('Y-m-d@h:i:s') . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:regencies,name',
            'population' => 'required|numeric',
            'provinceId' => 'required|numeric|exists:provinces,id'
        ]);
        DB::beginTransaction();
        try {
            Regency::create($request->except('_token'));
            DB::commit();
            return response()->json(['message' => 'successfully creating regency resource'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'failed creating regency resource'], 524);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $regency = Regency::find($id);
        if ($regency) {
            return response()->json(['message' => 'successfully showing regency resource', 'data' => $regency], 200);
        } else {
            return response()->json(['message' => 'failed showing regency resource', 'data' => $regency], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:regencies,name,' . $id,
            'population' => 'required|numeric',
            'provinceId' => 'required|numeric|exists:provinces,id'
        ]);
        DB::beginTransaction();
        try {
            Regency::find($id)->update($request->except('_token'));
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
        DB::beginTransaction();
        try {
            Regency::destroy($id);
            DB::commit();
            return response()->json(['message' => 'successfully deleting regency resource'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'failed deleting regency resource'], 524);
        }
    }
}
