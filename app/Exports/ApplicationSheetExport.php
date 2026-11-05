<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicationSheetExport implements
    Export,
    FromQuery,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{
    protected Builder $query;
    protected string $title;
    protected int $rowNumber = 0;

    public function __construct(Builder $query, string $title)
    {
        $this->query = $query->with([
            'applicant',
            'pmbPeriod',
            'pmbWave',
            'pmbRegistrationPath',
            'studyPrograms.studyProgram',
        ]);

        $this->title = $title;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function title(): string
    {
        $title = preg_replace('/[\\\\\/\?\*\[\]\:]/', '-', $this->title);

        return mb_substr($title, 0, 31);
    }

    public function styles(Worksheet $sheet): array
    {
        // Heading bold
        $sheet->getStyle('A1:X1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Border seluruh tabel
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:X{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '000000',
                    ],
                ],
            ],
        ]);

        return [];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Pendaftaran',
            'Nama Calon Mahasiswa',
            'Nomor Identitas',
            'Periode PMB',
            'Gelombang',
            'Jalur Pendaftaran',
            'Pilihan Program Studi 1',
            'Pilihan Program Studi 2',
            'Jenis Kelamin',
            'Agama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No Telp.',
            'Kewarganegaraan',
            'Asal Negara',
            'Nama Gadis Ibu Kandung',
            'No Telp. Orang Tua',
            'Provinsi',
            'Kabupaten / Kota',
            'Kecamatan',
            'Desa / Kelurahan',
            'Asal Sekolah',
            'Asal Jurusan / Prodi',
        ];
    }

    public function map($application): array
    {
        $studyPrograms = $application->studyPrograms->keyBy('choice_number');
        return [
            ++$this->rowNumber,
            $application->registration_number,
            $application->applicant?->name,
            $application->applicant?->identity_number,
            $application->pmbPeriod?->name,
            $application->pmbWave?->code,
            $application->pmbRegistrationPath?->name,
            // Pilihan Program Studi 1
            $studyPrograms->get(1)?->studyProgram?->name,
            // Pilihan Program Studi 2
            $studyPrograms->get(2)?->studyProgram?->name,
            $application->applicant?->gender,
            $application->applicant?->religion,
            $application->applicant?->birth_place,
            $application->applicant?->birth_date
                ? \Carbon\Carbon::parse($application->applicant->birth_date)->format('d-m-Y')
                : null,
            $application->applicant?->phone,
            $application->applicant?->nationality,
            $application->applicant?->country_of_origin,
            $application->applicant?->mother_name,
            $application->applicant?->parent_phone,
            $application->applicant?->village?->district?->regency?->province?->name,
            $application->applicant?->village?->district?->regency?->name,
            $application->applicant?->village?->district?->name,
            $application->applicant?->village?->name,
            $application->applicant?->school_origin,
            $application->applicant?->school_major,
        ];
    }
}
