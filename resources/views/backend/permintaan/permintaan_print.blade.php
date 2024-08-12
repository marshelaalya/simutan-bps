<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .header {
            border: none;
        }
        .header th, .header td {
            border: none;
            padding: 4px;
        }
        .header td {
            vertical-align: top;
        }
        .header img {
            width: 130px;
            height: auto;
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
        }
        .signature-section {
            margin-top: 0; /* Reduced margin-top */
        }
        .signature-table {
            width: 100%;
            border: none; /* Remove border from the entire table */
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 20px;
            border: none; /* Remove border from individual cells */
            width: 33%; /* Ensure equal width for all cells */
        }
        .signature-table .signature {
            height: 60px;
        }
        .signature-table .name {
            font-size: 16px;
        }
        .date-location {
            font-size: 16px;
            text-align: left;
            padding-left: 20px;
            /* margin-bottom: 1px; Reduced margin-bottom */
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td style="width: 120px; vertical-align: top;">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('backend/assets/images/logo-bps.png'))) }}" alt="BPS Logo">
            </td>
            <td style="vertical-align: top; padding-left: 25px;">
                <h1 style="margin: 0;">BADAN PUSAT STATISTIK</h1>
                <h3 style="margin: 0;">Kota Jakarta Utara</h3>
                <p style="margin: 0;">Jl. Berdikari No.1, Koja, Jakarta Utara 14230</p>
                <p style="margin: 0;">Telp/Fax: (021) 4353936</p>
            </td>
        </tr>
    </table>

    <br>
    <h4>Nomor Permintaan : {{ $permintaan->no_permintaan }}</h4>

    <h4 style="text-align: center;">TANDA TERIMA</h4>

    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 25%;">Kode Barang</th>
                <th style="width: 50%;">Nama Barang</th>
                <th style="width: 10%; text-align: right;">Kuantitas</th>
                <th style="width: 10%;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach($pilihan as $item)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $item->barang->kode }}</td>
                    <td>{{ $item->barang->nama }}</td>
                    <td style="text-align: right;">{{ $item->req_qty }}</td>
                    <td>{{ $item->barang->satuan->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <br><br><br>
    
    <div class="date-location">
        Jakarta, {{ \Carbon\Carbon::parse($pilihan->first()->date)->translatedFormat('d F Y') }}
    </div>
    
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature" style="text-align: left;">
                    <p>Yang Menyerahkan</p>
                    <br><br><br>
                    <div class="name">Juniaty Pardede, A.Md</div>
                </td>
                <td class="signature">
                    <p>Yang Menerima</p>
                    <br><br><br>
                    <div class="name">{{ $pilihan->first()->created_by }}</div>
                </td>
                <td class="signature" style="text-align: right;">
                    <p>Mengetahui/Menyetujui</p>
                    <br><br><br>
                    <div class="name">Mohamad Rudiansyah Oktavan, S.E.</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
