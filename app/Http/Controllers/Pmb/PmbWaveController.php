<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbWave;
use App\Models\PmbPeriod;
use App\Models\PmbWavePath;
use App\Models\PmbRegistrationPath;
use App\Models\Faculty;
use App\Models\PmbRegistrationStage;
use App\Models\PmbWavePathStage;
use Illuminate\Support\Facades\DB;

class PmbWaveController extends Controller
{
    public function index(Request &$request){
        $query = PmbWave::query();
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('pmb_period_id','DESC')->orderBy('code','DESC')->paginate(10)->appends(['search']);
        // dd($data);
        return view('pmb.pmb-wave.index',[
            'title'=> 'Data Gelombang Pendaftaran',
            'periods'=> PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
            'data'=> $data,
        ]);
    }

    public function create(){
        return view('pmb.pmb-wave.create',[
            'title'=> 'Tambah Gelombang Pendaftaran',
            'periods'=> PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
        ]);
    }

    public function store(\App\Http\Requests\PmbWaveRequest &$request){
        try {
            DB::beginTransaction();
            PmbWave::create([
                'pmb_period_id'=> $request->pmb_period_id,
                'code'=> $request->code,
                'name'=> $request->name,
                'registration_start'=> $request->registration_start,
                'registration_end'=> $request->registration_end,
                'is_active'=> $request->is_active ? true :false,
                'is_show'=> $request->is_show ? true :false,
            ]);
            DB::commit();
            return redirect()->route('pmb.wave.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(PmbWave &$pmb_wave){
        return view('pmb.pmb-wave.edit',[
            'title'=> 'Edit Gelombang Pendaftaran',
            'periods'=> PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
            'data'=> $pmb_wave,
        ]);
    }

    public function update(\App\Http\Requests\PmbWaveRequest &$request, PmbWave &$pmb_wave){
        try {
            DB::beginTransaction();
            $pmb_wave->update([
                'pmb_period_id'=> $request->pmb_period_id,
                'code'=> $request->code,
                'name'=> $request->name,
                'registration_start'=> $request->registration_start,
                'registration_end'=> $request->registration_end,
                'is_active'=> $request->is_active ? true :false,
                'is_show'=> $request->is_show ? true :false,
            ]);
            DB::commit();
            return redirect()->route('pmb.wave.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(PmbWave &$pmb_wave){
        try {
            DB::beginTransaction();
            $pmb_wave->delete();
            DB::commit();
            return redirect()->route('pmb.wave.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Request &$request, PmbWave &$pmb_wave){
        $query = PmbWavePath::query();
        $query->whereHas('PmbWave', function($q) use ($pmb_wave){
            $q->where('pmb_wave_id', $pmb_wave->id);
        });
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('pmb_registration_path_id','ASC')->paginate(10)->appends(['search']);
        // dd($data);
        return view('pmb.pmb-wave.show',[
            'title'=> 'Detail Gelombang Pendaftaran',
            'wave'=> $pmb_wave,
            'data'=> $data,
        ]);
    }

    public function wavePath_create(PmbWave &$pmb_wave){
        return view('pmb.pmb-wave.path.create',[
            'title'=> 'Tambah Jalur Gelombang Pendaftaran',
            'registration_paths'=> PmbRegistrationPath::orderBy('code','ASC')->pluck('name','id'),
            'wave'=> $pmb_wave,
            'registration_stages'=> PmbRegistrationStage::where('is_active',1)->get(),
            'faculty'=> Faculty::orderBy('code')->get(),
        ]);
    }

    public function wavePath_store(\App\Http\Requests\PmbWavePathRequest &$request){
        $wave = PmbWave::findOrFail($request->pmb_wave_id);
        try {
            DB::beginTransaction();
            $pmb_wave_path = PmbWavePath::create([
                'pmb_wave_id'=> $request->pmb_wave_id,
                'pmb_registration_path_id'=> $request->pmb_registration_path_id,
                'registration_fee'=> $request->registration_fee,
                'is_active'=> $request->is_active ? true : false,
            ]);
            $pmb_wave_path->studyPrograms()->sync($request->study_program_code ?? []);
            $pmb_wave_path->generateAllStages();
            DB::commit();
            return redirect()->route('pmb.wave.show', $wave)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function wavePath_edit(PmbWavePath &$pmb_wave_path){
        $pmb_wave = PmbWave::findOrFail($pmb_wave_path->pmb_wave_id);
        return view('pmb.pmb-wave.path.edit',[
            'title'=> 'Edit Jalur Gelombang Pendaftaran',
            'registration_paths'=> PmbRegistrationPath::orderBy('code','ASC')->pluck('name','id'),
            'wave'=> $pmb_wave,
            'faculty'=> Faculty::orderBy('code')->get(),
            'registration_stages'=> PmbRegistrationStage::where('is_active',1)->get(),
            'data'=> $pmb_wave_path,
        ]);
    }

    public function wavePath_update(\App\Http\Requests\PmbWavePathRequest &$request, PmbWavePath &$pmb_wave_path){
        // dd($request->all());
        $wave = PmbWave::findOrFail($pmb_wave_path->pmb_wave_id);
        try {
            DB::beginTransaction();
            $pmb_wave_path->update([
                'pmb_wave_id'=> $request->pmb_wave_id,
                'pmb_registration_path_id'=> $request->pmb_registration_path_id,
                'registration_fee'=> $request->registration_fee,
                'is_active'=> $request->is_active ? true : false,
            ]);
            $pmb_wave_path->studyPrograms()->sync($request->study_program_code ?? []);
            DB::commit();
            return redirect()->route('pmb.wave.show', $wave)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function wavePath_destroy(PmbWavePath &$pmb_wave_path){
        $wave = PmbWave::findOrFail($pmb_wave_path->pmb_wave_id);
        try {
            DB::beginTransaction();
            $pmb_wave_path->delete();
            DB::commit();
            return redirect()->route('pmb.wave.show', $wave)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function wavePath_show(PmbWavePath &$pmb_wave_path){
        $query = PmbWavePathStage::query();
        $query->whereHas('pmbWavePath', function($q) use ($pmb_wave_path){
            $q->where('pmb_wave_path_id', $pmb_wave_path->id);
        });
        $data = $query->orderBy('sequence', 'ASC')->get();
        $pmb_wave = PmbWave::findOrFail($pmb_wave_path->pmb_wave_id);
        $next_sequence = PmbWavePathStage::where(
            'pmb_wave_path_id',
            $pmb_wave_path->id
        )->max('sequence') + 1;
        return view('pmb.pmb-wave.path.show',[
            'title'=> 'Detail Jalur Gelombang Pendaftaran',
            'wave'=> $pmb_wave,
            'wave_path'=> $pmb_wave_path,
            'registration_stages'=> PmbRegistrationStage::where('is_active',1)->pluck('name','id'),
            'next_sequence'=> $next_sequence,
            'data'=> $data,
        ]);
    }

    public function wavePathStage_store(\App\Http\Requests\PmbWavePathStageRequest &$request, PmbWavePath &$pmb_wave_path){
        try {
            DB::beginTransaction();
            PmbWavePathStage::create([
                'pmb_wave_path_id'=> $request->pmb_wave_path_id,
                'pmb_registration_stage_id'=> $request->pmb_registration_stage_id,
                'sequence'=> $request->sequence,
                'is_required'=> $request->is_required ? true : false,
                'execution_type'=> $request->execution_type,
                'start_at'=> $request->start_at,
                'end_at'=> $request->end_at,
                'is_active'=> $request->is_active ? true : false,
            ]);
            DB::commit();
            return redirect()->route('pmb.wave.path.show', $pmb_wave_path)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function wavePathStage_update(\App\Http\Requests\PmbWavePathStageRequest &$request, PmbWavePathStage &$pmb_wave_path_stage){
        $pmb_wave_path = PmbWavePath::findOrFail($pmb_wave_path_stage->pmb_wave_path_id);
        try {
            DB::beginTransaction();
            $pmb_wave_path_stage->update([
                'pmb_wave_path_id'=> $request->pmb_wave_path_id,
                'pmb_registration_stage_id'=> $request->pmb_registration_stage_id,
                'sequence'=> $request->sequence,
                'is_required'=> $request->is_required ? true : false,
                'execution_type'=> $request->execution_type,
                'start_at'=> $request->start_at,
                'end_at'=> $request->end_at,
                'is_active'=> $request->is_active ? true : false,
            ]);
            DB::commit();
            return redirect()->route('pmb.wave.path.show', $pmb_wave_path)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function wavePathStage_destroy(PmbWavePathStage &$pmb_wave_path_stage){
        $pmb_wave_path = PmbWavePath::findOrFail($pmb_wave_path_stage->pmb_wave_path_id);
        try {
            DB::beginTransaction();
            $pmb_wave_path_stage->delete();
            DB::commit();
            return redirect()->route('pmb.wave.path.show', $pmb_wave_path)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
