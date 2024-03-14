 <!-- Begin Page Content -->
 <div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
         <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
     </div>



     <?= $this->session->flashdata('message') ?>
     <?php foreach ($ppn as $p) : ?>

         <?php
            $value_ppn = $p['ppn']; // Mengakses nilai 'nama_ppn'
            $value_dpp = $p['dpp']; // Mengakses nilai 'nilai_dpp'

            ?>


     <?php endforeach; ?>
     <div class="card" style="width: 60%; margin-bottom: 100px">
         <div class="card-body">


             <form method="POST" action="<?= base_url('PPN/updateSettings') ?>" id="myForm" onsubmit="return confirmSubmit();">

                 <div class="form-group">
                     <label>PPN</label>
                     <input type="text" name="ppn" class="form-control" value="<?= $value_ppn  * 100 ?>">
                     <?php echo form_error('ppn', '<div class="text-small text-danger"></div>') ?>
                 </div>

                 <div class="form-group">
                     <label>DPP</label>
                     <input type="text" name="dpp" class="form-control" value="<?= $value_dpp ?>">
                     <?php echo form_error('dpp', '<div class="text-small text-danger"></div>') ?>
                 </div>

                 <button type="submit" class="btn btn-success">Submit</button>

             </form>


         </div>
     </div>
     <script>
         function confirmSubmit() {
             var confirmation = confirm("Apakah Anda yakin ingin Edit data PPN ?");
             return confirmation;
         }
     </script>


 </div>
 </div>