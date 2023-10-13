<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Create Survey option</h1>
    <hr>    
    
    <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
    <form class="form-horizontal" action="<?= base_url('create_survey') ?>" method="post">
    <div id="dynamic_field">
    <div class="form-group mb-3">
    <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Surveyname">Enter Survey/Campaign Name:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <input type="text" class="form-control" id="suerveyname" placeholder="Enter Survey/Campaign Name" name="suerveyname" autocomplete="off">
        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('suerveyname') ?></div><?php endif; ?>
          </div>
      </div>
    </div>
    
    <div class="form-group mb-3">
    <div class="form-row row">

      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Answer">Select Question 1:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
  <div class="form-group">
    <div class="input-group">         
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="quest_1" >
          <?php foreach($getQuestData as $getQuestlist) { ?> 
            <option value="<?php echo $getQuestlist['question_id'] ; ?>"><?php echo $getQuestlist['question_name'] ; ?></option>
          <?php  } ?>
        </select>

        </div>       
       </div>
        </div>   
      </div>
    </div>
    
    <div class="form-group mb-3">
    <div class="form-row row">

      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Answer">Select Question 2:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
  <div class="form-group">
    <div class="input-group">         
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="quest_2" >
          <?php foreach($getQuestData as $getQuestlist) { ?> 
            <option value="<?php echo $getQuestlist['question_id'] ; ?>"><?php echo $getQuestlist['question_name'] ; ?></option>
          <?php  } ?>
        </select>

        </div>
        </div>   
        </div>   

      </div>
    </div>

     
    <div class="form-group mt-3">          
      <div class="form-row row">
        <div class="col-md-6 offset-4"> 
     <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block" value="Submit" /> 
</div></div></div>
</div> 
  </form>

</div>
          </section>
<?= $this->endSection() ?>