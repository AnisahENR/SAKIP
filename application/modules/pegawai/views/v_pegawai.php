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
						<a href="<?=base_url('Pegawai/tambah_pegawai');?>"><button class=" float-right btn btn-danger" type="button">Tambah Pegawai <i class="fa fa-plus"></i> </button> </a>     
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