<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class ApplicantController extends Controller
{
    public function index(){
        return view('home.my.applicant.index',[
            'title'=> 'Data Saya',
            'data'=> auth()->user()->applicant,
            'provinces'=> Province::all(),
        ]);
    }

    public function update(\App\Http\Requests\ApplicantRequest &$request, Applicant &$applicant){
        try {
            DB::beginTransaction();
            $user = User::findOrFail($applicant->user_id);
            $user->update([
                'email'=> $request->email,
                'phone'=> $request->phone,
            ]);
            $applicant->update($request->except(['_method','_token','email']));
            DB::commit();
            return redirect()->route('my.applicant.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
