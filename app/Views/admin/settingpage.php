<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Setting Page</h1>
    <hr> 
    <!---- Success Message ---->
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
      <p style="color:green; font-size:18px;"><?php echo session()->getFlashdata('response'); ?></p>
    <?php endif; ?>   
    <?php if (isset($validation)) : ?>
                <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('validatecheck') ?></p>
            <?php endif; ?>
    <form class="form-horizontal" action="<?= base_url('settingupdate') ?>" method="post" enctype="multipart/form-data">
    <div id="dynamic_field">
    <?php 
    //   $u_id =$settingdata['u_id'];
  $firstname = set_value('firstname') == false ? $settingdata['firstname']: set_value('firstname');
  $lastname = set_value('lastname') == false ? $settingdata['lastname'] : set_value('lastname');
  $username = set_value('username') == false ? $settingdata['username'] : set_value('lastname');
  $logo_update = isset($settingdata['logo_update']) ? $settingdata['logo_update'] : '';
  $logo_img = isset($settingdata['logo_img']) ? $settingdata['logo_img'] : '';
//   $email = set_value('email') == false ? $settingdata['email'] : set_value('email');
//   $phone_no = set_value('phone_no') == false ? $settingdata['phone_no'] : set_value('phone_no');

   ?>
    <div class="form-group mb-3">
    <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Firstname">Enter Firstname:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
      <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $firstname; ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('firstname') ?></div><?php endif; ?>
      </div>
    </div>
    </div>

    <div class="form-group mb-3">
    <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Lastname">Enter Lastname:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
      <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $lastname; ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('lastname') ?></div><?php endif; ?>
      </div>
    </div>
    </div>

    <div class="form-group mb-3">
    <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Username">Enter Username:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
      <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>">
              <?php if (isset($validation)) : ?> <div style="color:red"><?= $validation->showError('username') ?></div><?php endif; ?>
      </div>
    </div>
    </div>

    <div class="form-group mb-3">
    <div class="form-row row">
      <label class="control-label col-xl-3 col-lg-3 col-md-3" for="Logo">Upload Logo:</label>
      <div class="col-xl-6 col-lg-6 col-md-6">
        <img src="<?php echo $path = base_url().$logo_img;  ?>" class="rounded-3" style="width: 150px;" id="ajaxImgUpload">
        <div class="input-group custom-file-button">
              <label class="input-group-text" for="inputGroupFile">Upload Logo:</label>
              <input type="file" name="file" style="display:none" class="form-control" id="inputGroupFile" onChange="chkFile(this)">
        </div>
        <input type="hidden" class="form-control" name="logofile" id="logofile" value="<?php echo $logo_update; ?>">

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

<script type="text/javascript">
    function chkFile(file1) {
        // Append Image 
        if (file1.files && file1.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#ajaxImgUpload").attr('src', e.target.result).width(200)
            };
            reader.readAsDataURL(file1.files[0]);
        }
        var file = file1.files[0];

        var formData = new FormData();
        formData.append('formData', file);
        $.ajax({  
            url:'<?php echo base_url('logoupload'); ?>',
            type: 'post',
            dataType:'json',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response);
                $("#logofile").val(response.data.filepath);
            },
            error: function(response){
                console.log(response);
            } 
        });
    }
</script>
<?= $this->endSection() ?>