<?= $this->extend("layouts/app_before") ?>

<?= $this->section("body") ?>
    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">forget Password</div>
            <?php if (session()->getFlashdata('response') !== NULL) : ?>
                <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
            <?php endif; ?>

            <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
            <?php if (isset($valid)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $valid ?></p>
            <?php endif; ?>
            <div class="card-body">
            <form class="" action="<?= base_url('forget') ?>" method="post">
               
                <div class="form-group">
                    <div class="form-label-group">                        
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
                        <label for="email">Email</label>
                        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('email') ?></div><?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a class="d-block small mt-3" href="<?php echo site_url('login'); ?>">Back to Login</a>
                </div>
                
                </form>
            </div>
        </div>
    </div> 


<?= $this->endSection() ?>