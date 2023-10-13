<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>

<?php echo script_tag('js/jquery.min.js'); ?>

<?php echo script_tag('js/editor/jquery-3.6.0.min.js'); ?>
<?php echo script_tag('js/tinymce/tinymce.min.js'); ?>
<?php echo script_tag('js/editor/tinymce-jquery.min.js'); ?>

<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Email Template Option</h1>
    <hr>    
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
     <p style="color:green; font-size:18px;"><?php echo session()->getFlashdata('response'); ?></p>
    <?php endif; ?>
    <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>

    <form class="form-horizontal" action="<?= base_url('sendEmail') ?>" method="post" enctype="multipart/form-data">
    <div class="row">
    <div class="col-xl-7 col-lg-7 col-md-7">
      <div id="dynamic_field" class="card">
      <div class="card-body">
        <div class="form-group mb-3">
            <div class="form-row row"> 
              <label class="control-label  col-xl-5 col-lg-5 col-md-5" for="subject">subject:</label>
                <div class="col-xl-7 col-lg-7 col-md-7">
                <input type="text" class="form-control" id="subject" placeholder="Send subject" name="subject" autocomplete="off" >
                </div>
              </div>
            </div>
          <div class="form-group mb-3">
            <div class="form-row row"> 
              <label class="control-label  col-xl-5 col-lg-5 col-md-5" for="Campaign Name">Enter Survey/Campaign Name:</label>
                <div class="col-xl-7 col-lg-7 col-md-7">
                  <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="survey" >
                  <?php foreach($getSurvey as $getSurveylist) { ?> 
                    <option value="<?php echo $getSurveylist['campign_id'] ; ?>"><?php echo $getSurveylist['campain_name'] ; ?></option>
                  <?php  } ?>
                  </select>
                </div>
            </div> 
          </div>
          
          <!-- <div class="form-group mb-3">
            <div class="form-row row"> 
              <label class="control-label  col-xl-5 col-lg-5 col-md-5" for="Send Email">Send Email to:</label>
                <div class="col-xl-7 col-lg-7 col-md-7">
                <input type="text" class="form-control" id="sendemail" placeholder="Send Email to" name="sendemail" autocomplete="off" >
                </div>
              </div>
            </div> -->
              
          <div class="form-group mb-3">
            <div class="form-row row"> 
              <label class="control-label  col-xl-5 col-lg-5 col-md-5" for="Send Email">Send Email to (Upload):</label>
                <div class="col-xl-7 col-lg-7 col-md-7">
                  <div class="input-group custom-file-button">
                  <label class="input-group-text" for="inputGroupFile">Upload Email data:</label>
                  <input type="file" name="file"  style="display:none" class="form-control file" id="inputGroupFile" >
                  </div>
              </div>
            </div>       
          </div>

          <div class="form-group mb-3">
            <div class="form-row row"> 
              <label class="control-label  col-xl-12 col-lg-12 col-md-5" for="Send Email">Message :</label>
                <div class="col-xl-12 col-lg-12 col-md-12">
                <textarea id="editor" name="editor" value="" class="form-control">
</textarea>
              </div>
            </div>       
          </div>
          
        </div> 
    </div>
  </div>
<!-- Ending col space -->

    <div class="col-xl-5 col-lg-5 col-md-5">
    <div class="card">
      <div class="card-body">
      <div class="form-group mb-3">
        <div class="form-row row"> 
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary" id="result_panel">
            <div class="panel-heading">
              <ul class="list-group" style="overflow-y: none !important; " id="">
                <li class="list-group-item">
                    <div class="container">
                      <div class= "row">
                        <div class="col-xl-11 col-lg-11 col-md-11">
                          Email Address
                        </div>
                        <div class="col-xl-1 col-lg-1 col-md-1">
                          <input type="checkbox" id="select-all">
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
            </div>
                <div class="panel-body">
                  <ul class="list-group" id="append_list">
                    <?php ?>
                    <?php if($externalList) { foreach($externalList as $externalData) { ?>
                    <li class="list-group-item">
                      <div class="container">
                        <div class= "row">
                          <div class="col-xl-11 col-lg-11 col-md-11">
                            <?php echo $externalData["email_id"]; ?>
                          </div>
                          <div class="col-xl-1 col-lg-1 col-md-1">
                            <input type="checkbox" id="checkoutemail" name="checkoutemail[]" checked value="<?php echo $externalData["email_id"]; ?>" />
                          </div>
                        </div>
                      </div>
                    </li>
                    <?php  }  } ?>  
                  </ul>
                </div>
            </div>
          </div>
        </div>  
        </div>
    </div>
      </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="form-group mt-3">          
      <div class="form-row row">
        <div class="col-md-6 offset-4">
        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block" value="Send Email" /> 
        </div>
      </div>
    </div>
    </div>
</div>
  </form>

    </div>
    <style>
      .list-group{
    max-height: 248px;
    margin-bottom: 10px;
    overflow-y:scroll;
    -webkit-overflow-scrolling: touch;
}
      </style>
          </section>
<script type="text/javascript">
  document.getElementById('select-all').onclick = function() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
$(document).on('change','input[type="file"]',function(){
    // console.log(file1);
    var file = null;
    var file = $('#inputGroupFile').prop('files')[0];
    console.log(file);

    var formData = new FormData();
    formData.append('formData', file);
        console.log(formData);

    $.ajax({  
      url:'<?php echo base_url('uploadFile'); ?>',
      type: 'post',
      dataType:'json',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response){
        if(response.query) {
          let email = response.query;
          const myArray = email.split(",");
          console.log(myArray);
          $.each(myArray, function(index, value) {
              $("#append_list").append('<li class="list-group-item"><div class="container"><div class= "row"><div class="col-xl-11 col-lg-11 col-md-11">'+value+'</div><div class="col-xl-1 col-lg-1 col-md-1"><input type="checkbox"  id="checkoutemail" name="checkoutemail[]" checked value="'+value+'"/></div></div></div></li>');
              

          });
          $("#sendemail").val(response.query);
        }
        window.location.reload();
      },
      error: function(response){
        console.log(response);
      } 
    });
  });
tinymce.init({
    menubar: false,
    selector: '#editor',
    setup: function (editor) {
      editor.on('init', function (e) {
        editor.setContent('<p>Thanks and cheers to having been part of our team.</p><p>We wholeheartedly appreciate your efforts.</p><p>We`d be super glad to have your feedback</p><p></p>');
      });
    }
  });
$('textarea#editor').tinymce({
        height: 160,
        menubar: false,
        plugins: [
           'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
           'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
           'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
        ],
        toolbar: 'undo redo | a11ycheck casechange blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist checklist outdent indent | removeformat | code table help'
      });
    </script>
    </section>
<?= $this->endSection() ?>  