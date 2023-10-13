<?= $this->extend("layouts/app_before") ?>

<?= $this->section("body") ?>
<div class="row m-3">
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
        <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
    <?php endif; ?>
    <?php if (isset($validation)) : ?>
        <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
    <?php endif; ?>
    <?php if (isset($valid)) : ?>
        <p style="color:red; font-size:18px;" align="center"><?= $valid ?></p>
    <?php endif; ?>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <form  class="form-Centered sign-in"  action="<?= base_url('forget') ?>" method="post">
            <h5 class="Login-title">Forget Password</h5>
            <div class="mb-4">
                <input type="email" class="form-control input-style" name="email" id="email"  placeholder="Email Address" value="<?php echo set_value('email'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('email') ?></div><?php endif; ?>

            </div>
            <div class="row m-3">
                        <button type="submit" class="btn btn-primary btn-style Centered mb-3">Submit</button>
                        <a class="btn btn-primary btn-style Centered" href="<?php echo site_url('login'); ?>">Back to Login</a>
            </div>
        </form>
    </div> 
    <div class="col-md-6 col-sm-12 col-xs-12">
                <img src="images/login.png"  class="img-centered img-fluid" alt="login-image">
            </div>    
</div>
    


<?= $this->endSection() ?>