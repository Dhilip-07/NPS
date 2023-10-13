<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<div id="wrapper">
    <?php include APPPATH.'Views/layouts/sidebar.php';?>

    <div id="content-wrapper">
        <div class="container-fluid">
            <!-- Breadcrumbs-->
            <?php include APPPATH.'Views/layouts/breadcrumb.php';?>       

          

          <!-- Icon Cards-->
          <div class="row">
            <div class="col-xl-12 col-sm-6 mb-3">
   <h3>Welcome Back : <?= session()->get('firstname')." ".session()->get('lastname') ?> </h3>
            </div>
        </div>
    </div>
    <canvas id="myChart" width="400" height="400"></canvas>

</div>


<?= $this->endSection() ?>