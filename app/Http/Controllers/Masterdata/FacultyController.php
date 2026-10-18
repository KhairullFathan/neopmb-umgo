<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    public function index(Request &$request){
        $query = Faculty::query();
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('code','asc')->paginate(10)->appends(['search']);
        // dd($data);
        return view('masterdata.faculty.index',[
            'title'=> 'Data Fakultas',
            'data'=> $data,
        ]);
    }

    public function create(){
        return view('masterdata.faculty.create',[
            'title'=> 'Tambah Fakultas',
        ]);
    }

    public function store(\App\Http\Requests\FacultyRequest &$request){
        try {
            DB::beginTransaction();
            Faculty::create([
                'code'=> $request->code,
                'name'=> $request->name,
            ]);
            DB::commit();
            return redirect()->route('masterdata.faculty.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Faculty &$faculty){
        return view('masterdata.faculty.edit',[
            'title'=> 'Edit Fakultas: '.$faculty->code,
            'data'=> $faculty
        ]);
    }

    public function update(\App\Http\Requests\FacultyRequest &$request, Faculty &$faculty){
        try {
            DB::beginTransaction();
            $faculty->update([
                'code'=> $request->code,
                'name'=> $request->name,
            ]);
            DB::commit();
            return redirect()->route('masterdata.faculty.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Faculty &$faculty){
        try {
            DB::beginTransaction();
            $faculty->delete();
            DB::commit();
            return redirect()->route('masterdata.faculty.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
