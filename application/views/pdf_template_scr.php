<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /* Tambahkan CSS ini di dalam tag <style> */
        table {
            width: 120%;
            /* Lebarkan tabel 10% dari lebar layar */
        }

        th,
        td {
            padding: 10px 15px;
            /* Tambahkan padding horizontal lebih besar */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Style untuk mencetak */
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
                /* Sesuaikan ukuran font sesuai kebutuhan cetak */
            }

            h1 {
                text-align: center;
                font-size: 18px;
                /* Sesuaikan ukuran font sesuai kebutuhan cetak */
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                /* Garis hitam untuk batas tabel */
                padding: 6px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            tr:hover {
                background-color: transparent;
            }

            /* Sembunyikan gambar saat mencetak */
            img {
                display: none;
            }
        }
    </style>
</head>

<body>
    <img src="assets/img/icon.jpg" style="position: absolute; width: 100px; height: auto;">
    <h1>Laporan PPN</h1>

    <br>
    <hr>
    <br>
    <table>
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Tanggal Pembelian</th>
                <th>Supplier</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Tarif PPN</th>
                <th>PPN</th>
                <th>Total</th>
                <!-- Tambahkan kolom-kolom lainnya sesuai dengan data Anda -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($catakLaporan as $row) : ?>
                <tr>
                    <td><?= $row->no_faktur; ?></td>
                    <td><?= date('y-m-d', strtotime($row->tanggal_pembelian)) ?></td>
                    <td><?= $row->supplier; ?></td>
                    <td><?= $row->nama_barang; ?></td>
                    <td>Rp. <?= number_format($row->harga, 0, ',', '.') ?></td>
                    <td><?= number_format($row->t_ppn * 100) . '%' ?></td>
                    <td>Rp. <?= number_format($row->ppn, 0, ',', '.') ?></td>
                    <td>Rp. <?= number_format($row->ppn + $row->harga, 0, ',', '.') ?></td>
                    <!-- Tambahkan kolom-kolom lainnya sesuai dengan data Anda -->
                </tr>

            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Jumlah</strong></td>
                <td>
                    <?php
                    $totalPPN = 0;
                    foreach ($catakLaporan as $row) {
                        $totalPPN += $row->harga;
                    }
                    echo "Rp" . number_format($totalPPN, 0, ',', '.');
                    ?>
                </td>
                <td>
                <td>
                    <?php
                    $totalPPN = 0;
                    foreach ($catakLaporan as $row) {
                        $totalPPN += $row->ppn;
                    }
                    echo "Rp" . number_format($totalPPN, 0, ',', '.');
                    ?>
                </td>
                <td>
                    <?php
                    $totalKeseluruhan = 0;
                    foreach ($catakLaporan as $row) {
                        $totalKeseluruhan += $row->harga + $row->ppn;
                    }
                    echo "Rp" . number_format($totalKeseluruhan, 0, ',', '.');
                    ?>
                </td>
                <tb></tb>
            </tr>
        </tbody>
    </table>
</body>

</html>