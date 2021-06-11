            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
              <!-- ============================================================== -->
              <!-- Bread crumb and right sidebar toggle -->
              <!-- ============================================================== -->
              <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                  <h3 class="text-themecolor m-b-0 m-t-0">Form Tambah Data PK dan IKI</h3>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('beranda') ?>">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('Manajemen_sakip') ?>">Manajemen Sakip</a></li>
                    <li class="breadcrumb-item active">Tambah Data Sakip</li>
                  </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                  <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                      <div class="chart-text m-r-10">
                        <h6 class="m-b-0"><small>THIS MONTH</small></h6>
                        <h4 class="m-t-0 text-info">$58,356</h4>
                      </div>
                      <div class="spark-chart">
                        <div id="monthchart"></div>
                      </div>
                    </div>
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                      <div class="chart-text m-r-10">
                        <h6 class="m-b-0"><small>LAST MONTH</small></h6>
                        <h4 class="m-t-0 text-primary">$48,356</h4>
                      </div>
                      <div class="spark-chart">
                        <div id="lastmonthchart"></div>
                      </div>
                    </div>
                    <div class="">
                      <button class="right-side-toggle waves-effect waves-light btn-success btn btn-circle btn-sm pull-right m-l-10"><i
                        class="ti-settings text-white"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <!-- Validation wizard -->
                <div class="row" id="validation">
                  <div class="col-12">
                    <div class="card wizard-content">
                      <div class="card-body">
                        <h4 class="card-title">Form Tambah Data Sakip</h4>
                        <h6 class="card-subtitle">Terdiri dari input data PK dan data IKI</h6>
                        <form action="#" class="validation-wizard wizard-circle">
                          <!-- Step 1 -->
                          <h6>Identitas Pegawai</h6>
                          <section>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="wfirstName2"> Nama :
                                    <span class="danger">*</span>
                                  </label>
                                  <input type="text" class="form-control" id="nama" name="nama"> </div>
                                </div>
                                <input type="hidden" name="id_pegawai">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="jabatan"> Jabatan :
                                      <span class="danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan"> </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="perangkat_daerah"> Perangkat Daerah :
                                        <span class="danger">*</span>
                                      </label>
                                      <input type="email" class="form-control" id="perangkat_daerah" name="perangkat_daerah"> </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="wphoneNumber2">Golongan :</label>
                                        <input type="tel" class="form-control" id="golongan"> </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="wlocation2"> Fungsi :
                                            <span class="danger">*</span>
                                          </label>
                                          <select class="custom-select form-control" id="fungsi" name="fungsi">
                                            <option value="">Indonesia</option>
                                            <option value="India">India</option>
                                            <option value="USA">USA</option>
                                            <option value="Dubai">Dubai</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="wphoneNumber2">Tugas :</label>
                                          <input type="tel" class="form-control" id="tugas"> </div>
                                        </div>
                                      </div>
                                    </section>
                                    <!-- Step 2 -->
                                    <h6>Sasaran & Program</h6>
                                    <section>
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="jobTitle2">Sasaran Strategis :</label>
                                            <textarea type="text" class="form-control" id="sasaran">
                                            </textarea>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="webUrl3">Indikator Kinerja :</label>
                                            <textarea type="url" class="form-control" id="indikator_kinerja" name="indikator_kinerja"></textarea> </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="shortDescription3">Satuan :</label>
                                              <input type="text" name="satuan" class="form-control">

                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="shortDescription3">Target :</label>
                                              <input type="text" name="target" class="form-control">
                                            </div>
                                          </div>
                                          <div class="col-md-12">
                                            <div class="form-group">
                                              <label for="shortDescription3">Program :</label>
                                              <textarea type="text" name="target" class="form-control">
                                              </textarea>
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="shortDescription3">Anggaran :</label>
                                              <input type="number" name="target" class="form-control">  
                                            </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="shortDescription3">Keterangan :</label>
                                              <input type="number" name="target" class="form-control">  
                                            </div>
                                          </div>
                                        </div>
                                      </section>
                                      <!-- Step 3 -->
                                      <h6>Sub Kegiatan</h6>
                                      <section>
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="form-group">
                                              <label for="wint1">Sub Kegiatan :</label>
                                              <textarea type="text" class="form-control" id="sub_kegiatan"></textarea> </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">                       
                                                <label for="wintType1">Anggaran :</label>
                                                <select class="custom-select form-control" id="sub_kegiatan" data-placeholder="Type to search cities" name="sub_kegiatan">
                                                  <option value="Banquet">Easy</option>
                                                  <option value="Fund Raiser">Difficult</option>
                                                  <option value="Dinner Party">Hard</option>
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label for="wintType1">Keterangan :</label>
                                                <select class="custom-select form-control" id="keterangan_sub" data-placeholder="Type to search cities" name="keterangan_sub">
                                                  <option value="Banquet">Easy</option>
                                                  <option value="Fund Raiser">Difficult</option>
                                                  <option value="Dinner Party">Hard</option>
                                                </select>
                                              </div>
                                            </div>
                                          </div>
                                          <button class="btn btn-success" type="button" onclick="form_sakip();"><i class="fa fa-plus"></i></button>
                                          <div id="form_sakip"></div>
                                        </section>
                                        <!-- Step 4 -->
                                        <!--  <h6>Step 4</h6> -->
                                       <!--  <section>
                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label for="behName1">Behaviour :</label>
                                                <input type="text" class="form-control required" id="behName1">
                                              </div>
                                              <div class="form-group">
                                                <label for="participants1">Confidance</label>
                                                <input type="text" class="form-control required" id="participants1">
                                              </div>
                                              <div class="form-group">
                                                <label for="participants1">Result</label>
                                                <select class="custom-select form-control required" id="participants1" name="location">
                                                  <option value="">Select Result</option>
                                                  <option value="Selected">Selected</option>
                                                  <option value="Rejected">Rejected</option>
                                                  <option value="Call Second-time">Call Second-time</option>
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label for="decisions1">Comments</label>
                                                <textarea name="decisions" id="decisions1" rows="4" class="form-control"></textarea>
                                              </div>
                                              <div class="form-group">
                                                <label>Rate Interviwer :</label>
                                                <div class="c-inputs-stacked">
                                                  <label class="inline custom-control custom-checkbox block">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label ml-0">1 star</span>
                                                  </label>
                                                  <label class="inline custom-control custom-checkbox block">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label ml-0">2 star</span>
                                                  </label>
                                                  <label class="inline custom-control custom-checkbox block">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label ml-0">3 star</span>
                                                  </label>
                                                  <label class="inline custom-control custom-checkbox block">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label ml-0">4 star</span>
                                                  </label>
                                                  <label class="inline custom-control custom-checkbox block">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label ml-0">5 star</span>
                                                  </label>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </section> -->
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- vertical wizard -->
                              
                              

                              <!-- ============================================================== -->
                              <!-- End Page Content -->
                              <!-- ============================================================== -->
                              
                              <!-- ============================================================== -->
                              <!-- End Container fluid  -->
            <!-- ============================================================== -->