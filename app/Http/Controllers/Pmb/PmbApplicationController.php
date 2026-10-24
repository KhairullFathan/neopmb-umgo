<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Models\ApplicationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Application;
use App\Models\PmbPeriod;
use App\Models\PmbWave;
use App\Models\PmbWavePath;
use App\Models\ApplicationDocuments;

class PmbApplicationController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();
        $query = Application::query();
        $pmbWaves = PmbWave::query();
        $pmbWavePaths = PmbWavePath::query();
        $query->whereHas('studyPrograms', function($q) use ($user){
            $q->whereIn(
                'study_program_code',
                $user->studyPrograms()->select('study_programs.code')
            );
        });

        if($request->has('search') && $request->search != ''){
            $search = $request->search;
            $query->whereHas('applicant', function($q) use($search){
                $q->where('applicants.name', 'like', "%{$search}%")
                    ->orWhere('name','like', "%{$search}%")
                    ->orWhere('identity_number','like', "%{$search}%");
            });
        }

        if($request->has('period_id') && $request->period_id != ''){
            $period_id = $request->period_id;
            $query->whereHas('pmbWave', function($q) use ($period_id){
                $q->where('pmb_waves.pmb_period_id', $period_id);
            });
            $pmbWaves->where('pmb_period_id', $period_id);
        }

        if($request->has('wave_id') && $request->wave_id != ''){
            $wave_id = $request->wave_id;
            $query->whereHas('pmbWave', function($q) use ($wave_id){
                $q->where('pmb_waves.id', $wave_id);
            });
            $pmbWavePaths->where('pmb_wave_id', $wave_id);
        }

        if($request->has('registration_path_id') && $request->registration_path_id != ''){
            $registration_path_id = ($request->registration_path_id);
            $query->whereHas('pmbRegistrationPath', function($q) use ($registration_path_id){
                $q->where('pmb_registration_paths.id', $registration_path_id);
            });
        }
        $data = $query->orderBy('registration_number','DESC')->paginate(25)->appends(['search']);
        return view('pmb.pmb-application.index',[
            'title'=> 'Data Formulir',
            'periods'=> PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
            'pmbWaves'=> $pmbWaves->orderBy('pmb_period_id','desc')->orderBy('code', 'desc')->get(),
            'pmbWavePaths'=> $pmbWavePaths->get(),
            'data'=> $data,
        ]);
    }

    public function show(Application &$application){
        $payment = $application->payments()->where(
            'payment_type','formulir'
        )->first();
        return view('pmb.pmb-application.show',[
            'title'=> 'Data Formulir '.$application->registration_number,
            'payment'=> $payment,
            'data'=> $application,
        ]);
    }

    public function payment_verify(\App\Http\Requests\ApplicationPaymentRequest &$request, ApplicationPayment &$payment){
        // dd($request->all());
        $application = Application::findOrFail($payment->application_id);
        $stages = $application->pmbWavePath()->first()->stages()
            ->with('pmbRegistrationStage')
            ->where('is_active', true)
            ->orderBy('sequence')
            ->get();
        try {
            DB::beginTransaction();
            $payment->update([
                'sender_account_name'=> $request->sender_account_name,
                'sender_bank_name'=> $request->sender_bank_name,
                'paid_amount'=> $request->paid_amount,
                'paid_at'=> $request->paid_at,
                'verification_status'=> $request->verification_status,
                'verification_notes'=> $request->verification_notes,
                'verified_by'=> auth()->user()->id,
                'verified_at'=> now(),
            ]);
            if($request->verification_status === 'approved'){
                $current_stage = $stages->first(function ($stage) {
                    $code = $stage->pmbRegistrationStage?->code;
                    if (empty($code)) {
                        return false;
                    }
                    if ($code === 'PENDAFTARAN' || $code === 'PEMBAYARAN_FORMULIR') {
                        return false;
                    }
                    return true;
                });
                $application->update([
                    'current_stage'=> $current_stage->pmbRegistrationStage->code,
                ]);
            }else {
                $application->update([
                    'current_stage'=> 'PEMBAYARAN_FORMULIR',
                ]);
            }
            DB::commit();
            return redirect()->route('pmb.application.show', $application)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function document_verify(\App\Http\Requests\ApplicationDocumentRequest &$request, ApplicationDocuments &$document){
        $application = Application::findOrFail($document->application_id);
        $stages = $application->pmbWavePath()->first()->stages()
            ->with('pmbRegistrationStage')
            ->where('is_active', true)
            ->orderBy('sequence')
            ->get();
        try {
            DB::beginTransaction();
            $document->update([
                'verification_status'=> $request->verification_status,
                'verification_notes'=> $request->verification_notes,
                'verified_by'=> auth()->user()->id,
                'verified_at'=> now(),
            ]);
            DB::commit();
            return redirect()->route('pmb.application.show', $application)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function stage_set(Request &$request, Application &$application){
        try {
            DB::beginTransaction();
            $application->update([
                'current_stage'=> $request->current_stage,
            ]);
            DB::commit();
            return redirect()->route('pmb.application.show', $application)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
