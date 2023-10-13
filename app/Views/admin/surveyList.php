<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Survey List</h1>
    <hr>    
    
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
            <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
        <?php endif; ?>
<div class="row">
<div class="col-xl-11 col-lg-11 col-md-11">
    <div class="text-center mb-5"><a class="btn btn-primary float-end" href="<?php echo site_url('createSurvey'); ?>">Create Survey</a>
</div>
</div>
</div>

<?php  if(!empty($surveryList)) { ?>
    <table class="table mt-6 table-striped table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Survery Campain Name</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($surveryList as $surveryData) { ?>
    <tr>
      <td scope="row"><?php echo $surveryData['campign_id']; ?></td>
      <td><?php echo stripslashes($surveryData['campain_name']); ?></td>
      <td><a class="btn btn-primary" href="<?php echo site_url('editsurvey/'.$surveryData['campign_id']); ?>">edit</a>
      <a class="btn btn-primary" href="<?php echo site_url('deletesurvey/'.$surveryData['campign_id']); ?>">delete</a></td>
    </tr>
  <?php  } ?>
  </tbody>

  </table>
  <?php  } else { ?>
    <div class="text-center"><p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
    </div>
  <?php } ?>
  </div>
  </section>
    <?= $this->endSection() ?>