 <!-- Begin Page Content -->
 <div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
     </div>


     <?= $this->session->flashdata('no_faktur_exists') ?>

     <div class="card" style="width: 60%; margin-bottom: 100px">
         <div class="card-body">


             <form method="POST" action="<?= base_url('ppn/tambah') ?>" id="myForm" onsubmit="return confirmSubmit();">

                 <div class="form-group">
                     <label>No Faktur</label>
                     <input type="text" name="no_faktur" class="form-control">
                     <?php echo form_error('no_faktur', '<div class="text-small text-danger"></div>') ?>
                 </div>
                 <div class="form-group">
                     <label>Tanggal Pembelian</label>
                     <input type="date" name="tanggal_pembelian" class="form-control">
                     <?php echo form_error('tanggal_pembelian', '<div class="text-small text-danger"></div>') ?>
                 </div>
                 <div class="form-group">
                     <label>Supplier</label>
                     <input type="text" name="supplier" class="form-control">
                     <?php echo form_error('supplier', '<div class="text-small text-danger"></div>') ?>
                 </div>
                 <div class="form-group">
                     <label>Nama Barang</label>
                     <input type="text" name="nama_barang" class="form-control">
                     <?php echo form_error('nama_barang', '<div class="text-small text-danger"></div>') ?>
                 </div>
                 <div class="form-group">
                     <label>Harga</label>
                     <input type="text" name="harga" class="form-control">
                     <?php echo form_error('harga', '<div class="text-small text-danger"></div>') ?>
                 </div>

                 <button type="submit" class="btn btn-success">Submit</button>

             </form>


         </div>
     </div>
     <script>
         function confirmSubmit() {
             var confirmation = confirm("Apakah Anda yakin ingin mengirimkan formulir?");
             return confirmation;
         }
     </script>


 </div>
 </div>