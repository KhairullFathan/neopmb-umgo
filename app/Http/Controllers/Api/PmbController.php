<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class PmbController extends Controller
{
    public function waves(){

    }

    public function wavePaths(){

    }

    public function provinces()
    {
        return response()->json(
            Province::select('code','name')
                ->orderBy('name','ASC')
                ->get()
        );
    }

    public function regencies(Request $request)
    {
        $request->validate([
            'code_province' => ['required', 'exists:provinces,code'],
        ]);

        return response()->json(
            Regency::where('code_province', $request->code_province)
                ->select('code', 'name')
                ->orderBy('name','ASC')
                ->get()
        );
    }

    public function districts(Request $request)
    {
        $request->validate([
            'code_regency' => ['required', 'exists:regencies,code'],
        ]);

        return response()->json(
            District::where('code_regency', $request->code_regency)
                ->select('code', 'name')
                ->orderBy('name','ASC')
                ->get()
        );
    }

    public function villages(Request $request)
    {
        $request->validate([
            'code_district' => ['required', 'exists:districts,code'],
        ]);

        return response()->json(
            Village::where('code_district', $request->code_district)
                ->select('code', 'name')
                ->orderBy('name','ASC')
                ->get()
        );
    }

    public function village_detail(Request &$request){
        $validated = $request->validate([
            'code_village' => [
                'required',
                'exists:villages,code',
            ],
        ]);

        $village = Village::query()
            ->join(
                'districts',
                'villages.code_district',
                '=',
                'districts.code'
            )
            ->join(
                'regencies',
                'districts.code_regency',
                '=',
                'regencies.code'
            )
            ->join(
                'provinces',
                'regencies.code_province',
                '=',
                'provinces.code'
            )
            ->where('villages.code', $request->code_village)
            ->select([
                'provinces.code as code_province',
                'provinces.name as province_name',

                'regencies.code as code_regency',
                'regencies.name as regency_name',

                'districts.code as code_district',
                'districts.name as district_name',

                'villages.code as code_village',
                'villages.name as village_name',
            ])
            ->first();
        if (!$village) {
            return response()->json([
                'message' => 'Data desa tidak ditemukan.',
            ], 404);
        }
        return response()->json($village);
    }
}
