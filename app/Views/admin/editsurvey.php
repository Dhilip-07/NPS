<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Update Survey Option</h1>
    <hr>    
    <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
            <?php 

  $campign_id =  (!empty($getSurveyData)) ? $getSurveyData['campign_id'] : set_value('campign_id');
  $campain_name =  (!empty($getSurveyData)) ? stripslashes($getSurveyData['campain_name']) :set_value('suerveyname');
  $question_id_1 = (!empty($getSurveyData)) ? $getSurveyData['question_id_1']: set_value('quest_1');
  $question_id_2 =  (!empty($getSurveyData)) ? $getSurveyData['question_id_2']: set_value('quest_2');

   ?>
    <form class="form-horizontal" action="<?= base_url('editsurvey/'.$campign_id) ?>" method="post">
    <input type="hidden" class="form-control" id="campign_id"   name="campign_id" autocomplete="off" value="<?php echo $campign_id; ?>">

    <div id="dynamic_field">
    <div class="form-group mb-3">
    <div class="form-row row">      
      <label class="control-label  col-xl-3 col-lg-3 col-md-3" for="Campaign Name">Enter Survey/Campaign Name:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <input type="text" class="form-control" id="suerveyname" placeholder="Enter Survey/Campaign Name" name="suerveyname" autocomplete="off" value="<?php echo $campain_name; ?>">
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
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="quest_1" value="<?php echo $question_id_1; ?>">
          <?php foreach($getQuestData as $getQuestlist) { ?> 
            <option <?php if($getQuestlist['question_id'] == $question_id_1) { ?> selected="selected" <?php } ?> value="<?php echo $getQuestlist['question_id'] ; ?>"><?php echo $getQuestlist['question_name'] ; ?></option>
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
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="quest_2" value="<?php echo $question_id_2; ?>" >
          <?php foreach($getQuestData as $getQuestlist) { ?> 
            <option <?php if($getQuestlist['question_id'] == $question_id_2) { ?> selected="selected" <?php } ?> value="<?php echo $getQuestlist['question_id'] ; ?>"><?php echo $getQuestlist['question_name'] ; ?></option>
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