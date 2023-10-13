<?= $this->extend("layouts/app_before") ?>

<?= $this->section("body") ?>
    <div class="row m-3">
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
            <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
        <?php endif; ?>
        <?php if (isset($validation)) : ?>
            <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
        <?php endif; ?>
        <div class="col-md-10 col-sm-12 col-xs-12">
        <form  class="sign-in"  action="<?= base_url('signup') ?>" method="post">
            <h5 class="Login-title">Sign Up</h5>
            <div class="row m-3">
                <div class="col-md-6 col-sm-12 col-xs-12">                    
                    <div class="mb-3">
                        <input type="text" class="form-control input-style" name="firstname" id="firstname" placeholder="first name" value="<?php echo set_value('firstname'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('firstname') ?></div><?php endif; ?>
                    </div>                   
                    <div class="mb-3">
                        <input type="text" class="form-control input-style" name="tenantname" id="tenantname" placeholder="Tenant Details" value="<?php echo set_value('tenantname'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('tenantname') ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <input type="email" placeholder="Email Address"  class="form-control input-style" name="email" id="email" value="<?php echo set_value('email'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('email') ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control input-style" placeholder="Password" name="password" id="password">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('password') ?></div><?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="mb-3">
                        <input type="text" class="form-control input-style" name="lastname" id="lastname" placeholder="last name" value="<?php echo set_value('lastname'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('lastname') ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control input-style" name="username" id="username" placeholder="username" value="<?php echo set_value('username'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('username') ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control input-style" name="phone_no" id="phone_no" placeholder="Phone No." value="<?php echo set_value('phone_no'); ?>">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('phone_no') ?></div><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <input type="password" placeholder="Confirm Password"  class="form-control input-style"  name="confirmpassword" id="confirmpassword">
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('confirmpassword') ?></div><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-4 col-sm-12 col-xs-12"></div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                        <button type="submit" class="btn btn-primary btn-style Centered mb-3">Submit</button>
                        <a class="btn btn-primary btn-style Centered" href="<?php echo site_url('login'); ?>">Back to Login</a>
                </div>
            </div>
        </form>
        </div>
        <div class="col-md-2 col-sm-12 col-xs-12">
                <img src="images/login.png"  class="img-centered img-fluid" alt="login-image">
            </div>
    </div>
<?= $this->endSection() ?>