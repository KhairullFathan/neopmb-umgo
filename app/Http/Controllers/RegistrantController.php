<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RegistrantController extends Controller
{
    public function index(Request &$request){
        $query = User::query();
        $query->with(['roles'])
            ->whereHas('roles', function($q){
                $q->whereIn('name', [
                    'camaba'
                ]);
            });
        if($request->has('role') && $request->role != ''){
            $searchRole = $request->role;
            $query->whereHas('roles', function($q) use ($searchRole){
                $q->whereIn('roles.id',[$searchRole]);
            });
        }
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $data = $query->orderBy('username','asc')->paginate(10)->appends(['search']);
        // dd($data);
        return view('user.index',[
            'title'=> 'Data Pengguna',
            'roles'=> Role::whereIn('name',['camaba'])->get(),
            'data'=> $data,
        ]);
    }

    public function create(){
        return view('user.create',[
            'title'=> 'Tambah Pengguna',
            'roles'=> Role::whereNotIn('name',['camaba'])->get(),
            'faculty'=> Faculty::orderBy('code')->get(),
        ]);
    }

    public function store(\App\Http\Requests\UserRequest &$request){
        try {
            DB::beginTransaction();
            $user = User::create([
                'username'=> $request->username,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'password'=> Hash::make($request->password),
                'is_active'=> true,
            ]);
            $user->assignRole($request->role);
            $user->studyPrograms()->sync($request->study_program_code ?? []);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(User &$user){
        return view('user.edit',[
            'title'=> 'Edit Pengguna: '.$user->username,
            'roles'=> Role::whereNotIn('name',['camaba'])->get(),
            'faculty'=> Faculty::orderBy('code')->get(),
            'data'=> $user
        ]);
    }

    public function update(\App\Http\Requests\UserRequest &$request, User &$user){
        try {
            DB::beginTransaction();
            $user->update([
                'username'=> $request->username,
                'email'=> $request->email,
                'phone'=> $request->phone,
            ]);
            if($request->has('password') && $request->password != ''){
                $user->update([
                    'password'=> Hash::make($request->password),
                ]);
            }
            $user->syncRoles([$request->role]);
            $user->studyPrograms()->sync($request->study_program_code ?? []);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(User &$user){
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return redirect()->route('user.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
