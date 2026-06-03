<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export KTP Valid - KKN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
        }
        
        .header h3 {
            margin: 0;
            font-size: 14px;
        }
        
        /* 3 kolom dengan float */
        .col-left {
            float: left;
            width: 32%;
        }

        .col-center {
            float: left;
            width: 32%;
            margin-left: 2%;
        }
        
        .col-right {
            float: right;
            width: 32%;
        }
        
        .clearfix {
            clear: both;
        }
        
        .ktp-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            page-break-inside: avoid;
        }
        
        .ktp-name {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .ktp-image {
            text-align: center;
        }
        
        .ktp-image img {
            max-width: 100%;
            max-height: 200px;
            border: 1px solid #ccc;
        }
        
        .footer {
            text-align: center;
            font-size: 8px;
            margin-top: 15px;
            padding-top: 5px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>DAFTAR PESERTA KKN - KTP VALID</h3>
        <p>{{ $kegiatan_nama }} | {{ $periode_nama }} | Total: {{ $total_valid }}</p>
    </div>
    
    @foreach($files as $index => $file)

        @if($index % 3 == 0)
        <div class="col-left">
            <div class="ktp-item">
                <div class="ktp-name">{{ $index + 1 }}. {{ $file->nama }}</div>
                <div class="ktp-image">
                    @if($file->foto_ktp_base64)
                        <img src="{{ $file->foto_ktp_base64 }}" alt="KTP">
                    @else
                        <div style="color:#999; padding:20px; text-align:center;">No Image</div>
                    @endif
                </div>
            </div>
        </div>

        @elseif($index % 3 == 1)
        <div class="col-center">
            <div class="ktp-item">
                <div class="ktp-name">{{ $index + 1 }}. {{ $file->nama }}</div>
                <div class="ktp-image">
                    @if($file->foto_ktp_base64)
                        <img src="{{ $file->foto_ktp_base64 }}" alt="KTP">
                    @else
                        <div style="color:#999; padding:20px; text-align:center;">No Image</div>
                    @endif
                </div>
            </div>
        </div>

        @else
        <div class="col-right">
            <div class="ktp-item">
                <div class="ktp-name">{{ $index + 1 }}. {{ $file->nama }}</div>
                <div class="ktp-image">
                    @if($file->foto_ktp_base64)
                        <img src="{{ $file->foto_ktp_base64 }}" alt="KTP">
                    @else
                        <div style="color:#999; padding:20px; text-align:center;">No Image</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        @endif

    @endforeach
    
    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>