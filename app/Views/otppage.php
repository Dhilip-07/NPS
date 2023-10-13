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
                <form  class="form-Centered sign-in"  action="<?= base_url('verificationpage') ?>" method="post">

                    <h5 class="Login-title">check your Mail</h5>
                    <h6 class="Login-desc">Enter the code sent to your email Address!</h6>
                    <div class="mb-4">
                    <input type="text" class="form-control input-style" name="vcode" id="vcode" placeholder="Verification Code" value="">
                    <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('vcode') ?></div><?php endif; ?>
                        </div>  
                    <button type="submit" class="btn btn-primary btn-style Centered">Verify code</button><br>
                    <a class="btn btn-primary btn-style Centered" href="<?php echo site_url('login'); ?>">Back to Login</a>

                  </form>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <img src="<?php echo base_url(); ?>images/login.png"  class="img-centered img-fluid" alt="login-image">
            </div>
         
      
        </div> 
    
<?= $this->endSection() ?>