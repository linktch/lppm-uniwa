<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Verifikasi Berkas Pendaftaran KKN</title>
    <style>
        body {
            font-family: 'Times New Roman', Arial, sans-serif;
            font-size: 12px;
            margin: 25px;
        }
        
        /* Header dikosongkan karena sudah ada header sendiri */
        .header {
            display: none;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #1a3e6f;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-valid {
            color: #4caf50;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ff9800;
            font-weight: bold;
        }
        
        .status-ditolak {
            color: #f44336;
            font-weight: bold;
        }
        
        .ttd-section {
            margin-top: 50px;
            margin-bottom: 30px;
        }
        
        .ttd-left {
            float: left;
            width: 50%;
            text-align: center;
        }
        
        .ttd-right {
            float: right;
            width: 50%;
            text-align: center;
        }
        
        .clearfix {
            clear: both;
        }
        
        .ttd-content {
            margin-top: 70px;
        }
        
        .ttd-label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .ttd-name {
            font-weight: bold;
            margin-top: 20px;
            text-decoration: underline;
            font-size: 12px;
        }
        
        .ttd-title {
            font-size: 10px;
            margin-top: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
            border: 1px solid #999;
        }
        
        .summary h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #1a3e6f;
            font-size: 13px;
        }
        
        .summary table {
            width: auto;
            margin-top: 0;
            border: none;
        }
        
        .summary td {
            border: none;
            padding: 5px;
        }
        
        .summary th {
            background-color: transparent;
            color: #333;
            border: none;
            text-align: left;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-justify {
            text-align: justify;
        }
        
        .info-kkn {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #1a3e6f;
            background-color: #e8f0fe;
        }
        
        .info-kkn table {
            border: none;
            margin: 0;
        }
        
        .info-kkn td {
            border: none;
            padding: 3px;
        }
    </style>
</head>
<body>
    <!-- Header dihapus/dikosongkan karena sudah ada header sendiri -->
    
    <div class="info-kkn">
        <table>
            <tr>
                <td style="width: 150px;"><strong>Periode KKN</strong></td>
                <td>: {{ $periode_nama ?? 'Periode ' . $periode_id }}</td>
            </tr>
            <tr>
                <td><strong>Kegiatan KKN</strong></td>
                <td>: {{ $kegiatan_nama ?? 'Kuliah Kerja Nyata Reguler' }}</td>
            </tr>
            <tr>
                <td><strong>Lokasi Penempatan</strong></td>
                <td>: {{ $lokasi_kkn ?? 'Seluruh Desa/Kelurahan Wilayah Binaan' }}</td>
            </tr>
            <tr>
                <td><strong>Jumlah Pendaftar</strong></td>
                <td>: {{ $files->count() }} Mahasiswa</td>
            </tr>
        </table>
    </div>
    
    <div class="sub-header">
        <strong>DAFTAR HASIL VERIFIKASI BERKAS PERSYARATAN KKN</strong><br>
        Periode Pendaftaran: {{ $tanggal_mulai ?? '________' }} s.d. {{ $tanggal_selesai ?? '________' }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">NIM</th>
                <th style="width: 18%;">Nama Mahasiswa</th>
                <th style="width: 18%;">Program Studi</th>
                <th style="width: 12%;">Status Surat<br>Keterangan</th>
                <th style="width: 11%;">Status KTP</th>
                <th style="width: 12%;">Status SPP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $index => $file)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $file->nim }}</td>
                <td>{{ $file->nama }}</td>
                <td>{{ $file->prodi }}</td>
                <td class="text-center status-{{ $file->status_surat }}">
                    {{ $file->status_surat == 'valid' ? 'VALID' : ($file->status_surat == 'ditolak' ? 'DITOLAK' : 'PENDING') }}
                </td>
                <td class="text-center status-{{ $file->status_ktp }}">
                    {{ $file->status_ktp == 'valid' ? 'VALID' : ($file->status_ktp == 'ditolak' ? 'DITOLAK' : 'PENDING') }}
                </td>
                <td class="text-center status-{{ $file->status_spp }}">
                    {{ $file->status_spp == 'valid' ? 'VALID' : ($file->status_spp == 'ditolak' ? 'DITOLAK' : 'PENDING') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @php
        $validSurat = $files->where('status_surat', 'valid')->count();
        $pendingSurat = $files->where('status_surat', 'pending')->count();
        $ditolakSurat = $files->where('status_surat', 'ditolak')->count();
        
        $validKTP = $files->where('status_ktp', 'valid')->count();
        $pendingKTP = $files->where('status_ktp', 'pending')->count();
        $ditolakKTP = $files->where('status_ktp', 'ditolak')->count();
        
        $validSPP = $files->where('status_spp', 'valid')->count();
        $pendingSPP = $files->where('status_spp', 'pending')->count();
        $ditolakSPP = $files->where('status_spp', 'ditolak')->count();
        
        $total = $files->count();
        
        $persentaseValidSurat = $total > 0 ? round(($validSurat / $total) * 100, 2) : 0;
        $persentaseValidKTP = $total > 0 ? round(($validKTP / $total) * 100, 2) : 0;
        $persentaseValidSPP = $total > 0 ? round(($validSPP / $total) * 100, 2) : 0;
        
        $eligible_kkn = $files->where('status_surat', 'valid')
                             ->where('status_ktp', 'valid')
                             ->where('status_spp', 'valid')
                             ->count();
        $persentaseEligible = $total > 0 ? round(($eligible_kkn / $total) * 100, 2) : 0;
        $tidak_eligible = $total - $eligible_kkn;
    @endphp
    
    <div class="summary">
        <h4>REKAPITULASI HASIL VERIFIKASI BERKAS PENDAFTARAN KKN</h4>
        <table>
            <tr>
                <td style="width: 250px;"><strong>Jumlah Mahasiswa</strong></td>
                <td>: <strong>{{ $mahasiswaTerplotting }}</strong> orang</td>
            </tr>
            <tr>
                <td style="width: 250px;"><strong>Jumlah Mahasiswa yang mendaftar</strong></td>
                <td>: <strong>{{ $total }}</strong> orang</td>
            </tr>
            <tr>
                <td><strong>Surat Keterangan Pendamping (SKP)</strong></td>
                <td>: VALID: {{ $validSurat }} orang ({{ $persentaseValidSurat }}%) | PENDING: {{ $pendingSurat }} | DITOLAK: {{ $ditolakSurat }}</td>
            </tr>
            <tr>
                <td><strong>KTP</strong></td>
                <td>: VALID: {{ $validKTP }} orang ({{ $persentaseValidKTP }}%) | PENDING: {{ $pendingKTP }} | DITOLAK: {{ $ditolakKTP }}</td>
            </tr>
            <tr>
                <td><strong>Bukti Pembayaran SPP</strong></td>
                <td>: VALID: {{ $validSPP }} orang ({{ $persentaseValidSPP }}%) | PENDING: {{ $pendingSPP }} | DITOLAK: {{ $ditolakSPP }}</td>
            </tr>
            <tr style="border-top: 1px solid #000;">
                <td><strong>Mahasiswa ELIGIBEL mengikuti KKN</strong></td>
                <td>: <strong>{{ $eligible_kkn }}</strong> orang (<strong>{{ $persentaseEligible }}%</strong>)</td>
            </tr>
            <tr>
                <td><strong>Mahasiswa TIDAK ELIGIBEL mengikuti KKN</strong></td>
                <td>: <strong>{{ $tidak_eligible }}</strong> orang (<strong>{{ 100 - $persentaseEligible }}%</strong>)</td>
            </tr>
        </table>
        
        <div style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #999;">
            <p style="font-style: italic; font-size: 10px;" class="text-justify">
                <strong>Catatan:</strong><br>
                1. Status VALID: Berkas memenuhi persyaratan.<br>
                2. Status PENDING: Berkas sedang dalam proses verifikasi.<br>
                3. Status DITOLAK: Berkas tidak memenuhi persyaratan.<br>
                4. Mahasiswa dinyatakan ELIGIBEL mengikuti KKN apabila seluruh persyaratan dinyatakan VALID.
            </p>
        </div>
    </div>
    
    <div class="ttd-section">
        <div class="ttd-left">
            <div class="ttd-label">Mengetahui,</div>
            <div class="ttd-label">Ketua Pelaksana KKN,</div>
            <div class="ttd-content">
                <br><br>
                <div class="ttd-name">M. Zakariya Yahya, S.P</div>
                <div class="ttd-title">NIY. _________________________</div>
            </div>
        </div>
        
        <div class="ttd-right">
            <div class="ttd-label">Mengesahkan,</div>
            <div class="ttd-label">Pimpinan PT / Rektor,</div>
            <div class="ttd-content">
                <br><br>
                <div class="ttd-name">Dr. Fauziah Isnaini, M.Pd.I</div>
                <div class="ttd-title">NIY. 197003162002092004</div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div class="footer">
        <p>Dokumen ini ditandatangani secara elektronik | Sistem Informasi Pendaftaran KKN</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>