<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 5mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* General table styling for tables without borders */
        table.no-border, table.no-border th, table.no-border td {
            border: none;
        }

        /* Table styling for tables with borders */
        table.with-border, table.with-border th, table.with-border td {
            border: 1px solid black;
        }

        th, td {
            padding: 4px;
            text-align: left;
            font-size: 13px; /* Menambah ukuran font tabel */
        }

        .header {
            border: none;
        }

        .header th, .header td {
            border: none;
            padding: 2px;
        }

        .header td {
            vertical-align: top;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-top: 5px;
        }

        h1 {
            font-size: 18px; /* Menambah ukuran font h1 */
            margin: 2px 0;
        }

        h2 {
            font-size: 14px; /* Menambah ukuran font h2 */
            margin: 2px 0;
        }

        p {
            font-size: 13px; /* Menambah ukuran font p */
            margin: 2px 0;
        }

        h4 {
            font-size: 13px; /* Menambah ukuran font h4 */
            margin: 5px 0;
        }

        .date-location {
            font-size: 13px; /* Menambah ukuran font untuk lokasi dan tanggal */
            text-align: right;
            padding-right: 10px;
            margin: 0;
        }

        .signature-section {
            margin-top: 20px; /* Memberi jarak antara tabel dan signature section */
        }

        .signature-table {
            width: 100%;
            border: none;
            font-size: 14px;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 5px;
            border: none;
            width: 33%; /* Adjusted width for better alignment */
        }

        .signature-table .signature img {
            max-height: 60px; /* Ukuran tinggi signature */
            height: auto;
        }

        .signature-table .name {
            font-size: 15px; /* Menambah ukuran font nama */
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <table class="header">
            <tr>
                <td style="width: 120px; vertical-align: top;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('backend/assets/images/logo-bps.png'))) }}" alt="BPS Logo">
                </td>
                <td style="vertical-align: top; padding-left: 5px;">
                    <h1>BADAN PUSAT STATISTIK</h1>
                    <h1>Kota Jakarta Utara</h1>
                    <p style="font-size: 12px">Jl. Berdikari No.1, Koja, Jakarta Utara 14230</p>
                    <p style="font-size: 12px">Telp/Fax: (021) 4353936</p>
                </td>
            </tr>
        </table>

        <br><br>
        <h4>Nomor Permintaan : {{ $permintaan->no_permintaan }}</h4>

        <br>
        <h4 style="text-align: center;">TANDA TERIMA</h4>
        <br>

        <!-- Table with borders -->
        <table class="with-border">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 25%;">Kode Barang</th>
                    <th style="width: 40%;">Nama Barang</th>
                    <th style="width: 10%; text-align: center;">Kuantitas</th>
                    <th style="width: 20%;">Satuan</th>
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
                        <td>{{ $item->barang->satuan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <br><br>

        <!-- Signature Section -->
        <table class="no-border">
            <tr>
                <td colspan="2">
                    <div class="signature-section">
                        <table class="signature-table">
                            <tr>
                                <td class="signature">
                                    <br>
                                    <p>Yang Menyerahkan</p>
                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('backend/assets/images/users/ttd_4.png'))) }}" alt="Signature Juniaty Pardede">
                                    <div class="name" style="font-size: 14px;">Juniaty Pardede, A.Md</div>
                                </td>
                                <td class="signature">
                                    <br>
                                    <p>Yang Menerima</p>
                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('backend/assets/images/users/ttd_' . auth()->user()->id . '.png'))) }}" alt="Signature {{ auth()->user()->name }}">
                                    <div class="name" style="font-size: 14px;">{{ $pilihan->first()->created_by }}</div>
                                </td>
                                <td class="signature">
                                    <div class="date-location" style="text-align: center;">
                                        Jakarta, {{ \Carbon\Carbon::parse($pilihan->first()->date)->locale('id')->translatedFormat('d F Y') }}
                                    </div>
                                    <p>Mengetahui/Menyetujui</p>
                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('backend/assets/images/users/ttd_2.png'))) }}" alt="Signature Mohamad Rudiansyah Oktavan">
                                    <div class="name" style="font-size: 14px;">Mohamad Rudiansyah Oktavan, S.E.</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
