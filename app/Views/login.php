<?= $this->extend("layouts/app_before") ?>

<?= $this->section("body") ?>
    
          <div class="row m-3">
          <?php if (session()->getFlashdata('response') !== NULL) : ?>
                <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
            <?php endif; ?>

            <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <form  class="form-Centered sign-in"  action="<?= base_url('login') ?>" method="post">

                    <h5 class="Login-title">Sign in</h5>
                    <h6 class="Login-desc">Sign in and start managing your candidates!</h6>
                    <div class="mb-4">
                    <input type="text" class="form-control input-style" name="tenantname" id="tenantname" placeholder="Tenant Name" value="<?php echo set_value('tenantname'); ?>">
                    <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('tenantname') ?></div><?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <input type="email" class="form-control input-style" name="email" placeholder="Email Address" id="email" value="<?php echo set_value('email'); ?>">
                            <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('email') ?></div><?php endif; ?>
                        </div>
                    <div class="">
                    <input type="password" class="form-control input-style" name="password" id="password" placeholder="Password">
                    <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('password') ?></div><?php endif; ?>

                      </div>
                    <div class="form-check">
                      <!-- <input type="checkbox" class="form-check-input checkbox-style"> -->
                      <!-- <label class="form-check-label">Remember me</label> -->
                      <label class="form-check-label float-right"> <a class="d-block small mt-3" href="<?php echo site_url('forget'); ?>">Forget Password</a></label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-style Centered">login</button><br>
                    <a class="btn btn-primary btn-style Centered" href="<?php echo site_url('signup'); ?>">Register an Account</a>
                  </form>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <img src="<?php echo base_url(); ?>images/login.png"  class="img-centered img-fluid" alt="login-image">
            </div>
         
      
        </div> 
    
<?= $this->endSection() ?>