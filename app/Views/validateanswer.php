<?= $this->extend("layouts/app_before") ?>
<?= $this->section("body") ?>
<?php echo script_tag('js/jquery.min.js'); ?>

    <div class="row m-3">
        <div class=" offset-3 col-md-6 col-sm-12 col-xs-12">
            <img src="<?php echo base_url(); ?>images/surveyFeed.jpg"  class="img-centered img-fluid" style="height:100px;" alt="login-image">
        </div>
    </div>

    <div class="row m-3">
        <?php if (session()->getFlashdata('response') !== NULL) : ?>
            <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
        <?php endif; ?>

        <?php if (isset($validation)) : ?>
            <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('RESULT_TextField') ?></p>
            <p style="color:red; font-size:18px;" align="center"><?= $validation->showError('RESULT_TextField1') ?></p>
        <?php endif; ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
        <form class="form-Centered sign-in" action="<?= base_url('createsurveyanswer') ?>" method="post" id="contact_form">

                    <div class="mb-4 row">
                    <input type="hidden" id="emailid" name="emailid" value="<?php echo $getSurveyData['email_id']; ?>">
                    <input type="hidden" id="surveyid" name="surveyid" value="<?php echo $getSurveyData['campaignId']; ?>">
                    <input type="hidden" id="userid" name="userid" value="<?php echo $getSurveyData['userData']['id']; ?>">
                    <input type="hidden" id="tenantid" name="tenantid" value="<?php echo $getSurveyData['tenantData']['tenant_id']; ?>">
                    <input type="hidden" id="randomkey" name="randomkey" value="<?php echo isset($randomKey) ? $randomKey : ''; ?>">
                    <?php foreach($getSurveyData['questionlist'] as $key => $questiondata) { ?>
                    <div class="row ml-4 q">
                        <div class="col-md-12">
                            <label><b><?php echo stripslashes($questiondata['question_name']); ?></b></label>

                        </div>
                        <div class="col-md-12">
                        <input type="hidden" id="question_<?php echo $key; ?>" name="question[<?php echo $key; ?>]" value="<?php echo $questiondata['question_id']; ?>">
                        <?php if($questiondata['info_details'] == 'nps') { ?>
                            <table class="number_scale" id="firstquestion">
                                <tbody>                                    
                                    <tr>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_0" value="0"><label for="RESULT_TextField-<?php echo $key; ?>_0"><span class="number">0</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_1" value="1"><label for="RESULT_TextField-<?php echo $key; ?>_1"><span class="number">1</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_2" value="2"><label for="RESULT_TextField-<?php echo $key; ?>_2"><span class="number">2</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_3" value="3"><label for="RESULT_TextField-<?php echo $key; ?>_3"><span class="number">3</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_4" value="4"><label for="RESULT_TextField-<?php echo $key; ?>_4"><span class="number">4</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_5" value="5"><label for="RESULT_TextField-<?php echo $key; ?>_5"><span class="number">5</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_6" value="6"><label for="RESULT_TextField-<?php echo $key; ?>_6"><span class="number">6</span></label></td>
                                        <td class=""><input type="radio" name="RESULT_TextField" class="multiple_choice number_field animate" id="RESULT_TextField-<?php echo $key; ?>_7" value="7"><label for="RESULT_TextField-<?php echo $key; ?>_7"><span class="number">7</span></label></td>
                                        <td class="highlight"><input type="radio" name="RESULT_TextField" class="multiple_choice number_field animate" id="RESULT_TextField-<?php echo $key; ?>_8" value="8"><label for="RESULT_TextField-<?php echo $key; ?>_8"><span class="number">8</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_9" value="9"><label for="RESULT_TextField-<?php echo $key; ?>_9"><span class="number">9</span></label></td>
                                        <td><input type="radio" name="RESULT_TextField" class="multiple_choice number_field" id="RESULT_TextField-<?php echo $key; ?>_10" value="10"><label for="RESULT_TextField-<?php echo $key; ?>_10"><span class="number">10</span></label></td>
                                    </tr>
                                    <tr>
                                        <td colspan="11">
                                        <div class="number_scale_label_left">Not Likely</div>
                                        <div class="number_scale_label_right">Very Likely</div>
                                        </td>
                                    </tr>             

                                    <div style="color:red; display:none;" id="errorquestion"></div>
                                </tbody>
                            </table>
                        <?php  } ?> 
                        </div>
                    </div>                   
                    <?php  } ?>
                    <div class="row mt-2 q" id ="next_question">
                        <div class="col-md-12">
                            <label id ="update_quest_title"></label>
                            <input type="hidden" id="question2" name="question[]" value="">
                        </div>
                        <div class="col-md-10 mb-4">
                            <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="RESULT_TextField1[]" id="next_questionsel" multiple>
                                
                            </select>
                            <div style="color:red; display:none;" id="errorquestion2"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" id="submitbtn" class="btn btn-success">Submit</button>
                    </div>
                    </form>

                    </div>
            </div>
        


    </div>
     
<script type="text/javascript">
$(document).ready(function(){
    $("form").submit(function(e){
            var getradio = document.querySelector( 'input[name="RESULT_TextField"]:checked');   
            var getselect = document.getElementById("next_questionsel").value;   
            if(getradio == null)   {
                $("#errorquestion").show();
                $("#errorquestion").html("Please select one rating for verification");
                e.preventDefault();
            } else if(getselect == "") {
                $("#errorquestion2").show();
                $("#errorquestion2").html("Please give any feedback for this question");
                e.preventDefault();
            } else{
                console.log("your feedback has been recorded");   
            }
    });
        $("#next_question").hide();
        $("input[name='RESULT_TextField']").change(function(){ 
            var radioValue = $(this).val();
            $("#errorquestion").hide();

            $("#next_questionsel option").remove();
            $("#update_quest_title").html("");
            console.log(radioValue);
            $.ajax({  
                url:'<?php echo base_url('getquestionnext'); ?>',
                type: 'post',
                dataType:'json',
                data: { id : "<?php echo $getSurveyData['userData']['id']; ?>", "QandA1" : radioValue },
                success: function(response){
                    var responsedata = response.query;
                    if(responsedata){
                        var title = responsedata[0] ? responsedata[0].question_name : "";
                        var options = responsedata[0] ? responsedata[0].other_option : "";
                        var question_id = responsedata[0] ? responsedata[0].question_id : "";                    
                        var optionsrest  = options ? JSON.parse(options) : "";
                        $("#update_quest_title").html("<b>"+title.stripSlashes()+"</b>");
                        $.each(optionsrest, function(key, item) {
                            $('#next_questionsel').append($('<option>').val(item).text(item));                                         
                        });
                        $("#question2").val(question_id);
                        (title) ? $("#next_question").show() : $("#next_question").hide();                                    
                    }
                },
                error: function(response){
                    console.log(response);
                } 
            });
        });
        String.prototype.stripSlashes = function(){
            return this.replace(/\\(.)/mg, "$1");
        }
    });
</script>
<?= $this->endSection() ?>