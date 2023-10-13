<?= $this->extend("layouts/app_before") ?>
<?php  echo $userId; ?>
<?= $this->section("body") ?>
<div class="container">
      <div class="card card-register mx-auto mt-5">
        <div class="card-header">Validate new user</div>
        <div class="card-body">
        <div class="text-center">
        <?php
            $atts = [
                'scrollbars'  => 'yes',
                'status'      => 'yes',
                'resizable'   => 'yes',
                'screenx'     => 0,
                'screeny'     => 0,
            ];

            echo anchor('validateoption/'.$userId, 'Click Me!', $atts); ?>
                <a class="d-block small mt-3" href="<?php echo site_url('login'); ?>">Back to Login</a>
            </div>
        </div>
    </div>
    </div>
</div>

        <?= $this->endSection() ?>