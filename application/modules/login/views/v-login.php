<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <title>E-BappedaTop - Login</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>_assets/material_pro/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="<?=base_url()?>_assets/material_pro/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>_assets/material_pro/material/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="<?=base_url()?>_assets/material_pro/material/css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="d-flex align-content-center align-items-center flex-wrap login-register" style="background-image:url(<?=base_url()?>_assets/material_pro/assets/images/background/login-register.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" id="loginform" action="<?=base_url('beranda')?>" method="post">
                        <a href="javascript:void(0)" class="text-center db">
                            <!-- <img src="<?//=base_url()?>_assets/material_pro/assets/images/logo-icon.png" alt="SILAT" /> -->
                            <br/>
                          <!--   <img src="<?//=base_url()?>_assets/material_pro/assets/images/logo-text.png" alt="SILAT" /> -->
                        </a>
                        <h3 class="box-title m-b-20">Masuk</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Username" name="username"> </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" placeholder="Password" name="password"> </div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex no-block align-items-center">
                                <div class="checkbox checkbox-primary p-t-0">
                                    <input id="checkbox-signup" type="checkbox" name="remember">
                                    <label for="checkbox-signup"> Ingat saya </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Masuk</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/popper/popper.min.js"></script>
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?=base_url()?>_assets/material_pro/material/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?=base_url()?>_assets/material_pro/material/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?=base_url()?>_assets/material_pro/material/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="<?=base_url()?>_assets/material_pro/material/js/custom.min.js"></script>
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/toast-master/js/jquery.toast.js"></script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?=base_url()?>_assets/material_pro/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    
    <?php if (isset($status)): ?>
        
    <script>
        $( document ).ready(function() {
           $.toast({
            heading: 'Login Error!',
            text: '<?php echo $message; ?>',
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: '<?php echo $status; ?>',
            hideAfter: 3500
          });
        });   
    </script>
    <?php endif; ?>

</body>

</html>