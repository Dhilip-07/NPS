<?php $logo_img  = session()->getFlashdata('logo_update') ? base_url().session()->getFlashdata('logo_update'): session()->get('logo_update'); 
    $logo_img = ($logo_img == '') ?  'images/logo.png' : $logo_img; 
    ?>
<nav class="sidebar">
        <header>
            <div class="image-text">
                <div class="text logo-text">
                <?php $imageProperties = ['src' => $logo_img,'class' => 'img-fluid', "style" => "height: 40px;",'alt' => 'Hair Component']; 
                            echo img($imageProperties); ?>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="menu-head ">
                    <h6>Customer Satisfaction
                        Survey </h6>
                </li>

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search...">
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="<?php echo site_url('admin'); ?>?filter=no">
                            <?php $imageProperties = ['src' => 'images/majesticons_home.svg','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <?php if (session()->get('tenant_id') == 1) { ?>
                    <li class="nav-link">
                        <a href="<?php echo site_url('createtenant'); ?>">
                            <?php $imageProperties = ['src' => 'images/majesticons_home.svg','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Create Tenant</span>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="nav-link">
                        <a href="<?php echo site_url('questionList'); ?>">
                        <?php $imageProperties = ['src' => 'images/Answers.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">View Question</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="<?php echo site_url('answerList'); ?>">
                        <?php $imageProperties = ['src' => 'images/Answers.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">View Answer</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="<?php echo site_url('surveyList'); ?>">
                        <?php $imageProperties = ['src' => 'images/Answers.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">View Survey</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?php echo site_url('overallreport'); ?>">
                        <?php $imageProperties = ['src' => 'images/Investment Portfolio.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Reports</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="#">
                        <?php $imageProperties = ['src' => 'images/World.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Geo-Matrix</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="<?php echo site_url('SurveyResponse'); ?>">
                        <?php $imageProperties = ['src' => 'images/Answers.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Survey Report</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?php echo site_url('emailtemplate'); ?>">
                        <?php $imageProperties = ['src' => 'images/Send Email.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">send E-mail</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?php echo site_url('getCustomerData'); ?>">
                        <?php $imageProperties = ['src' => 'images/Search Client.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                            <span class="text nav-text">Contact Details</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <hr>
                <li class="nav-link">
                    <a href="#">
                    <?php $imageProperties = ['src' => 'images/Technical Support.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                        <span class="text nav-text">Support</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="<?php echo site_url('settingpage'); ?>">
                    <?php $imageProperties = ['src' => 'images/Settings.svg','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                        <span class="text nav-text">Setting</span>
                    </a>
                </li>
                <li class="Username">
                    <a href="<?php echo site_url('userprofile'); ?>">
                    <?php $imageProperties = ['src' => 'images/user-icon.png','class' => 'img-fluid']; 
                            echo img($imageProperties); ?>
                        <span class="text nav-text">
                            <h6>
                                <?= session()->get('firstname')." ".session()->get('lastname') ?>
                            </h6>
                            <h6>
                            <?= session()->get('email'); ?>
                            </h6>
                        </span>
                        </a>    
                        <a class="btn bx bx-log-out icon" href="<?php echo site_url('logout'); ?>"></a>
                    
                </li>
    


            </div>
        </div>

    </nav>
      