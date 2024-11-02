<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AttendanceImport implements ToModel, WithHeadingRow
{
    private $usersCache = [];
    private $importedCount = 0;
    private $skippedCount = 0;
    private $skippedDetails = []; // Array untuk menyimpan detail data yang dilewati

    public function __construct()
    {
        // Cache semua pengguna untuk menghindari query database berulang
        $this->usersCache = User::pluck('id', 'nama_lengkap')->toArray();
    }

    public function model(array $row)
    {
        Log::info('Data baris: ', $row);

        // Temukan user_id berdasarkan nama_lengkap dari cache
        $userId = $this->usersCache[$row['nama_lengkap']] ?? null;
        $periode = null;

        try {
            if (is_numeric($row['periode'])) {
                $periode = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['periode'])->format('Y-m');
            } elseif (\DateTime::createFromFormat('Y-m', $row['periode']) !== false) {
                $periode = \DateTime::createFromFormat('Y-m', $row['periode'])->format('Y-m');
            } elseif (\DateTime::createFromFormat('d/m/y', $row['periode']) !== false) {
                $periode = \DateTime::createFromFormat('d/m/y', $row['periode'])->format('Y-m');
            } else {
                Log::error("Format Periode tidak dikenali: " . $row['periode']);
            }
        } catch (\Exception $e) {
            Log::error("Kesalahan saat parsing Periode: " . $e->getMessage());
        }

        if ($userId && $periode) {
            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('periode', $periode)
                ->exists();

            if ($existingAttendance) {
                Log::info('Melewati absensi yang sudah ada untuk user_id ' . $userId . ' dan periode ' . $periode);

                // Simpan detail data yang dilewati
                $this->skippedDetails[] = [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'periode' => $periode,
                ];

                $this->skippedCount++;
                return null;
            }

            $this->importedCount++;
            return new Attendance([
                'user_id' => $userId,
                'periode' => $periode,
                'work_days' => $row['hari_kerja'] ?? 0,
                'late_less_30' => $row['late_less_30_min'] ?? 0,
                'late_more_30' => $row['late_more_30_min'] ?? 0,
                'sick_days' => $row['sakit_or_izin'] ?? 0,
            ]);
        }

        Log::error('Import Attendance: Pengguna tidak ditemukan atau gagal parsing Periode untuk nama_lengkap ' . $row['nama_lengkap']);

        // Simpan detail data yang dilewati jika pengguna tidak ditemukan atau periode tidak valid
        $this->skippedDetails[] = [
            'nama_lengkap' => $row['nama_lengkap'],
            'periode' => $row['periode'] ?? 'Tidak diketahui',
        ];

        $this->skippedCount++;
        return null;
    }

    public function getImportSummary()
    {
        return [
            'importedCount' => $this->importedCount,
            'skippedCount' => $this->skippedCount,
            'skippedDetails' => $this->skippedDetails,
        ];
    }
}
