<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceImportTemplateExport implements FromCollection, WithHeadings
{
    /**
     * Fetch data for export: only users' full names and current period.
     */
    public function collection()
    {
        $currentPeriod = \Carbon\Carbon::now()->format('Y-m'); // Current month in YYYY-MM format

        return User::whereNull('deleted_at')
        ->select('nama_lengkap')
        ->get()
        ->map(function ($user) use ($currentPeriod) {
            return [
                'nama_lengkap' => $user->nama_lengkap,
                'periode' => $currentPeriod,
                'work_days' => '',
                'late_less_30' => '', // Leave blank
                'late_more_30' => '', // Leave blank
                'sick_days' => '',    // Leave blank
            ];
        });

    }

    /**
     * Set headings for the columns in the template.
     */
    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Periode',
            'Hari Kerja',
            'Late Less 30 min',
            'Late More 30 min',
            'Sakit or Izin',
        ];
    }
}
