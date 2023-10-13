'<?= $this->extend("layouts/app") ?>
<?= $this->section("body") ?>

<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/daterangepicker.css') ?>" />
    <section class="home">
        <div class="container">
            <div class="row">

            <?php if(session()->get('tenant_id') == 1) { ?> 
            <div class="col-xl-8 col-lg-8 col-md-8"></div>
            <div class="col-xl-4 col-lg-4 col-md-4 float-right">
                <select class="custom-select form-select custom-select-sm" class="custom-select custom-select-sm" aria-label="Default select example" name="tenant" id="tenantchange">
                <?php foreach($getdashData['getTenantdata'] as $getTenantlist) { ?> 
                    <option value="<?php echo $getTenantlist['tenant_id'] ; ?>" <?php if($getdashData['selectTenant'] ==  $getTenantlist['tenant_id']): ?>selected="selected" <?php endif; ?>><?php echo $getTenantlist['tenant_name'] ; ?></option>
                  <?php  } ?>
                  </select>
            </div>
            <?php } ?>
            </div>
            <div class="row mt-3">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h1>Dashboard - Live</h1>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2">
                <input type="checkbox" class="form-check-input" id="tocompare" name="tocompare" value="yes" <?php if($getdashData['selectfilter']== "yes") {?> checked <?php } ?>>
                 <label class="form-check-label" for="tocompare">To Compare</label>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                    </div>
                </div>
        </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                <canvas id="chart-line" width="299" height="200" class="chartjs-render-monitor" style="display: block; width: 600px; height: 300px;"></canvas>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                <canvas id="chart-line1" width="299" height="200" class="chartjs-render-monitor" style="display: block; width: 600px; height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <p class="response-head-1">NPS Response</p>                   
                    <canvas id="chartId" aria-label="chart" height="350" width="580"></canvas>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-1 row">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                            <table class="table mt-6 ">
                                <thead>
                                    <tr>
                                    <th scope="row"><p class="response-head-1">NPS Summary</p></th>
                                    <?php  if(isset($_GET["filter"]) && $_GET['filter']== 'yes') { ?><th scope="row"><p class="response-head-1">previous</p></th> <?php  } ?>
                                    <th scope="row"><p class="response-head-1">Current</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">
                                    <p class="">Promoters</p>
                                    </td>
                                    <?php  if(isset($_GET["filter"]) && $_GET['filter']== 'yes') { ?>                                    <td scope="row">
                                    <p class="text-center"><?php echo count($getDatacomp['promoters']); ?></p>
                                    </td>
                                    <?php } ?>
                                    <td scope="row">
                                    <p class="text-center"><?php echo count($getdashData['promoters']); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">
                                    <p class="">Passives</p>
                                    </td>
                                    <?php  if(isset($_GET["filter"]) && $_GET['filter']== 'yes') { ?>
                                    <td scope="row">
                                    <p class="text-center"><?php echo count($getDatacomp['passives']); ?></p>
                                    </td>
                                    <?php } ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo count($getdashData['passives']); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">
                                    <p class="">Detractors</p>
                                    </td>
                                    <?php  if(isset($_GET["filter"]) && $_GET['filter']== 'yes') { ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo count($getDatacomp['detractors']); ?></p>
                                    </td>
                                    <?php } ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo count($getdashData['detractors']); ?></p>
                                    </td>
                                </tr>                                       
                                <tr>
                                    <td scope="row">
                                    <p class="">Total Sents</p>
                                    </td>
                                    <?php  if(isset($_GET["filter"]) && $_GET['filter']== 'yes') { ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo $getDatacomp['totalresponse']; ?></p>
                                    </td>
                                    <?php } ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo $getdashData['totalresponse']; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">
                                    <p class="">Total Response</p>
                                    </td>
                                    <?php  if(isset($_GET["filter"]) && $_GET['filter']== 'yes') { ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo $getDatacomp['getsurveyresponse']; ?></p>
                                    </td>
                                    <?php } ?>

                                    <td scope="row">
                                    <p class="text-center"><?php echo $getdashData['getsurveyresponse']; ?></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script  src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>
    <script type="text/javascript">
    $(function() {
        var currentdate = "<?php echo $getdashData['selectRange']; ?>";
        var start =  moment().subtract(32, 'days');
        var end = moment();
        
        if(currentdate){
            var splitdate = currentdate.split("_");
            var start =  moment(new Date(splitdate[0]));
            var end =  moment(new Date(splitdate[1]));
        } 
        function cb(start, end) {
            $('#reportrange span').html(start.format('DD-MMMM-YYYY') + ' - ' + end.format('DD-MMMM-YYYY'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var startdate = picker.startDate.format('YYYY-MM-DD');
            var enddate = picker.endDate.format('YYYY-MM-DD');
            var daterange = startdate+"_"+enddate;
            var newUrl = '';
            var currLoc = $(location).attr('href');
            var hashes =  window.location.href.indexOf("?");    
            if(hashes == -1) {
                var currentUrl = window.location.href +"?daterange="+daterange;
                var urls = new URL(currentUrl);
                newUrl = urls.href; 
            }else {
                var currentUrl = window.location.href;
                var urls = new URL(currentUrl);
                urls.searchParams.set("daterange", daterange); // setting your param
                newUrl = urls.href; 
            }
            console.log(newUrl);
            window.location.href =  newUrl;
        });
    });
    $("#tocompare").change(function(){
        var status= $(this).is( ":checked" );
        var t_id = (status) ? 'yes' : 'no';
        var newUrl = '';
        var currentdate = "<?php echo $getdashData['selectRange']; ?>";
        var start =  moment().subtract(32, 'days');
        var end = moment();        
        if(currentdate){
            var splitdate = currentdate.split("_");
            var start =  moment(new Date(splitdate[0]));
            var end =  moment(new Date(splitdate[1]));
        }
        var currLoc = $(location).attr('href');
        var hashes =  window.location.href.indexOf("?");  
        var startdate = start.format('YYYY-MM-DD');
        var enddate = end.format('YYYY-MM-DD');
        var daterange = startdate+"_"+enddate;  
        if(hashes == -1) {
            var currentUrl = window.location.href +"?filter="+t_id+"&daterange="+daterange;
            var urls = new URL(currentUrl);
            newUrl = urls.href;
        } else {
            var currentUrl = window.location.href;
            var urls = new URL(currentUrl);
            urls.searchParams.set("filter", t_id); // setting your param
            urls.searchParams.set("daterange", daterange); // setting your param
            newUrl = urls.href; 
        }
        window.location.href =  newUrl;
    });
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
    $(document).ready(function() {
        Chart.pluginService.register({
      beforeDraw: function(chart) {
        if (chart.config.options.elements.center) {
          // Get ctx from string
          var ctx = chart.chart.ctx;
          // Get options from the center object in options
          var centerConfig = chart.config.options.elements.center;
          var fontStyle = centerConfig.fontStyle || 'Arial';
          var txt = centerConfig.text;
          var txt2 = centerConfig.text2;
          var state = centerConfig.state;
          var color = centerConfig.color || 'green';
          var maxFontSize = centerConfig.maxFontSize || 75;
          var sidePadding = centerConfig.sidePadding || 20;
          var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2);
          
          // Start with a base font of 30px
          ctx.font = "30px " + fontStyle;

          // Get the width of the string and also the width of the element minus 10 to give it 5px side padding
          var stringWidth = ctx.measureText(txt).width;
          var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

          // Find out how much the font can grow in width.
          var widthRatio = elementWidth / stringWidth;
          var newFontSize = Math.floor(15 * widthRatio);
          var elementHeight = (chart.innerRadius * 2);

          // Pick a new font size so it will not be larger than the height of label.
          var fontSizeToUse = Math.min(newFontSize, elementHeight, maxFontSize);
          var minFontSize = centerConfig.minFontSize;
          var lineHeight = centerConfig.lineHeight || 25;
          var wrapText = false;

          if (minFontSize === undefined) {
            minFontSize = 20;
          }

          if (minFontSize && fontSizeToUse < minFontSize) {
            fontSizeToUse = minFontSize;
            wrapText = true;
          }

          // Set font settings to draw it correctly.
          ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';
          var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
          var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
          
          ctx.beginPath();
          var sub = centerX - 50;
          var hsub = centerY +10;
          ctx.moveTo(sub,hsub);
          var add = centerX +50;
          ctx.lineTo(add,hsub);
          ctx.lineWidth = 1;
          // set line color
          ctx.strokeStyle = 'black';
          ctx.stroke();   
          if (!wrapText) {
            ctx.fillStyle = 'black';
            ctx.font = fontSizeToUse + "px " + fontStyle;
            ctx.fillText(txt, centerX, centerY); 
            if(state == "high"){
                ctx.fillStyle = "#FF6384";
            } else {
                ctx.fillStyle = "#00FF80";
            }      
            ctx.font = "20px " + fontStyle;  
           ctx.fillText(txt2, centerX, centerY+45);
            var image = new Image();
            if(state == "high"){
                image.src = 'images/growth.png';
            } else if(state == "low"){
                image.src = 'images/down.png';
            } else {
                image.src = '';
            }
            image.onload = () => {
            ctx.drawImage(image, centerX+20, centerY+20)
            }            
            return;
          }
          

          var words = txt.split(' ');
          var line = '';
          var lines = [];

          // Break words up into multiple lines if necessary
          for (var n = 0; n < words.length; n++) {
            var testLine = line + words[n] + ' ';
            var metrics = ctx.measureText(testLine);
            var testWidth = metrics.width;
            if (testWidth > elementWidth && n > 0) {
              lines.push(line);
              line = words[n] + ' ';
            } else {
              line = testLine;
            }
          }

          // Move the center up depending on line height and number of lines
          centerY -= (lines.length / 2) * lineHeight;

          for (var n = 0; n < lines.length; n++) {

            ctx.fillText(lines[n], centerX, centerY);
            centerY += lineHeight;
          }
          //Draw text in center

          ctx.fillText(line, centerX, centerY);

        }

        if (chart.data.datasets.length === 0) {
            var ctx = chart.chart.ctx;
            var width = chart.chart.width;
            var height = chart.chart.height
            chart.clear();
            
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = "16px normal 'Helvetica Nueue'";
            ctx.fillText('No data to display', width / 2, height / 2);
            ctx.restore();
        }
      }    
    }); 
    var flag_compare  = "<?php echo isset($_GET["filter"]) ? $_GET['filter'] : ""; ?>"; 
        var promotor  = "<?php echo count($getdashData['promoters']); ?>";
        var passives  = "<?php echo count($getdashData['passives']); ?>";
        var detractors  = "<?php echo count($getdashData['detractors']); ?>";
        var getsurveyresponse  = "<?php echo $getdashData['getsurveyresponse']; ?>";
        var totalresponse  = "<?php echo $getdashData['totalresponse']; ?>";
        // second text value for NPS Score
        var totalNPS = parseInt(promotor) + parseInt(passives)  + parseInt(detractors);
        var totalDet =  Math.round((detractors/totalNPS) * 100);
        // Response Rate Chart details
        var Npsresponse = (totalresponse > 0) ? Math.round((getsurveyresponse/totalresponse) * 100 ): Math.round((getsurveyresponse) * 100);

        var ratestatus, responsestatus = 0;
        var revenuestatus,RRestatus = '';
        var compare_ratio, totalrespreturn = '';
        if(flag_compare == 'yes'){
            // Revenue difference for 2 date option - Compare to option validation
            var c_promotor  = "<?php echo (isset($getDatacomp['promoters']) ?count($getDatacomp['promoters']): 0); ?>";
            var c_passives  = "<?php echo (isset($getDatacomp['passives']) ?count($getDatacomp['passives']): 0); ?>";
            var c_detractors  = "<?php echo (isset($getDatacomp['detractors']) ?count($getDatacomp['detractors']): 0); ?>";
            var c_getsurveyresponse  = "<?php echo (isset($getDatacomp['getsurveyresponse']) ?$getDatacomp['getsurveyresponse']: "");  ?>";
            var c_totalresponse  = "<?php echo (isset($getDatacomp['totalresponse']) ?$getDatacomp['totalresponse']: "");  ?>";
            var c_totalNPS = parseInt(c_promotor) + parseInt(c_passives)  + parseInt(c_detractors);

            var c_totalDet =  Math.round((c_detractors/c_totalNPS) * 100);
            totalNPS = (totalNPS >0) ? totalNPS : 0;
            if(totalNPS > c_totalNPS){
                ratestatus = parseInt(totalNPS)- parseInt(c_totalNPS);
                revenuestatus = 'high';
            }else {
                ratestatus = parseInt(c_totalNPS)- parseInt(totalNPS);
                revenuestatus = 'low';
            }
            compare_ratio = Math.round((ratestatus/totalNPS) * 100);
            // Response Rate Data difference for 2 date option - Compare to option validation
            if(getsurveyresponse > c_getsurveyresponse){
                responsestatus = parseInt(getsurveyresponse)- parseInt(c_getsurveyresponse);
                RRestatus = 'high';
            }else {
                responsestatus = parseInt(c_getsurveyresponse)- parseInt(getsurveyresponse);
                RRestatus = 'low';
            }
            totalrespreturn = (getsurveyresponse >0 ) ? Math.round((responsestatus/getsurveyresponse) * 100) : 0;
            promotor = parseInt(promotor) + parseInt(c_promotor);
            passives = parseInt(passives) + parseInt(c_passives);
            detractors = parseInt(detractors) + parseInt(c_detractors);
            getsurveyresponse = parseInt(getsurveyresponse) + parseInt(c_getsurveyresponse);

        }else {
            compare_ratio = 0;
            revenuestatus= '';
            totalrespreturn= 0;
            RRestatus= '';
        }     
        // NPS Score
        if(promotor == '0' && passives == '0' && detractors == '0'){
            var ctx = $("#chart-line");
            var myLineChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets:''
                },
                options: {
                    legend: {
                    display: false
                    }
                }
            });
        } else {
            var ctx = $("#chart-line");
            var myLineChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [ "Promoters","Passives","Detractors"],
                    datasets: [{
                        data: [promotor, passives, detractors ],
                        backgroundColor: ["darkblue", "pink", "red"]
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Net Promotion Score'
                    },
                    elements: {
                        center: {
                            text:  Number.isNaN(totalDet) ? "0%" : totalDet+ "%",
                            text2: Number.isNaN(compare_ratio) ? "0%" : compare_ratio+ "%",
                            state: revenuestatus,
                            color: '#00FF80', // Default is #000000
                            fontStyle: 'Arial', // Default is Arial
                            sidePadding: 20, // Default is 20 (as a percentage)
                            minFontSize: 25, // Default is 20 (in px), set to false and text will not wrap.
                            lineHeight: 25 // Default is 25 (in px), used for when text wraps
                        }
                    }
                }
            });
        }
        // Response Rate
        if(totalresponse == '0' && getsurveyresponse == "0") {
            var ctx = $("#chart-line1");
            var myLineChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets:''
                },
                options: {
                    legend: {
                    display: false
                    }
                }
            });
        } else {
            var ctx = $("#chart-line1");
            var myLineChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [ "total Data","response Data"],
                    datasets: [{
                        data: [totalresponse, getsurveyresponse ],
                        backgroundColor: ["darkblue", "red"]
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Response Rate'
                    },
                    elements: {
                        center: {
                            text: Number.isNaN(Npsresponse) ? "0%" : Npsresponse+ "%",
                            text2: Number.isNaN(totalrespreturn) ? "0%" : totalrespreturn + "%",
                            state: RRestatus,
                            color: '#FF6384', // Default is #000000
                            fontStyle: 'Arial', // Default is Arial
                            sidePadding: 20, // Default is 20 (as a percentage)
                            minFontSize: 25, // Default is 20 (in px), set to false and text will not wrap.
                            lineHeight: 25 // Default is 25 (in px), used for when text wraps
                        }
                    }
                }
            });
        }
    });
    var flag_compare  = "<?php echo isset($_GET["filter"]) ? $_GET['filter'] : ""; ?>"; 
    var linelabel = ['0','1','2','3','4','5','6','7','8','9','10'];
    if(flag_compare =='yes'){
        var barchartData = ["<?php echo (isset($getdashData['getfullResponse'][0]) ? $getdashData['getfullResponse'][0] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][1]) ? $getdashData['getfullResponse'][1] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][2]) ? $getdashData['getfullResponse'][2] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][3]) ? $getdashData['getfullResponse'][3] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][4]) ? $getdashData['getfullResponse'][4] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][5]) ? $getdashData['getfullResponse'][5] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][6]) ? $getdashData['getfullResponse'][6] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][7]) ? $getdashData['getfullResponse'][7] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][8]) ? $getdashData['getfullResponse'][8] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][9]) ? $getdashData['getfullResponse'][9] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][10]) ? $getdashData['getfullResponse'][10] : ''); ?>"];

        var barDataPrevious = ["<?php echo (isset($getDatacomp['getfullResponse'][0]) ? $getDatacomp['getfullResponse'][0] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][1]) ? $getDatacomp['getfullResponse'][1] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][2]) ? $getDatacomp['getfullResponse'][2] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][3]) ? $getDatacomp['getfullResponse'][3] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][4]) ? $getDatacomp['getfullResponse'][4] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][5]) ? $getDatacomp['getfullResponse'][5] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][6]) ? $getDatacomp['getfullResponse'][6] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][7]) ? $getDatacomp['getfullResponse'][7] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][8]) ? $getDatacomp['getfullResponse'][8] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][9]) ? $getDatacomp['getfullResponse'][9] : ''); ?>",
            "<?php echo (isset($getDatacomp['getfullResponse'][10]) ? $getDatacomp['getfullResponse'][10] : ''); ?>"];
        var barData = {
            labels: linelabel,
            datasets: [{
                label: "NPS Customer Response",
                data: barchartData,
                backgroundColor: 'red'
            },
            {
                label: "NPS- Compare response",
                data: barDataPrevious,
                backgroundColor: 'yellow'

            }
        ],
        }
    }else {
        var barchartData = ["<?php echo (isset($getdashData['getfullResponse'][0]) ? $getdashData['getfullResponse'][0] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][1]) ? $getdashData['getfullResponse'][1] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][2]) ? $getdashData['getfullResponse'][2] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][3]) ? $getdashData['getfullResponse'][3] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][4]) ? $getdashData['getfullResponse'][4] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][5]) ? $getdashData['getfullResponse'][5] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][6]) ? $getdashData['getfullResponse'][6] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][7]) ? $getdashData['getfullResponse'][7] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][8]) ? $getdashData['getfullResponse'][8] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][9]) ? $getdashData['getfullResponse'][9] : ''); ?>",
            "<?php echo (isset($getdashData['getfullResponse'][10]) ? $getdashData['getfullResponse'][10] : ''); ?>"];
        var barData = {
            labels: linelabel,
            datasets: [{
                label: "NPS Customer Response",
                data: barchartData,
                backgroundColor: ['grey', 'red', 'pink', 'brown', 'darkblue', 'lightgreen', 'darkred', 'gold', 'lightblue', 'violet', 'yellow']
            }],
        }
    }
    var chartOptions = {
        responsive: true,  
        legend: {position: "top" },  
        title: { display: true, text: "Chart.js Bar Chart" },  
        scales: {yAxes: [{ ticks: { beginAtZero: true}}] }
    }
    var chrt = document.getElementById("chartId").getContext("2d");
      var chartId = new Chart(chrt, {
         type: 'bar',
         data: barData,
         options: chartOptions,
      });
</script>
    <?= $this->endSection() ?>