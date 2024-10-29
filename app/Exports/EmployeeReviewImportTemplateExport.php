<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeReviewImportTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $currentPeriod = \Carbon\Carbon::now()->format('Y-m'); // Current month in YYYY-MM format

        return User::whereNull('deleted_at')
            ->when(auth()->user()->role !== 'admin', function ($query) {
                return $query->where('approval_id', Auth::id());
            })
            ->select('nama_lengkap')
            ->get()
            ->map(function ($user) use ($currentPeriod) {
                return [
                    'nama_lengkap' => $user->nama_lengkap,
                    'periode' => $currentPeriod,
                    'responsiveness' => '',
                    'problem_solver' => '',
                    'helpfulness' => '',
                    'initiative' => '',
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
            'Responsiveness',
            'Problem solver',
            'Helpfulness',
            'Initiative',
        ];
    }
}
