<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'views/layouts/sidebar.php';?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Create New Tenant</h1>
    <hr>
<!---- Success Message ---->
<?php if (session()->getFlashdata('response') !== NULL) : ?>
  <p style="color:green; font-size:18px;"><?php echo session()->getFlashdata('response'); ?></p>

<?php endif; ?>
<?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
      <!-- Icon Cards-->
    <form class="" action="<?= base_url('paswordupdate') ?>" method="post">
      <div class="form-group  mb-3">
        <div class="form-row row">
        <label class="control-label col-xl-3 col-lg-3 col-md-3" for="password">Enter your password:</label>
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
              <input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('password') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>
     
      <div class="form-group  mb-3">
        <div class="form-row row">
        <label class="control-label col-xl-3 col-lg-3 col-md-3" for="password">Enter your confirm password</label>

          <div class="col-md-6 offset-1">
            <div class="form-label-group">
              <input type="password" class="form-control" name="confpassword" id="confpassword" value="<?php echo set_value('confpassword'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('confpassword') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      
      
      <div class="form-group  mt-3">          
      <div class="form-row row">
      <div class="col-md-6 offset-4">
          <button type="submit" class="btn btn-primary btn-block">Update</button>
        </div>
      </div>
      </div>


    </form>
</div>
</section>


<?= $this->endSection() ?>