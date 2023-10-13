<?= $this->extend("layouts/app_before") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>
    <div class="row m-3">
        <div class=" offset-3 col-md-6 col-sm-12 col-xs-12">
            <img src="<?php echo base_url(); ?>images/surveyFeed.jpg"  class="img-centered img-fluid" style="height:200px;" alt="login-image">
        </div>
    </div>
    <div class="row m-3"> 
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
            <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
        <?php endif; ?>      
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card col-md-12 bg-white shadow-md p-5">
                <div class="mb-4 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="75" height="75"
                        fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                    </svg>
                </div>
                <div class="text-center">
                    <h1>Thank You !</h1>
                    <p>you already completed the survey section.</p>
                    <button id="submitoption" class="btn btn-outline-success">Back Home</button>
                </div>

                    </div>
            </div>
        


     
<script type="text/javascript">
    $(document).ready(function(){  
        $("#submitoption").click(function () {
           window.location.href = "<?php echo base_url(); ?>/login";
        });      
    });
</script>
<?= $this->endSection() ?>