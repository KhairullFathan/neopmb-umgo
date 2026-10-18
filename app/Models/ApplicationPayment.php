<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;


#[Fillable(['application_id', 'payment_type', 'amount', 'payment_method', 'payment_reference', 'sender_account_name', 'sender_bank_name', 'proof_path', 'paid_amount', 'paid_at', 'verification_status', 'verified_by', 'verified_at', 'verification_notes'])]

class ApplicationPayment extends Model
{
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    protected function paymentTypeLabel(): Attribute
    {
        return Attribute::get(
            fn () => match ($this->payment_type) {
                'formulir' => 'Pembayaran Formulir',
                'registrasi_ulang' => 'Pembayaran Registrasi Ulang',
                default => 'Tidak diketahui',
            }
        );
    }

    protected function verificationStatusLabel(): Attribute
    {
        return Attribute::get(
            fn () => match ($this->verification_status) {
                'pending' => 'Menunggu Verifikasi',
                'approved' => 'Terverifikasi',
                'rejected' => 'Ditolak',
                'not_required' => 'Tidak Memerlukan Pembayaran',
                default => 'Tidak diketahui',
            }
        );
    }

    protected function verificationStatusBadge(): Attribute
    {
        return Attribute::get(
            fn () => match ($this->verification_status) {
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'danger',
                'not_required' => 'secondary',
                default => 'secondary',
            }
        );
    }

    public function getAmountFormattedAttribute(){
        return 'Rp. '.number_format($this->amount,0,',','.');
    }

    public function getPaidAmountFormattedAttribute(){
        return 'Rp. '.number_format($this->paid_amount,0,',','.');
    }

    public function application(){
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function verifier(){
        return $this->belongsTo(User::class,'verified_by','id');
    }
}
