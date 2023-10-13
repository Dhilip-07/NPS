<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/front/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/front/nps.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/front/Mediaquery.css') ?>">

    <title>NPS Login page</title>
</head>
<body>
<div class="container">
        <nav class="navbar container">
            <div class="container">
              <?php $logoimg = (session()->get("logo_user")) ?  session()->get("logo_user") : "images/logo.png"; ?>
              <a class="navbar-brand" href="#">
                <img src="<?php echo base_url().$logoimg; ?>" class="logo img-fluid" alt="logo">
              </a>
            </div>
          </nav>
   <?= $this->renderSection("body") ?>
   </div> 
          <div class="row">
        <img src="<?php echo base_url(); ?>images/Vectors.png" class="Footer-img img-fluid" alt="login-image">
    </div>
</body>

<?php echo script_tag('vendor/jquery/jquery.min.js'); ?>
<?php echo script_tag('vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>
<?php echo script_tag('vendor/jquery-easing/jquery.easing.min.js'); ?>


</html>