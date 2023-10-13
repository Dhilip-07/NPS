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
    <form class="form-horizontal" action="<?= base_url('create_question') ?>" method="post">
    <div id="dynamic_field">
    <div class="form-group  mb-3">
        <div class="form-row row">
        <label class="control-label col-xl-3 col-lg-3 col-md-3" for="question">Enter Question:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <input type="text" class="form-control" id="question" placeholder="Enter question" name="question" autocomplete="off" value="<?php echo set_value('question'); ?>">
        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('question') ?></div><?php endif; ?>

      </div>
    </div></div>
    
    <div class="form-group  mb-3">
        <div class="form-row row">
    <label class="control-label col-xl-3 col-lg-3 col-md-3" for="qinfo">Enter Question Info:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <input type="text" class="form-control" id="qinfo" placeholder="Enter Question Info" name="qinfo" autocomplete="off" value="<?php echo set_value('qinfo'); ?>">
        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('qinfo') ?></div><?php endif; ?>

      </div>
    </div></div>
    
    <div class="form-group  mb-3">
        <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Answer">Select Answer:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
  <div class="form-group">
    <div class="input-group">         
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="answer" id="answer_data"  value="<?php echo set_value('answer'); ?>" >
            <option value="">-select-</option>
            <option value="nps" <?php echo (set_value('answer')=='nps')?" selected=' selected'":""?>>NPS Answer Type</option>
            <option value="other"  <?php echo (set_value('answer')=='other')?" selected=' selected'":""?>>Other</option>
        </select>

        </div>
        </div> 
        <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('answer') ?></div><?php endif; ?>
  
      </div>
    </div>
    </div>
    <div id="answerother_open">
    <div class="form-group  mb-3" id="">
        <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Answer">Other Answer:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
  <div class="form-group">
    <div class="input-group">         
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="answerdata[]" id="answerother" multiple>
        <?php foreach($answercollection as $key => $answerlist) { ?> 
            <option value="<?php echo $answerlist; ?>"><?php echo $answerlist; ?></option>
          <?php  } ?>
        </select>

        </div>
        </div>
      </div>
    </div> </div>  
    <div class="form-group  mb-3">
        <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="priority">Select priority:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
  <div class="form-group">
    <div class="input-group">         
        <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="priority" id="priority" >
            <option value="">-select-</option>
            <option value="1" <?php echo (set_value('priority')=='1')?" selected=' selected'":""?>>Range 0-6 Rating</option>
            <option value="2" <?php echo (set_value('priority')=='2')?" selected=' selected'":""?>>Range 7-8 Rating</option>
            <option value="3" <?php echo (set_value('priority')=='3')?" selected=' selected'":""?>>Range 9-10 Rating</option>
        </select>

        </div>
        </div>   
      </div>
    </div>
    </div>   
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
        <script type="text/javascript">
$(document).ready(function(){      
  $("#answerother_open").hide();
  $('#answer_data').change(function(){  
        if($(this).val() == 'other') {
          $("#answerother_open").show();
        }else {
          $("#answerother_open").hide();
        }
    });
    var selectoption = "<?php echo set_value('answer'); ?>";
    if(selectoption == 'other') {
      $("#answerother_open").show();
    }
});
</script> 
<!-- 
<script type="text/javascript">
    $(document).ready(function(){      
      var i=1;  
      $('#add').click(function(){  
           i++;             
           $('#dynamic_field').append('<div id="row'+i+'"><div class="form-group"><label class="control-label col-sm-2" for="question">Enter Question:</label><div class="col-sm-5"><input type="text" class="form-control"  placeholder="Enter question" name="question[]" autocomplete="off"></div></div><div class="form-group"><label class="control-label col-sm-2" for="qinfo">Enter Question Info:</label><div class="col-sm-5"><input type="text" class="form-control" id="qinfo" placeholder="Enter Question Info" name="qinfo[]" autocomplete="off"></div></div><div class="form-group"><label class="control-label col-sm-2" for="Answer">Select Answer:</label><div class="col-sm-5"><select class="custom-select custom-select-sm" aria-label="Default select example" class="form-select form-select-lg mb-3"  name="amswer[]" ><option value="nps">NPS Answer Type</option></select></div><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></div>');

     });
     
     $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id"); 
           var res = confirm('Are You Sure You Want To Delete This?');
           if(res==true){
           $('#row'+button_id+'').remove();  
           $('#'+button_id+'').remove();  
           }
      });  
      if(i > 5) {
        console.log(i);
      }
  
    });  
</script> -->
<?= $this->endSection() ?>