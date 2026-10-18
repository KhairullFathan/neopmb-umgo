<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\DB;

class StudyProgramController extends Controller
{
    public function index(Request &$request){
        $query = StudyProgram::query();
        if($request->has('faculty') && $request->faculty != ''){
            $searchFaculty = $request->faculty;
            $query->where('faculty_code',$searchFaculty);
        }
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('code','asc')->paginate(10)->appends(['search']);
        // dd($data);
        return view('masterdata.study-program.index',[
            'title'=> 'Data Program Studi',
            'faculty'=> Faculty::all(),
            'data'=> $data,
        ]);
    }

    public function create(){
        return view('masterdata.study-program.create',[
            'title'=> 'Tambah Program Studi',
            'faculty'=> Faculty::all(),
        ]);
    }

    public function store(\App\Http\Requests\StudyProgramRequest &$request){
        try {
            DB::beginTransaction();
            StudyProgram::create([
                'code'=> $request->code,
                'faculty_code'=> $request->faculty_code,
                'name'=> $request->name,
                'degree_level'=> $request->degree_level,
                'accreditation'=> $request->accreditation,
                'degree_title'=> $request->degree_title,
                'description'=> $request->description,
            ]);
            DB::commit();
            return redirect()->route('masterdata.study_program.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(StudyProgram &$study_program){
        return view('masterdata.study-program.edit',[
            'title'=> 'Edit Program Studi: '.$study_program->code,
            'faculty'=> Faculty::all(),
            'data'=> $study_program,
        ]);
    }

    public function update(\App\Http\Requests\StudyProgramRequest &$request, StudyProgram &$study_program){
        try {
            DB::beginTransaction();
            $study_program->update([
                'code'=> $request->code,
                'faculty_code'=> $request->faculty_code,
                'name'=> $request->name,
                'degree_level'=> $request->degree_level,
                'accreditation'=> $request->accreditation,
                'degree_title'=> $request->degree_title,
                'description'=> $request->description,
            ]);
            DB::commit();
            return redirect()->route('masterdata.study_program.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(StudyProgram &$study_program){
        try {
            DB::beginTransaction();
            $study_program->delete();
            DB::commit();
            return redirect()->route('masterdata.study_program.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
