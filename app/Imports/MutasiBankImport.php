<?php

namespace App\Imports;

use App\Models\MutasiBank;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MutasiBankImport
{
    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function import()
    {
        $spreadsheet = IOFactory::load($this->filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            MutasiBank::create([
                'tanggal' => Carbon::parse($row[0]),
                'deskripsi' => $row[1],
                'nominal' => $row[2],
                'tipe' => strtolower($row[3]) === 'debit' ? 'debit' : 'kredit',
                'saldo_setelah' => $row[4] ?? null,
            ]);
        }
    }
}
