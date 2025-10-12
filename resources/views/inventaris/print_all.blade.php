<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Inventaris</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px; /* Smaller font for print */
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Semua Data Inventaris</h1>

    <table>
        <thead>
            <tr>
                <th>Kode Inventaris</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Pemilik</th>
                <th>Tahun Beli</th>
                <th>Stok (HP)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventaris as $item)
                <tr>
                    <td>{{ $item->kode_inventaris }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori ?? 'N/A' }}</td>
                    <td>{{ $item->kondisi ?? 'N/A' }}</td>
                    <td>{{ $item->lokasi ?? 'N/A' }}</td>
                    <td>{{ $item->pemilik ?? 'N/A' }}</td>
                    <td>{{ $item->tahun_beli ?? 'N/A' }}</td>
                    <td>
                        @if ($item->kategori === 'habis_pakai')
                            {{ $item->stokHabisPakai->sum('jumlah_masuk') - $item->stokHabisPakai->sum('jumlah_keluar') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
