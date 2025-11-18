<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlumniExport implements FromCollection, WithHeadings
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama',
            'Email',
            'No HP',
            'Jenis Kelamin',
            'Nama Ortu',
            'Tempat Lahir',
            'Tanggal Lahir',
            'STTP',
            'Angkatan',
            'Jurusan',
            'Pekerjaan',
            'Perusahaan',
            'Alamat',
        ];
    }

    public function collection()
    {
        return $this->rows->map(function ($a) {
            return [
                $a->nis,
                $a->nisn,
                $a->nama,
                $a->email,
                $a->phone,
                $a->jenis_kelamin === 'L'
                    ? 'Laki-laki'
                    : ($a->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                $a->nama_ortu,
                $a->tempat_lahir,
                optional($a->tanggal_lahir)->format('d-m-Y'),
                $a->sttp,
                $a->angkatan,
                $a->jurusan,
                $a->pekerjaan,
                $a->perusahaan,
                $a->alamat,
            ];
        });
    }
}
