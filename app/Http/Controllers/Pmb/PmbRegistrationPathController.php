<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PmbRegistrationPath;
use App\Models\PmbRegistrationDocument;
use Illuminate\Support\Facades\DB;

class PmbRegistrationPathController extends Controller
{
    public function index(Request &$request){
        $query = PmbRegistrationPath::query();
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('code','ASC')->paginate(10)->appends(['search']);
        // dd($data);
        return view('pmb.pmb-registration-path.index',[
            'title'=> 'Data Jalur Pendaftaran PMB',
            'data'=> $data,
        ]);
    }

    public function store(\App\Http\Requests\PmbRegistrationPathRequest &$request){
        try {
            DB::beginTransaction();
            PmbRegistrationPath::create([
                'code'=> $request->code,
                'name'=> $request->name,
                'description'=> $request->description,
            ]);
            DB::commit();
            return redirect()->route('pmb.registration_path.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(\App\Http\Requests\PmbRegistrationPathRequest &$request, PmbRegistrationPath &$pmb_registration_path){
        try {
            DB::beginTransaction();
            $pmb_registration_path->update([
                'code'=> $request->code,
                'name'=> $request->name,
                'description'=> $request->description,
            ]);
            DB::commit();
            return redirect()->route('pmb.registration_path.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(PmbRegistrationPath &$pmb_registration_path){
        try {
            DB::beginTransaction();
            $pmb_registration_path->delete();
            DB::commit();
            return redirect()->route('pmb.registration_path.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(PmbRegistrationPath &$pmb_registration_path){
        return view('pmb.pmb-registration-path.show', [
            'title'=> 'Detail Jalur Pendaftaran PMB',
            'data'=> $pmb_registration_path,
        ]);
    }

    public function registrationDocument_store(\App\Http\Requests\PmbRegistrationDocumentRequest &$request){
        $path = PmbRegistrationPath::findOrFail($request->pmb_registration_path_id);
        $allowed_mimes = PmbRegistrationDocument::generateAllowedMimes($request->allowed_mimes);
        try {
            DB::beginTransaction();
            $pmb_registration_document = PmbRegistrationDocument::create([
                'pmb_registration_path_id'=> $request->pmb_registration_path_id,
                'name'=> $request->name,
                'description'=> $request->description,
                'is_required'=> $request->is_required ? true : false,
                'allowed_mimes'=> $allowed_mimes,
                'is_active'=> $request->is_active ? true : false,
            ]);
            DB::commit();
            return redirect()->route('pmb.registration_path.show', $path)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function registrationDocument_update(\App\Http\Requests\PmbRegistrationDocumentRequest &$request, PmbRegistrationDocument &$pmb_registration_document){
        $path = PmbRegistrationPath::findOrFail($request->pmb_registration_path_id);
        $allowed_mimes = PmbRegistrationDocument::generateAllowedMimes($request->allowed_mimes);
        try {
            DB::beginTransaction();
            $pmb_registration_document->update([
                'pmb_registration_path_id'=> $request->pmb_registration_path_id,
                'name'=> $request->name,
                'description'=> $request->description,
                'is_required'=> $request->is_required ? true : false,
                'allowed_mimes'=> $allowed_mimes,
                'is_active'=> $request->is_active ? true : false,
            ]);
            DB::commit();
            return redirect()->route('pmb.registration_path.show', $path)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
