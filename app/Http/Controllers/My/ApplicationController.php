<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationPayment;
use Illuminate\Http\Request;
use App\Models\PmbRegistrationDocument;
use Illuminate\Validation\ValidationException;
use App\Models\ApplicationDocuments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(){
        $query = Application::query()
            ->whereHas('applicant', function($q){
                $q->where('applicants.user_id', auth()->user()->id);
            });
        $data = $query->orderBy('created_at','DESC')->get();
        return view('home.my.application.index',[
            'title'=> 'Pendaftaran Saya',
            'data'=> $data
        ]);
    }

    public function show(Application &$application){
        $payment = $application->payments()->where(
            'payment_type','formulir'
        )->first();
        return view('home.my.application.show',[
            'title'=> 'Detail Pendaftaran Saya',
            'payment'=> $payment,
            'data'=> $application
        ]);
    }

    public function document_store(\App\Http\Requests\ApplicationDocumentRequest &$request, Application &$application){
        // dd($request->all());
        // Pastikan pendaftaran milik CAMABA yang sedang login.
        abort_unless(
            $application->applicant?->user_id === auth()->id(),
            403,
            'Anda tidak memiliki akses ke pendaftaran ini.'
        );
        // Ambil konfigurasi dokumen sesuai jalur pendaftaran.
        $document_type = PmbRegistrationDocument::query()
            ->whereKey($request->input('pmb_registration_document_id'))
            ->where(
                'pmb_registration_path_id',
                $application->pmb_registration_path_id
            )
            ->where('is_active', true)
            ->firstOrFail();
        $file = $request->file('file');
        // Validasi MIME berdasarkan konfigurasi database.
        $allowed_mimes = $document_type->allowed_mimes ?? [];
        if (is_string($allowed_mimes)) {
            $allowed_mimes = json_decode($allowed_mimes, true) ?? [];
        }
        $file_mime = $file->getMimeType();
        if (
            !empty($allowed_mimes) &&
            !in_array($file_mime, $allowed_mimes, true)
        ) {
            throw ValidationException::withMessages([
                'file' => 'Format file tidak diizinkan.',
            ]);
        }
        $existing_document = ApplicationDocuments::query()
            ->where('application_id', $application->id)
            ->where(
                'pmb_registration_document_id',
                $document_type->id
            )
            ->first();
        $old_file_path = $existing_document?->file_path;
        // Simpan file baru pada disk local.
        $file_path = $file->store(
            'pmb/documents/' . $application->registration_number,
            'public'
        );
        if (!$file_path) {
            throw new \RuntimeException('File gagal disimpan.');
        }
        try {
            DB::transaction(function () use (
                $application,
                $document_type,
                $file,
                $file_path,
                $existing_document
            ) {
                $document_data = [
                    'file_path' => $file_path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'verification_status' => 'pending',
                    'verified_by' => null,
                    'verified_at' => null,
                    'verification_notes' => null,
                ];
                if ($existing_document) {
                    $existing_document->update($document_data);
                } else {
                    ApplicationDocuments::create(array_merge(
                        $document_data,
                        [
                            'application_id' => $application->id,
                            'pmb_registration_document_id' => $document_type->id,
                        ]
                    ));
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($file_path);
            throw $exception;
        }
        // Hapus file lama setelah data baru berhasil disimpan.
        if ($old_file_path && $old_file_path !== $file_path) {
            Storage::disk('public')->delete($old_file_path);
        }
        return back()->with(
            'success',
            'Berkas berhasil diunggah dan menunggu verifikasi.'
        );
    }

    public function paymentform_store(\App\Http\Requests\ApplicationPaymentRequest &$request, Application &$application){
        // dd($request->all());
        $amount = $application->pmbWavePath()->first()->registration_fee;
        $payment_type = 'formulir';
        // Pastikan pendaftaran milik CAMABA yang sedang login.
        abort_unless(
            $application->applicant?->user_id === auth()->id(),
            403,
            'Anda tidak memiliki akses ke pendaftaran ini.'
        );
        $existing_payment = ApplicationPayment::query()
            ->where('application_id', $application->id)
            ->where('payment_type', $payment_type)
            ->first();

        if ($existing_payment && in_array($existing_payment->verification_status, ['approved', 'not_required'])) {
            throw ValidationException::withMessages([
                'file' => 'Pembayaran ini sudah diselesaikan.',
            ]);
        }
        $old_file_path = $existing_payment?->proof_path;
        // Simpan bukti pembayaran di disk public.
        $file_path = $request->file('file')->store(
            'pmb/payments/' . $application->registration_number,
            'public'
        );
        if (!$file_path) {
            throw new \RuntimeException('Bukti pembayaran gagal disimpan.');
        }
        try {
            DB::transaction(function () use (
                $application,
                $existing_payment,
                $file_path,
                $amount,
                $payment_type
            ) {
                $payment_data = [
                    'amount' => $amount,
                    'proof_path' => $file_path,
                    'verification_status' => 'pending',
                    'verified_by' => null,
                    'verified_at' => null,
                    'verification_notes' => null,
                ];
                if ($existing_payment) {
                    $existing_payment->update($payment_data);
                } else {
                    ApplicationPayment::create(array_merge(
                        $payment_data,
                        [
                            'application_id' => $application->id,
                            'payment_type' => $payment_type,
                        ]
                    ));
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($file_path);
            throw $exception;
        }
        // Hapus file lama setelah data baru berhasil disimpan.
        if ($old_file_path && $old_file_path !== $file_path) {
            Storage::disk('public')->delete($old_file_path);
        }
        return back()->with(
            'success',
            'Berkas berhasil diunggah dan menunggu verifikasi.'
        );
    }
}
