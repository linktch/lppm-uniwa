<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penilaian Hafalan - {{ $mahasiswa->nama }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #1e3a5f;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 10px;
        }
        .info-mahasiswa {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .info-mahasiswa table {
            width: 100%;
        }
        .info-mahasiswa td {
            padding: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .timeline-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid #d97706;
        }
        .timeline-info h4 {
            margin: 0 0 8px 0;
            color: #92400e;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-nilai th {
            background: #1e3a5f;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 11px;
        }
        .table-nilai td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .table-nilai .text-center {
            text-align: center;
        }
        .status-wajib {
            color: #166534;
            font-weight: bold;
        }
        .status-tidak-wajib {
            color: #64748b;
        }
        .nilai-sangat {
            color: #059669;
            font-weight: bold;
        }
        .nilai-cukup {
            color: #d97706;
            font-weight: bold;
        }
        .total-nilai {
            margin-top: 20px;
            text-align: right;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 8px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .ttd {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd-box {
            text-align: center;
            width: 200px;
        }
        .ttd-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        @page {
            margin: 1.5cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENILAIAN HAFALAN</h2>
        <p>Kuliah Kerja Nyata (KKN) | Penilaian Hafalan</p>
    </div>

    <!-- Informasi Mahasiswa -->
    <div class="info-mahasiswa">
        <table>
            <tr>
                <td class="info-label">NIM</td>
                <td>: {{ $mahasiswa->nim }}</td>
                <td class="info-label">Program Studi</td>
                <td>: {{ $mahasiswa->prodi }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Lengkap</td>
                <td>: {{ $mahasiswa->nama }}</td>
                <td class="info-label">Kelompok</td>
                <td>: -</td>
            </tr>
            <tr>
                <td class="info-label">Jenis Kelamin</td>
                <td>: {{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                <td class="info-label">Tanggal Penilaian</td>
                <td>: {{ $tanggal_cetak }}</td>
            </tr>
        </table>
    </div>


    <!-- Tabel Penilaian -->
    <table class="table-nilai">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="55%">Indikator Penilaian</th>
                <th width="20%">Status</th>
                <th width="20%">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($indikators as $index => $indikator)
            @php
                $isWajib = ($mahasiswa->jenis_kelamin == 'L' || $mahasiswa->jenis_kelamin == 'Laki-laki')
                    ? $indikator->laki_laki
                    : $indikator->perempuan;
                $nilai = $penilaian->get($indikator->id);
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $indikator->nama_indikator }}</td>
                <td class="text-center">
                    @if($isWajib)
                        <span class="status-wajib">Wajib</span>
                    @else
                        <span class="status-tidak-wajib">Tidak Wajib</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($isWajib)
                        @if($nilai)
                            <span class="{{ $nilai->nilai == 'sangat_lancar' ? 'nilai-sangat' : 'nilai-cukup' }}">
                                {{ $nilai->nilai == 'sangat_lancar' ? 'Sangat Lancar' : 'Cukup Lancar' }}
                            </span>
                        @else
                            <span class="status-tidak-wajib">Belum Dinilai</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Tanda Tangan -->
    <div class="ttd">
        <div class="ttd-box">
            <div>Mengetahui,</div>
            <div>Kepala Program Studi</div>
            <div class="ttd-line"></div>
            <div>{{ $penilai }}</div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara elektronik pada {{ $tanggal_cetak }}<br>
        Merupakan bukti sah penilaian hafalan peserta KKN
    </div>
</body>
</html>