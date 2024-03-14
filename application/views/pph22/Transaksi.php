 <!-- Begin Page Content -->
 <div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
     </div>

     <a class="btn btn-primary mb-3" href=" <?= base_url('pph22/tambahData'); ?>"><i class="fas fa-plus"></i> Tambah Data</a>

     <?= $this->session->flashdata('pesan') ?>

     <table class="table table-bondered table-striped mt-2">
         <tr>
             <th class="text-center">No</th>
             <th class="text-center">No Faktur</th>
             <th class="text-center">Tanggal Pembelian</th>
             <th class="text-center">Supplier</th>
             <th class="text-center">Nama Barang</th>
             <th class="text-center">Harga</th>
             <th class="text-center">PPH 22 (3%)</th>
             <th class="text-center">PPH 22 (1,5%)</th>
             <th class="text-center">Total</th>
         </tr>
         <?php $no = 1;
            foreach ($transaksi as $t) : ?>
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
                     <center>Rp. <?= number_format($t->pph, 0, ',', '.') ?></center>
                 </td>
                 <td>
                     <center>Rp. <?= number_format($t->npwp, 0, ',', '.') ?></center>
                 </td>
                 <td>
                     <center>Rp. <?= number_format($t->pph + $t->harga + $t->npwp, 0, ',', '.') ?></center>
                 </td>

             </tr>
         <?php endforeach; ?>
     </table>
 </div>
 </div>