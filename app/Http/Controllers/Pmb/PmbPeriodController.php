<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbPeriod;
use Illuminate\Support\Facades\DB;

class PmbPeriodController extends Controller
{
    public function index(Request &$request){
        $query = PmbPeriod::query();
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('id','desc')->paginate(10)->appends(['search']);
        // dd($data);
        return view('pmb.pmb-period.index',[
            'title'=> 'Data Periode PMB',
            'data'=> $data,
        ]);
    }

    public function store(\App\Http\Requests\PmbPeriodRequest &$request){
        try {
            DB::beginTransaction();
            PmbPeriod::create([
                'id'=> $request->id,
                'name'=> $request->name,
                'is_active'=> $request->is_active ? true : false,
            ]);
            DB::commit();
            return redirect()->route('pmb.period.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(\App\Http\Requests\PmbPeriodRequest &$request, PmbPeriod &$pmb_period){
        try {
            DB::beginTransaction();
            $pmb_period->update([
                'id'=> $request->id,
                'name'=> $request->name,
                'is_active'=> $request->is_active ? true : false,
            ]);
            DB::commit();
            return redirect()->route('pmb.period.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(PmbPeriod &$pmb_period){
        try {
            DB::beginTransaction();
            $pmb_period->delete();
            DB::commit();
            return redirect()->route('pmb.period.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
