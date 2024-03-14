 <!-- Begin Page Content -->
 <div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
     </div>

     <div class="card">
         <div class="card-header bg-primary text-white">
             Laporan Data PPN
         </div>
         <div class="card-body">


             <form class="form-inline" action="<?= base_url('ppn'); ?>" method="GET">
                 <div class="form-group mb-2">
                     <input type="text" class="form-control bg-light border-0 small ml-2" placeholder="Search for..." name="search" aria-label="Search" aria-describedby="basic-addon2" id="search" value="<?= isset($search) ? $search : ''; ?>">
                 </div>

                 <div class="form-group mb-2 ml-3">
                     <label for="startDate" class="">Tanggal Awal : </label>
                     <input type="date" class="form-control ml-2" name="tanggal_mulai" id="tanggal_mulai" value="<?= isset($startDay) ? $startDay : ''; ?>">
                 </div>
                 <div class="form-group mb-2 ml-3">
                     <label for="endDate">Tanggal Akhir : </label>
                     <input type="date" class="form-control ml-2" name="tanggal_selesai" id="tanggal_selesai" value="<?= isset($endDay) ? $endDay : ''; ?>">
                 </div>

                 <!-- Rest of your code -->

                 <?php
                    $search = $this->input->Get('search');
                    $startDay = $this->input->Get('tanggal_mulai');
                    $endDay = $this->input->Get('tanggal_selesai');
                    ?>

                 <button type="submit" class="btn btn-primary mb-2 ml-auto"><i class="fas fa-eye"></i> Tampilkan Data</button>
                 <!-- Cetak Laporan Berdasarkan Pencarian -->
                 <?php if (!empty($search)) { ?>
                     <a href="<?= base_url('ppn/cetakLaporanscr?search=' . urlencode($search) . '&tanggal_mulai=' . '&tanggal_selesai='); ?>" class="btn btn-success mb-2 ml-3"><i class="fas fa-print"></i> Cetak Laporan</a>
                 <?php } elseif (!empty($startDay) && !empty($endDay)) { ?>
                     <a href="<?= base_url('ppn/cetakLaporan?search=' . '&tanggal_mulai=' . $startDay . '&tanggal_selesai=' . $endDay); ?>" class="btn btn-success mb-2 ml-3"><i class="fas fa-print"></i> Cetak Laporan</a>
                 <?php } else { ?>
                     <button type="button" class="btn btn-success mb-2 ml-3" data-toggle="modal" data-target="#"><i class="fas fa-print"></i> Cetak Laporan</button>
                 <?php } ?>
             </form>
         </div>
     </div>

     <?php if (!empty($startDay)) { ?>
         <div class="alert alert-info">
             Menampilkan Data PPN Tanggal: <span class="font-weight-bold"><?= date('d-m-Y', strtotime($startDay)); ?></span> Sampai - Tanggal: <span class="font-weight-bold"><?= date('d-m-Y', strtotime($endDay)); ?></span>
         </div>
     <?php } elseif (!empty($search)) { ?>
         <div class="alert alert-info">
             Menampilkan Data PPN Pencarian: <span class="font-weight-bold"><?= $search; ?></span>
         </div>
     <?php } else { ?>
         <div class="alert alert-info">
             Menampilkan Data PPN Tanggal: <span class="font-weight-bold"><?= date('d-m-Y'); ?></span>
         </div>
     <?php } ?>

     <?php
        $jml_data = count($ppn);
        if ($jml_data > 0) { ?>

         <table class="table table-bordered table striped">
             <tr>
                 <th class="text-center">No</th>
                 <th class="text-center">No Faktur</th>
                 <th class="text-center">Tanggal Pembelian</th>
                 <th class="text-center">Supplier</th>
                 <th class="text-center">Nama Barang</th>
                 <th class="text-center">Harga</th>
                 <th class="text-center">Tarif PPN</th>
                 <th class="text-center">PPN </th>
                 <th class="text-center">Total</th>
             </tr>

             <?php $no = 1;
                foreach ($ppn as $t) : ?>

                 <tr>
                     <td>
                         <center><?= $no++ ?></center>
                     </td>
                     <td>
                         <center><?= $t->no_faktur ?></center>
                     </td>
                     <td>
                         <center><?= date('d-m-y', strtotime($t->tanggal_pembelian)) ?></center>
                     </td>
                     <td>
                         <center><?= $t->supplier ?></center>
                     </td>
                     <td>
                         <center><?= $t->nama_barang ?></center>
                     </td>
                     <td>
                         <center>Rp. <?= number_format($t->harga, 0, ',', '.') ?></center>
                     </td>
                     <td>
                         <center><?= number_format($t->t_ppn * 100) . '%' ?></center>
                     </td>
                     <td>
                         <center>Rp. <?= number_format($t->ppn, 0, ',', '.') ?></center>
                     </td>
                     <td>
                         <center>Rp. <?= number_format($t->ppn + $t->harga, 0, ',', '.') ?></center>
                     </td>

                 </tr>
             <?php endforeach; ?>
         </table>

     <?php } else { ?>
         <span class="badge badge-denger"><i class="fas fa-info-circle"></i>
             Data Masih Kosong, silahkan tambah data perhitungan pajak terlebih dahulu pada bulan dan tahun yang Anda pilih!</span>
     <?php } ?>


 </div>
 </div>