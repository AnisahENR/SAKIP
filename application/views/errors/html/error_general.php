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
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo config_item('base_url'); ?>_assets/material_pro/assets/images/favicon.png">
    <title>SILAT - <?php echo $status_code; ?> <?php echo $heading; ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo config_item('base_url'); ?>_assets/material_pro/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo config_item('base_url'); ?>_assets/material_pro/material/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="<?php echo config_item('base_url'); ?>_assets/material_pro/material/css/colors/default.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper" class="error-page">
        <div class="error-box">
            <div class="error-body text-center">
                <h1 class="text-info"><?php echo $status_code; ?></h1>
                <h3 class="text-uppercase"><?php echo $heading; ?></h3>
                <p class="text-muted m-t-30 m-b-30"><?php echo $message; ?></p>
                <a href="<?php echo config_item('base_url'); ?>" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Kembali</a> </div>
            <footer class="footer text-center">© 2020 Pemerintah Kota Pasuruan</footer>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo config_item('base_url'); ?>_assets/material_pro/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo config_item('base_url'); ?>_assets/material_pro/assets/plugins/popper/popper.min.js"></script>
    <script src="<?php echo config_item('base_url'); ?>_assets/material_pro/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo config_item('base_url'); ?>_assets/material_pro/material/js/waves.js"></script>
</body>

</html>