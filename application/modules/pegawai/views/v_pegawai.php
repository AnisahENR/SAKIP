<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
	<!-- ============================================================== -->
	<!-- Bread crumb and right sidebar toggle -->
	<!-- ============================================================== -->
	<div class="row page-titles">
		<div class="col-md-5 col-8 align-self-center">
			<h3 class="text-themecolor m-b-0 m-t-0">Daftar Pegawai</h3>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>">Beranda</a></li>
				<li class="breadcrumb-item active">Daftar Pegawai</li>
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
						<button class=" float-right btn btn-danger" type="button" data-toggle="collapse" id="btn-collapseAdd" aria-expanded="false" >Tambah Pegawai <i class="fa fa-chevron-down"></i> </button>      
					</div>
				</div>
				<div class="collapse" id="collapseadd">
					<div class="card card-body">
						<form id="form-add-admin">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label">Pilih SKPD</label>
										<select class="form-group select2" name="skpd_id" id="skpd_id" style="width: 100% !important" required>
											<!-- <option value="">Pilih SKPD</option> -->
                       <!--  <?php foreach ($list_skpd as $key => $value): ?>
                          <option value="<?= $value['id'] ?>"><?= $value['deskripsi'] ?></option>
                          <?php endforeach ?> -->
                      </select>
                  </div>
                  <div class="form-group">
                  	<label class="control-label">Pilih Bidang</label>
                  	<select class="form-group select2" name="bidang_skpd_id" id="bidang_skpd_id" style="width: 100% !important" required>
                  		<option value="">Pilih Bidang</option>
                  	</select>
                  </div>     
              </div>
              <div class="col-md-4">
              	<div class="form-group">
              		<label class="control-label">Nama</label>
              		<input type="text" name="nama" class="form-control" required>
              	</div>
              	<div class="form-group">
              		<label class="control-label">NIP</label>
              		<input type="text" name="nip" class="form-control" required>
              	</div>    
              </div>
              <div class="col-md-4">
              	<div class="form-group" id="form-check-user">
              		<label class="control-label" >Username</label>
              		<input type="text" name="username" id="username" class="form-control" required>
              		<div id="small-check"></div>
              	</div>
              	<div class="form-group">
              		<label class="control-label">Password</label>
              		<input type="password" name="password" class="form-control" required>
              	</div>    
              </div>
          </div>
          <div class="row">
          	<div class="col-md-12">
          		<div class="float-right">
          			<button type="button" class="btn waves-effect waves-light btn-danger" id="btn-batal-tambah"><i class="fa fa-times"></i> Batal</button>
          			<button type="submit" class="btn waves-effect waves-light btn-info"><i class="fa fa-check"></i> Tambahkan</button>
          		</div>
          	</div>
          </div>
          <div class="row">
          	<div class="col-md-2">
          		<div></div>
          	</div>
          </div>
      </form>
  </div>
</div>
<div class="card-body ribbon-content">
	<div class="table-responsive">
		<table id="tabel-admin" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th width="5%">No</th>
					<th>Nama Perangkat Daerah</th>
					<th>Username</th>
					<th>Nama Lengkap</th>
					<th class="text-center">Status</th>
					<th class="text-center">Aksi</th>
				</tr>
			</thead>
			<tbody id="list-data">
				<?php $no = 1;?>
				<tr>
					<th width="5%"><?php echo $no++;?></th>
					<th>BAPPEDA</th>
					<th>trisia</th>
					<th>Trisia Widya M</th>
					<th class="text-center"><button class="btn btn-rounded btn-success">
					Aktif </button></th>
					<th>
						<button type="button" class="btn btn-primary btn-circle"><i class="fa fa-check"></i> </button>
						<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-edit"></i> </button>
						<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button>
					</th>
				</tr>
				<tr>
					<th width="5%"><?php echo $no++;?></th>
					<th>BAPPEDA</th>
					<th>trisia</th>
					<th>Trisia Widya M</th>
					<th class="text-center"><button class="btn btn-rounded btn-danger">Tidak Aktif</button></th>
					<th> 

						<button type="button" class="btn btn-primary btn-circle"><i class="fa fa-check"></i> </button>
						<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-edit"></i> </button>
						<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i> </button></th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
</div>

<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
            <!-- ============================================================== -->