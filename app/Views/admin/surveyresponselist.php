<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>

<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>
    <div class="row"> 
    <div class="col-xl-8 col-lg-8 col-md-8"><h1>Survey Response Reports</h1> </div>
            <?php if(session()->get('tenant_id') == 1) { ?> 
            <div class="col-xl-4 col-lg-4 col-md-4 float-right">
                <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="tenant" id="tenantchange">
                <?php foreach($getTenantdata as $getTenantlist) { ?> 
                    <option value="<?php echo $getTenantlist['tenant_id'] ; ?>" <?php if($selectTenant ==  $getTenantlist['tenant_id']): ?>selected="selected" <?php endif; ?>><?php echo $getTenantlist['tenant_name'] ; ?></option>
                  <?php  } ?>
                  </select>
            </div>
            <?php } ?>
            </div>
            <hr>  
            <?php if(!empty($getsurveylist)){ $basesurvey = (isset($_GET['tenantId'])) ? base_url('SurveyResponse?tenantId='.$_GET['tenantId']) : base_url('SurveyResponse'); ?>
    <form class="form-horizontal" action="<?= $basesurvey; ?>" method="post">

    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="surveyid" >
                <?php foreach($getsurveylist as $getsurveys) { ?> 
                    <option value="<?php echo $getsurveys['campign_id'] ; ?>" <?php if($getsurveys['campign_id'] == $selectsurvey['campign_id']) { ?> selected ="selected" <?php }  ?>><?php echo $getsurveys['campain_name'] ; ?></option>
                  <?php  } ?>
                  </select>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                <input type="submit" name="submit" id="submit" class="btn btn-primary btn-block" value="Filter" /> 
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <table class="table mt-6 table-striped table-bordered">
  <tbody>
    <?php if(!empty($getSurveyData)) { 
        foreach($getSurveyData as $getSurveylist) { 
        $timestamp = strtotime($getSurveylist["created_at"]);
        ?>
    <tr >
        <td scope="row">
        <div class="d-flex flex-column"> <div class="p-2 bd-highlight"><p>Completed</p></div>
        <div class="p-2 bd-highlight"> <span class="rounded-circle">1</span></div>
        <div class="p-2 bd-highlight"> <span class="rounded-circle">2</span></div>
        </div>
        </td>
        <td scope="row">
        <div class="d-flex flex-column"> <div class="p-2 bd-highlight container"><div class="row">
            <div class="col-xl-2 col-lg-2 col-md-2"> <i class="bi bi-clock"></i><?php echo date("h:m:s",$timestamp); ?></div>
            <div class="col-xl-4 col-lg-4 col-md-4"> <i class="bi bi-calendar"></i><?php echo date("l,m d,Y",$timestamp); ?></div>
            <div class="col-xl-4 col-lg-4 col-md-4"> <i class="bi bi-telephone"></i><?php echo isset($getSurveylist['userdata']) ? $getSurveylist['userdata']['contact_details'] : ""; ?></div>
        </div></div>
        <div class="p-2 bd-highlight"> <span ><?php echo $getSurveylist["questiondata"][0] ? $getSurveylist["questiondata"][0]['question_name'] : ""; ?></span></div>
        <div class="p-2 bd-highlight"> <span ><?php echo $getSurveylist["questiondata"][1] ? $getSurveylist["questiondata"][1]['question_name'] :" "; ?></span></div>
        </div>
        </td>
        <td scope="row">
        <div class="d-flex flex-column"> <div class="p-2 bd-highlight container"><div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12"> <i class="bi bi-envelope"></i><?php echo isset($getSurveylist['userdata']) ? $getSurveylist['userdata']['email_id'] :''; ?></div>
        </div></div>
        <div class="p-2 bd-highlight"> <span ><?php echo $getSurveylist["answer_id1"]; ?></span></div>
        <div class="p-2 bd-highlight"> <span ><?php echo $getSurveylist["answer_id2"]; ?></span></div>

        </td>
    </tr>
    <?php } } else { ?>
        <div class="text-center"><p class="fs-3"> <span class="text-danger">Oops!</span>There is no record for this survey.</p>
    </div>
    <?php } ?>
</tbody>
</table>
    </form>
    </div>
  </section>
  <script type="text/javascript">
        $("#tenantchange").change(function(){
        var t_id= $(this).val();
        var newUrl = '';
        var currLoc = $(location).attr('href');
        var hashes =  window.location.href.indexOf("?");    
        if(hashes == -1) {
            var currentUrl = window.location.href +"?tenantId="+t_id;
            var urls = new URL(currentUrl);
            newUrl = urls.href; 
        }else {
            var currentUrl = window.location.href;
            var urls = new URL(currentUrl);
            urls.searchParams.set("tenantId", t_id); // setting your param
            newUrl = urls.href; 
        }
        window.location.href =  newUrl;
    });
    </script>
    <?= $this->endSection() ?>