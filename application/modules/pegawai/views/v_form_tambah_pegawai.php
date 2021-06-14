<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
	<!-- ============================================================== -->
	<!-- Bread crumb and right sidebar toggle -->
	<!-- ============================================================== -->
	<div class="row page-titles">
		<div class="col-md-5 col-8 align-self-center">
			<h3 class="text-themecolor m-b-0 m-t-0">Formulir Tambah Pegawai</h3>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>">Beranda</a></li>
				<li class="breadcrumb-item">Daftar Pegawai</li>
				<li class="breadcrumb-item active">Form Tambah Pegawai</li>
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
				<div class="ribbon ribbon-wrapper ribbon-danger" style="font-family: sans-serif;"> Tambah Pegawai</div>
				<form id="form-add-admin">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Nama</label>
								<input type="text" name="nama_pegawai" class="form-control">			
							</div>
							<div class="form-group">
								<label class="control-label">Golongan</label>
								<select class="form-control">
									<option>Bappeda</option>

									<option>Kominfo</option>
								</select>
							</div>     
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Perangkat Daerah</label>

								<select class="form-control">
									<option>Bappeda</option>

									<option>Kominfo</option>
								</select>
							</div>

							<div class="form-group" id="form-check-user">
								<label class="control-label" >NIP</label>
								<input type="text" name="username" id="username" class="form-control">
								<div id="small-check"></div>
							</div>   
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Jabatan</label>
								<select class="form-control">
									<option>Bappeda</option>

									<option>Kominfo</option>
								</select>
							</div> 
							<div class="form-group">
								<label class="control-label">Tugas</label>
								<textarea type="password" name="password" class="form-control"></textarea>

							</div>    
						</div>
						<div class="col-md-10">
							<div class="form-group">
								<label class="control-label">Fungsi</label>
								<textarea type="text" name="fungsi" class="form-control"></textarea>
							</div> 
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-primary" style="margin-top:3em;" onclick="form_pegawai()">Tambah</button>
						</div>
					</div>
					<div id="form_pegawai"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="float-right">
								<button type="submit" class="btn waves-effect waves-light btn-info"><i class="fa fa-check"></i> Submit</button>
							</div>
						</div>
					</div>
				</form>
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