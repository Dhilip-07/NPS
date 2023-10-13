<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Create Question and Summary</h1>
    <hr>   
     
    <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
    <form class="form-horizontal" action="<?= base_url('createAnswer') ?>" method="post">
    <div id="dynamic_field">
    <div class="form-group  mb-3">
        <div class="form-row row">
        <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Answer">Enter Answer:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <input type="text" class="form-control" id="answer" placeholder="Enter Answer" name="answer" autocomplete="off">
        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('answer') ?></div><?php endif; ?>

      </div>
    </div></div>
    
    <div class="form-group  mb-3">
        <div class="form-row row">
    <label class="control-label col-xl-3 col-lg-3 col-md-3" for="ainfo">Enter Answer Info:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <input type="text" class="form-control" id="ainfo" placeholder="Enter Answer Info" name="ainfo" autocomplete="off">
        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('ainfo') ?></div><?php endif; ?>

      </div>
    </div></div>
    
    
        </div>
        <div class="form-group  mt-3">          
      <div class="form-row row">
      <div class="col-md-6 offset-4">
     <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block" value="Submit" /> 
</div></div></div>
</div> 
  </form>

    </div>
        </section>

<?= $this->endSection() ?>