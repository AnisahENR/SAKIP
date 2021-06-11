<div class="container-fluid">
  <!-- ============================================================== -->
  <!-- Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
      <h3 class="text-themecolor m-b-0 m-t-0">Beranda</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>">Beranda</a></li>
        <li class="breadcrumb-item">Manajemen Data Sakip</li>
        
      </ol>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Start Page Content -->
  <!-- ============================================================== -->
  <div class="row">
    <div class="col-md-12">
      <div class="card ribbon-wrapper">
        <div class="ribbon ribbon-corner ribbon-danger"><i class="fa fa-list-alt"></i></div>
        <div class="card-header row">
          <h2 class="card-title col-md-8"><b>Daftar Pegawai</b></h2>
          <div class="col-md-4">
           <!--  <button class=" float-right btn btn-danger" type="button" data-toggle="collapse" id="btn-collapseAdd" aria-expanded="false" >Tambah Data <i class="fa fa-chevron-down"></i> </button>   -->    
         </div>
       </div>
       
       <div class="card-body ribbon-content">
        <div class="table-responsive">
          <table id="tabel-admin" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody id="list-data">
              <?php $no = 1;?>
              <tr>
                <th width="5%"><?php echo $no++;?></th>
                <th>Trisia Widya M</th>
                <th>Kepala Badan</th>
                <th class="text-center">
                  <a href="<?=base_url();?>Manajemen_sakip/detail_sakip"><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-list"></i> </button></a>
                </th>
              </tr>
              <tr>
                <th width="5%"><?php echo $no++;?></th>
                <th>Trisia Widya M</th>
                <th>Kepala Badan</th>
                <th class="text-center">
                  <a href="<?=base_url('Manajemen_sakip/tambah_sakip');?>"><button type="button" class="btn btn-primary btn-circle"><i class="fa fa-plus"></i> </button></a>
               </th>
             </tr>
           </tbody>
         </table>
       </div>
     </div>
   </div>
 </div>
</div>    
</div>
<!-- modal -->
<div class="modal fade " id="modal-detail-admin" >
  <div class="modal-dialog " role="document">
    <div class="modal-content   modal-md">
      <div class="modal-header ribbon-wrapper">
        <div class="ribbon ribbon-corner ribbon-info"><i class="fa fa-keyboard"></i></div>
        <!-- <h5 class="modal-title" id="exampleModalLabel">Edit Master Author</h5> -->

        <h2 class="modal-title col-md-8"><b>Form Edit Data Admin </b></h2>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->


      </div>
      <form id="form-update-admin">
        <input type="hidden" name="kode_spv" id="kode_spv">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Bidang</label>
                <select class="form-control select2" name="bidang_skpd_id" id="bidang_skpd" style="width: 100% !important; ">
                </select>
              </div>
              <div class="form-group">
                <label class="control-label">Nama</label>
                <input type="text" class="form-control" name="nama" id="nama">
              </div>
              <div class="form-group">
                <label class="control-label">NIP</label>
                <input type="text" class="form-control" name="nip" id="nip">
              </div> 
            </div>
          </div>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>