<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<section class="home">
        <div class="container">
               <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
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
    <form class="" action="<?= base_url('tenant_data') ?>" method="post">
      <div class="form-group mb-4">
        <div class="form-row row">
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="tenantname">Enter your Tenant Name</label>

              <input type="text" class="form-control" name="tenantname" id="tenantname" value="<?php echo set_value('tenantname'); ?>" onChange="this.value=this.value.toLowerCase();">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('tenantname') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>

     <h1>Create Admin User</h1>
              <hr/>
      <div class="form-group mb-3">
        <div class="form-row row">
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="firstname">Enter your First Name</label>
              <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('firstname') ?></div><?php endif; ?>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="lastname">Enter your Last Name</label>
              <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('lastname') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group  mb-3">
        <div class="form-row row">
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="username">Enter your User Name</label>

              <input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('username') ?></div><?php endif; ?>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="email">Enter your Email</label>
              <input type="text" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('email') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group  mb-3">
        <div class="form-row row">
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="phone_no">Enter your phone_no</label>
              <input type="text" class="form-control" name="phone_no" id="phone_no" value="<?php echo set_value('phone_no'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('phone_no') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <hr/>
      <div class="form-group  mb-3">
        <div class="form-row row">
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="password">Enter your password</label>
              <input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('password') ?></div><?php endif; ?>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="form-label-group">
            <label for="confirmpassword">Enter your confirm password</label>
              <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" value="<?php echo set_value('confirmpassword'); ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('confirmpassword') ?></div><?php endif; ?>
            </div>
          </div>
        </div>
      </div>

<div class="form-group  mt-3">          
      <div class="form-row row">
        <div class="col-xl-12 col-lg-12 col-md-12"> 
          <button type="submit" class="btn btn-primary float-end">Create Tenant</button>
        </div>
      </div>
      </div>

    </form>
    </div>

              </section>

<?= $this->endSection() ?>