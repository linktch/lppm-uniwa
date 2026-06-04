<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Sertifikat KKN - Universitas Wahidiyah | {{ $nama_mahasiswa }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700;800&family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #e2ddce;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            font-family: 'Inter', sans-serif;
        }

        .certificates-container {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 35px;
        }

        .certificate-page {
            background: linear-gradient(135deg, #FFFFFF 0%, #FEFAF2 100%);
            position: relative;
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease;
            overflow: hidden;
        }

        .certificate-page:hover {
            transform: scale(1.01);
        }

        .certificate-page::before {
            content: "";
            position: absolute;
            top: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            border-top: 3px solid #c9a03d;
            border-left: 3px solid #c9a03d;
            border-radius: 8px 0 0 0;
            z-index: 2;
        }

        .certificate-page::after {
            content: "";
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-bottom: 3px solid #c9a03d;
            border-right: 3px solid #c9a03d;
            border-radius: 0 0 8px 0;
            z-index: 2;
        }

        .corner-top-right {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-top: 3px solid #c9a03d;
            border-right: 3px solid #c9a03d;
            border-radius: 0 8px 0 0;
            z-index: 2;
        }

        .corner-bottom-left {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            border-bottom: 3px solid #c9a03d;
            border-left: 3px solid #c9a03d;
            border-radius: 0 0 0 8px;
            z-index: 2;
        }

        .certificate-page .border-line {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            bottom: 12px;
            border: 1px solid rgba(201, 160, 61, 0.25);
            border-radius: 18px;
            pointer-events: none;
            z-index: 1;
        }

        .page-content {
            padding: 1.8rem 2.2rem;
            position: relative;
            min-height: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            text-align: center;
            z-index: 3;
            background: transparent;
        }

        .univ-name {
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #2a5c4a;
            display: inline-block;
            width: auto;
            margin: 0 auto 5px;
            padding-bottom: 8px;
            position: relative;
        }

        .univ-name::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4b87a, #c9a03d, #d4b87a, transparent);
        }

        .header-container {
            text-align: center;
            margin-bottom: 10px;
            width: 100%;
        }

        .panitia {
            font-size: 0.7rem;
            font-weight: 500;
            color: #6e5a3a;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .lokasi {
            font-size: 0.65rem;
            color: #8f7a54;
            font-style: normal;
            letter-spacing: 0.5px;
        }

        .cert-title {
            text-align: center;
            margin: 8px 0 6px;
            width: 100%;
        }

        .cert-title h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #b8860b;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #8B6914, #d4a017);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .nomor-sertifikat {
            text-align: center;
            font-size: 0.7rem;
            color: #9b8a64;
            margin-top: 4px;
            font-family: monospace;
            font-weight: 500;
            background: #f5f0e4;
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
        }

        .preambule-text {
            font-size: 0.85rem;
            color: #3f3a2e;
            line-height: 1.4;
            text-align: center;
            margin: 8px 0 6px;
            width: 100%;
            font-weight: 400;
        }

        .recipient {
            text-align: center;
            margin: 8px 0;
            width: 100%;
        }

        .recipient-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: #2c3e2b;
            border-bottom: 2px solid #c9a03d;
            display: inline-block;
            padding: 0 15px 6px;
            letter-spacing: 1px;
        }

        .nim {
            font-size: 0.8rem;
            font-weight: 500;
            color: #7d6b46;
            margin-top: 5px;
            text-align: center;
        }

        .highlight-title {
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: #a5722c;
            background: linear-gradient(135deg, #fef8ec, #fcf3e0);
            display: inline-block;
            width: auto;
            margin: 6px auto;
            padding: 4px 25px;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            border: 1px solid #eedfbf;
        }

        .lokasi-kkn {
            text-align: center;
            font-size: 0.75rem;
            color: #6b5c40;
            font-style: italic;
            background: rgba(246, 240, 224, 0.7);
            padding: 5px 12px;
            border-radius: 30px;
            width: fit-content;
            margin: 8px auto;
            border: 1px solid #e8ddc5;
        }

        .doa-penutup {
            text-align: center;
            font-size: 0.8rem;
            color: #7c6a48;
            margin: 10px 0;
            font-style: italic;
            line-height: 1.3;
            background: #fef8ec;
            padding: 6px;
            border-radius: 30px;
            width: 85%;
            margin-left: auto;
            margin-right: auto;
            border-left: 3px solid #c9a03d;
            border-right: 3px solid #c9a03d;
        }

        .signature-double-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
            margin-bottom: 5px;
            width: 100%;
            gap: 40px;
            flex-wrap: wrap;
        }

        .signature-left {
            text-align: center;
            flex: 1;
            min-width: 160px;
        }

        .signature-right {
            text-align: center;
            flex: 1;
            min-width: 160px;
        }

        .signature-line {
            width: 200px;
            border-top: 1.5px solid #c9a03d;
            margin: 0 auto 8px;
        }

        .signature-name {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #2a4b3c;
        }

        .signature-title {
            font-size: 0.65rem;
            color: #967a48;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .date-wrapper {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .date-center {
            text-align: center;
            font-size: 0.8rem;
            color: #5f523b;
            font-weight: 500;
            display: inline-block;
            background: #faf5e8;
            padding: 4px 18px;
            margin-bottom: 70px;
            margin-top: 10px;
            border-radius: 30px;
        }

        .subtitle-page2 {
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: #a5722c;
            background: linear-gradient(135deg, #fef8ec, #fcf3e0);
            display: inline-block;
            width: auto;
            margin: 6px auto 12px;
            padding: 4px 22px;
            border-radius: 50px;
            border: 1px solid #eedfbf;
        }

        .table-container {
            margin: 10px 0 10px;
            overflow-x: auto;
            width: 100%;
        }

        .achievement-table {
            width: 100%;
            border-collapse: collapse;
            background: #fefaf2;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .achievement-table th {
            background: #e8ddc5;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.7rem;
            color: #4a3e28;
            padding: 8px 8px;
            text-align: center;
        }

        .achievement-table td {
            padding: 6px 10px;
            font-size: 0.7rem;
            color: #4a4130;
            border-bottom: 1px solid #eadfc5;
        }

        .achievement-table td:first-child {
            font-weight: 500;
            text-align: left;
        }

        .achievement-table td:nth-child(2) {
            text-align: center;
        }

        .status-sangat-lancar {
            background: linear-gradient(135deg, #1f6e43, #2a8a55);
            color: white;
            font-weight: 600;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 30px;
            font-size: 0.65rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .status-cukup-lancar {
            background: linear-gradient(135deg, #d9a13b, #c4892a);
            color: white;
            font-weight: 600;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 30px;
            font-size: 0.65rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .status-belum-dinilai {
            background: linear-gradient(135deg, #9e9e9e, #757575);
            color: white;
            font-weight: 600;
            display: inline-block;
            padding: 3px 14px;
            border-radius: 30px;
            font-size: 0.65rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .catatan-footer {
            margin-top: 12px;
            font-size: 0.6rem;
            color: #8f7a58;
            text-align: center;
            background: #faf4e6;
            padding: 6px;
            border-radius: 16px;
            width: 100%;
            border: 1px solid #ede0cc;
        }

        .predikat-box {
            margin-top: 15px;
            text-align: center;
            background: linear-gradient(135deg, #e8ddc5, #f5f0e4);
            padding: 8px 16px;
            border-radius: 30px;
            display: inline-block;
            width: auto;
        }

        .predikat-box span {
            font-weight: 700;
            color: #2a5c4a;
        }

        .action-buttons {
            display: flex;
            gap: 18px;
            justify-content: center;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        button {
            background: #2d6a4f;
            border: none;
            font-family: 'Inter', sans-serif;
            padding: 10px 28px;
            font-size: 0.85rem;
            font-weight: 500;
            color: white;
            border-radius: 40px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        button:hover {
            background: #1f543e;
            transform: scale(0.97);
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .certificates-container {
                gap: 0;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
            
            .certificate-page {
                box-shadow: none;
                border: 1px solid #ddd;
                border-radius: 0;
                margin: 0;
                page-break-after: always;
                page-break-inside: avoid;
                break-inside: avoid;
                break-before: avoid;
            }
            
            .certificate-page:last-child {
                page-break-after: auto;
            }
            
            .certificate-page {
                width: 297mm;
                height: 210mm;
                margin: 0 auto;
                overflow: hidden;
            }
            
            .page-content {
                padding: 10mm 15mm;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            
            .action-buttons {
                display: none;
            }
            
            .certificate-page {
                background: #FFFEF7;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .highlight-title, .lokasi-kkn, .doa-penutup, .catatan-footer, .subtitle-page2, .predikat-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .status-sangat-lancar, .status-cukup-lancar, .status-belum-dinilai {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            @page {
                size: A4 landscape;
                margin: 0;
            }
        }

        @media (max-width: 800px) {
            .page-content {
                padding: 1rem;
            }
            .recipient-name {
                font-size: 1.3rem;
            }
            .cert-title h1 {
                font-size: 1.3rem;
            }
            .achievement-table th, .achievement-table td {
                font-size: 0.6rem;
                padding: 3px 2px;
            }
            .signature-line {
                width: 140px;
            }
            .signature-double-wrapper {
                gap: 20px;
                margin-top: 40px;
            }
        }
    </style>
</head>
<body>
<div class="certificates-container">
    <!-- HALAMAN 1 - SERTIFIKAT KOMPETENSI -->
    <div class="certificate-page">
        <div class="corner-top-right"></div>
        <div class="corner-bottom-left"></div>
        <div class="border-line"></div>
        
        <div class="page-content">
            <div>
                <div class="header-container">
                    <div class="univ-name">{{ $univ_name ?? 'UNIVERSITAS WAHIDIYAH' }}</div>
                    <div class="panitia">{{ $panitia ?? 'PANITIA PELAKSANA KULIAH KERJA NYATA (KKN) TAHUN ' . date('Y') }}</div>
                    <div class="lokasi">{{ $lokasi ?? 'KABUPATEN MADIUN' }}</div>
                </div>

                <div class="cert-title">
                    <h1>SERTIFIKAT KOMPETENSI</h1>
                    <div class="nomor-sertifikat">Nomor: {{ $nomor_sertifikat }}</div>
                </div>

                <div class="preambule-text">
                    Panitia Pelaksana Kuliah Kerja Nyata (KKN) Universitas Wahidiyah Tahun {{ $tahun ?? date('Y') }} memberikan sertifikat ini kepada:
                </div>

                <div class="recipient">
                    <div class="recipient-name">{{ $nama_mahasiswa }}</div>
                    <div class="nim">NIM: {{ $nim }}</div>
                    @if(isset($prodi) && $prodi != '-')
                    <div class="nim" style="margin-top: 2px;">Program Studi: {{ $prodi }}</div>
                    @endif
                    @if(isset($kelompok) && $kelompok != '-')
                    <div class="nim" style="margin-top: 2px;">Kelompok: {{ $kelompok }}</div>
                    @endif
                </div>

                <div class="preambule-text" style="margin-top: 3px;">
                    Sebagai apresiasi atas kelulusan dan capaian dalam:
                </div>

                <div>
                    <div class="highlight-title">HAFALAN DOA DAN TAHLIL</div>
                </div>

                <div class="lokasi-kkn">
                    Yang diselenggarakan dalam rangkaian program persiapan pelaksanaan Kuliah Kerja Nyata (KKN)<br>
                    Universitas Wahidiyah di {{ $kabupaten ?? 'Kabupaten Madiun' }}, Provinsi {{ $provinsi ?? 'Jawa Timur' }}
                </div>

                <div class="doa-penutup">
                    "Semoga ilmu yang diraih dapat diamalkan dan membawa kemaslahatan bagi masyarakat"
                </div>
            </div>

            <div>
                <div class="date-wrapper">
                    <div class="date-center">{{ $tempat_tanggal ?? 'Kediri, ' . now()->format('d F Y') }}</div>
                </div>
                <div class="signature-double-wrapper">
                    <div class="signature-left">
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $penanda_tangan['nama'] ?? auth()->user()->name ?? 'M. Zakaria Yahya, S.P.' }}</div>
                        <div class="signature-title">{{ $penanda_tangan['jabatan'] ?? 'Ketua Panitia Pelaksana KKN' }}</div>
                    </div>
                    <!-- Kanan: Kaprodi -->
<div class="signature-right">
    @if(!empty($kaprodi['signatur_path']) && file_exists(public_path('storage/' . $kaprodi['signatur_path'])))
        <div class="signature-image">
            <img src="{{ asset('storage/' . $kaprodi['signatur_path']) }}" 
                 alt="Tanda Tangan" 
                 style="height: 90px; width: auto; object-fit: contain; margin-bottom: 5px;">
        </div>
    @else
        <div class="signature-line"></div>
    @endif
    <div class="signature-name">{{ $kaprodi['nama'] ?? 'Dr. Hj. Fatimah Azzahra, M.Pd.' }}</div>
    <div class="signature-title">{{ $kaprodi['jabatan'] ?? 'Kepala Program Studi' }}</div>
</div>
                </div>
            </div>
        </div>
    </div>

    <!-- HALAMAN 2 - LAPORAN PENCAPAIAN HAFALAN -->
    <div class="certificate-page">
        <div class="corner-top-right"></div>
        <div class="corner-bottom-left"></div>
        <div class="border-line"></div>
        
        <div class="page-content">
            <div>
                <div class="header-container">
                    <div class="univ-name">{{ $univ_name ?? 'UNIVERSITAS WAHIDIYAH' }}</div>
                    <div class="panitia">PANITIA PELAKSANA KKN TAHUN {{ $tahun ?? date('Y') }} – {{ $kabupaten ?? 'KABUPATEN MADIUN' }}</div>
                </div>

                <div class="cert-title">
                    <h1 style="font-size: 1.5rem;">LAPORAN PENCAPAIAN HAFALAN</h1>
                    <div class="nomor-sertifikat">Nomor: {{ $nomor_sertifikat }}</div>
                </div>

                <div style="margin-top: 5px; text-align: center;">
                    <div class="recipient-name" style="font-size: 1.3rem; border-bottom: none; display: inline-block; border-bottom: 2px solid #c9a03d; padding-bottom: 4px;">{{ $nama_mahasiswa }}</div>
                    <div class="nim">NIM: {{ $nim }}</div>
                    @if(isset($prodi) && $prodi != '-')
                    <div class="nim">Program Studi: {{ $prodi }}</div>
                    @endif
                </div>

                <div style="text-align: center;">
                    <div class="subtitle-page2" style="display: inline-block;">Capaian Hafalan Doa & Tahlil</div>
                </div>

                <div class="table-container">
                    <table class="achievement-table">
                        <thead>
                            <tr><th>Materi Hafalan</th><th>Status Kelancaran</th></tr>
                        </thead>
                        <tbody>
                            @foreach($capaian_hafalan as $item)
                            <tr>
                                <td>{{ $item['materi'] }}</td>
                                <td style="text-align: center;">
                                    @if($item['status'] == 'Sangat Lancar')
                                        <span class="status-sangat-lancar">SANGAT LANCAR</span>
                                    @elseif($item['status'] == 'Cukup Lancar')
                                        <span class="status-cukup-lancar">CUKUP LANCAR</span>
                                    @else
                                        <span class="status-belum-dinilai">{{ $item['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="predikat-box">
                    <span>Predikat Kelulusan: {{ $predikat }}</span>
                </div>

                <div class="catatan-footer">
                    <strong>Sangat Lancar</strong> : hafal di luar kepala, fasih, tajwid baik.<br>
                    <strong>Cukup Lancar</strong> : hafal dengan sedikit bimbingan, masih perlu penguatan.<br>
                    <strong>Total Nilai: {{ $total_nilai }}/{{ $max_nilai }} ({{ $persentase }}%)</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="action-buttons">
    <button onclick="window.print()">Download sebagai PDF</button>
</div>
</body>
</html>