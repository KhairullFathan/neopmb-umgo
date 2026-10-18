<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\PmbPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\ApplicationGraduate;

class PmbGraduationController extends Controller
{
    public function index(Request &$request){
        $query = Application::query();
        $query->where('current_stage', 'PENGUMUMAN_KELULUSAN');
        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->whereHas('applicant', function($q) use($search){
                $q->where('applicants.name', 'like', "%{$search}%")
                    ->orWhere('name','like', "%{$search}%")
                    ->orWhere('identity_number','like', "%{$search}%");
            });
        }
        $data = $query->orderBy('registration_number','DESC')->paginate(25)->appends(['search']);
        return view('pmb.pmb-graduation.index',[
            'title'=> 'Kelulusan PMB',
            'periods'=> PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
            'data'=> $data,
        ]);
    }

    public function store(Request $request, Application $application){
        $validated = $request->validate([
            'study_program_code' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        // Pastikan prodi merupakan pilihan pada pendaftaran ini.
        $isValidChoice = $application->studyPrograms()
            ->where(
                'study_program_code',
                $validated['study_program_code']
            )
            ->exists();

        if (! $isValidChoice) {
            throw ValidationException::withMessages([
                'study_program_code' =>
                    'Program studi bukan pilihan pada pendaftaran ini.',
            ]);
        }
        $graduate = DB::transaction(function () use (
            $application,
            $validated,
            $request
        ) {
            return ApplicationGraduate::updateOrCreate(
                [
                    'application_id' => $application->id,
                ],
                [
                    'study_program_code' => $validated['study_program_code'],
                    'notes' => null,
                    'decided_by' => $request->user()->id,
                    'decided_at' => now(),
                ]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Kelulusan calon mahasiswa berhasil ditetapkan.',
            'data' => [
                'application_id' => $graduate->application_id,
                'study_program_code' => $graduate->study_program_code,
                'status' => $graduate->status,
                'decided_at' => $graduate->decided_at,
            ],
        ]);
    }
}
