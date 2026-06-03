<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ScreeningAnswer;
use Illuminate\Support\Facades\Auth;

class ScreeningPdfController extends Controller
{
    public function index()
    {
        $data = ScreeningAnswer::with('question', 'user')
            ->where('user_id', Auth::id())
            ->get();

        $user = $data->first()?->user;

        // decode JSON biodata
        $biodata = $user ? json_decode($user->data_mahasiswa ?? '{}', true) : [];

        $nama = $biodata['nama_mahasiswa'] ?? '-';
        $nim = $biodata['nim'] ?? '-';
        $prodi = $biodata['nama_program_studi'] ?? '-';
        $tgl_lahir = $biodata['tanggal_lahir'] ?? '....................';
        $tinggi = $biodata['tinggi_badan'] ?? '....................';
        $berat = $biodata['berat_badan'] ?? '....................';

        $pdf = Pdf::loadView('pdf.screening', compact(
            'data',
            'nama',
            'nim',
            'prodi',
            'tgl_lahir',
            'tinggi',
            'berat'
        ));

        $pdf->setPaper('F4', 'portrait');

        return $pdf->stream('screening.pdf');
    }
}