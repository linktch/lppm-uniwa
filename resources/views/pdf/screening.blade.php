<!DOCTYPE html>
<html>
<head>
    <title>Screening KKN</title>
    <style>
        /* Atur ukuran kertas F4 */
        @page {
            size: F4;
            margin: 1.2cm;
        }
        
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px; /* Sedikit diperkecil */
            line-height: 1.4;   /* Spasi lebih rapat */
            margin: 0;
            padding: 0;
        }

        .center { text-align: center; }

        .line {
            border-top: 1.5px solid black;
            margin: 8px 0 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table th, table td {
            border: 1px solid black;
            padding: 4px 6px; /* Padding lebih kecil */
        }

        .no-border td {
            border: none;
            padding: 2px 4px;
        }

        .ttd {
            margin-top: 12px;
        }
        
        /* Mengurangi margin judul */
        h3, h4 {
            margin: 5px 0;
        }
        
        /* Mengurangi jarak antar paragraf */
        p {
            margin: 6px 0;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="center">
    <h3>SURAT PERNYATAAN SCREENING KESEHATAN</h3>
    <h4>KULIAH KERJA NYATA (KKN)</h4>
</div>

<div class="line"></div>

{{-- PEMBUKA --}}
<p style="text-align: justify;">
    Dengan hormat,
    <br><br>
    Sehubungan dengan persyaratan peserta Kuliah Kerja Nyata (KKN) yang ditetapkan oleh Panitia KKN,
    dengan ini saya menyampaikan pernyataan dan informasi sebagai berikut:
</p>

<br>

{{-- DATA PRIBADI --}}
<b>DATA PRIBADI</b>

<table class="no-border" width="100%">
    <tr>
        <td width="25%">Nama</td>
        <td width="25%">: {{ ucwords(strtolower($nama)) }}</td>

        <td width="25%">Tanggal Lahir</td>
        <td width="25%">: {{ $tgl_lahir }}</td>
    </tr>

    <tr>
        <td width="25%">NIM</td>
        <td width="25%">: {{ $nim }}</td>

        <td width="25%">Tinggi Badan</td>
        <td width="25%">: {{ $tinggi }}</td>
    </tr>

    <tr>
        <td width="25%">Program Studi</td>
        <td width="25%">: {{ $prodi }}</td>

        <td width="25%">Berat Badan</td>
        <td width="25%">: {{ $berat }}</td>
    </tr>
</table>

<br>

{{-- TABEL SCREENING --}}
<b>HASIL SCREENING KESEHATAN</b>

<table>
    <tr>
        <th width="5%">No</th>
        <th width="45%">Pertanyaan</th>
        <th width="15%">Jawaban</th>
        <th width="35%">Keterangan</th>
    </tr>

    @foreach($data as $i => $d)
    <tr>
        <td align="center">{{ $i+1 }}</td>
        <td>{{ $d->question->pertanyaan }}</td>
        <td align="center">{{ $d->jawaban ? 'Ya' : 'Tidak' }}</td>
        <td>{{ $d->keterangan }}</td>
    </tr>
    @endforeach
</table>

<br>

{{-- PERNYATAAN --}}
<p style="text-align: justify;">
    Saya menyatakan dengan sebenar-benarnya bahwa seluruh pernyataan yang saya sampaikan dalam surat ini
    serta jawaban atas pertanyaan screening kesehatan telah diberikan secara jujur, lengkap, dan sesuai
    dengan kondisi yang sebenarnya.
</p>

<p style="text-align: justify;">
    Apabila di kemudian hari ditemukan bahwa pernyataan atau jawaban yang saya berikan tidak sesuai dengan
    kondisi yang sebenarnya atau terdapat penyembunyian fakta yang seharusnya disampaikan,
    maka saya bersedia menerima sanksi sesuai dengan peraturan yang berlaku.
</p>

<br>

<p>Kediri, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

{{-- TTD --}}
<table class="no-border ttd">
    <tr>
        <td width="50%" align="center">
            Mengetahui,<br>
            Petugas Klinik / Puskesmas<br>
            <small>(Wajib tanda tangan & stempel)</small>

            <br><br><br><br>

            (_________________________)
        </td>

        <td width="50%" align="center">
            Yang Menyatakan,<br>
            Mahasiswa

            <br><br><br><br>

            ({{ ucwords(strtolower($nama)) }})
        </td>
    </tr>
</table>

<script>
    (function() {
        // Ambil data nama dan NIM dari Blade
        let nama = "{{ addslashes(ucwords(strtolower($nama))) }}";
        let nim = "{{ addslashes($nim) }}";
        
        // Format nama file: nama-nim-screening
        // Ganti spasi dengan underscore atau strip spasi (opsional)
        let fileName = nama.replace(/\s+/g, '_') + '-' + nim + '-screening.pdf';
        
        // Siapkan event listener sebelum print
        let beforePrint = function() {
            // Ubah title dokumen sementara agar berpengaruh pada nama file PDF
            document.title = fileName.replace('.pdf', '');
        };
        
        let afterPrint = function() {
            // Kembalikan title ke semula (opsional)
            setTimeout(function() {
                document.title = "Screening KKN";
            }, 500);
        };
        
        // Daftarkan event untuk sebelum dan sesudah print
        if (window.matchMedia) {
            let mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    beforePrint();
                } else {
                    afterPrint();
                }
            });
        }
        
        // Fallback: tangkap event beforeprint dan afterprint (didukung banyak browser)
        window.addEventListener('beforeprint', beforePrint);
        window.addEventListener('afterprint', afterPrint);
        
        // Untuk browser yang tidak mendukung event di atas,
        // set title langsung dari awal (masih membantu sebagian kasus)
        document.title = fileName.replace('.pdf', '');
    })();
</script>

</body>
</html>