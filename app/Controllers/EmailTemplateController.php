<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TenantModel;
use App\Models\QuestionModel;
use App\Models\SurveyModel;
use App\Models\ExternalcontactsModel;
use App\Models\CreatecontactsModel;
use App\Models\AnswercreateModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class EmailTemplateController extends BaseController
{
    public function index()
    {
        $externalList = $this->getSurveycontactdata();
        $model = new SurveyModel();  
        $getSurvey = $model->where('user_id', session()->get('id'))->find(); 
        return view('admin/emailtemplate',["getSurvey" => $getSurvey,"externalList" => $externalList]);
    }
    public function getSurveycontactdata(){
        if(session()->get('tenant_id') == 1 ){
            $model = new ExternalcontactsModel(); 
            return $externalList = $model->where('created_by', session()->get('id'))->find();
        } else {
            $dbname = "nps_".session()->get('tenant_name');     
            //new DB creation for Tenant details
            $db = db_connect();
            $db->query('USE '.$dbname);
            $new_db_select ="SELECT * FROM ".$dbname.".nps_external_contacts ";
            $result = $db->query($new_db_select);
            if(count($result->getResult()) > 0) {
               return $externalList =json_decode(json_encode($result->getResult()),true);
            }
        } 
    }
    public function uploadFile(){
        $input = $this->validate([
            'formData' => 'uploaded[formData]|max_size[formData,2048]|ext_in[formData,csv]'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
            echo json_encode(['failed'=> $data, 'csrf' => csrf_hash() ]);
        }else {
            if($file = $this->request->getFile('formData')) {
                if ($file->isValid() && ! $file->hasMoved()) {                    
                    $newName = $file->getRandomName();
                    $file->move('../public/csvfile', $newName);
                    $file = fopen("../public/csvfile/".$newName,"r");
                    $i = 0;
                    $numberOfFields = 5;
                    $csvArr = array();
                    while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                        $num = count($filedata);
                        if($i > 0 && $num == $numberOfFields){ 
                            $csvArr[$i]['name'] = $filedata[0];
                            $csvArr[$i]['firstname'] = $filedata[1];
                            $csvArr[$i]['lastname'] = $filedata[2];
                            $csvArr[$i]['contact'] = $filedata[3];
                            $csvArr[$i]['email'] = $filedata[4];
                        }
                        $i++;
                    }
                    fclose($file);
                    $count = 0;
                    $emaillist= array();
                    foreach($csvArr as $exportData){
                        $validateEmail = $this->getExternalContact($exportData["email"]);
                        if($validateEmail) {
                            $tenant  = [
                                "tenant_id" => session()->get('tenant_id'),
                                "tenant_name" =>  session()->get('tenant_name')
                            ];
                            $data = [
                                "name" => $exportData["name"],
                                "firstname" => $exportData["firstname"],
                                "lastname" => $exportData["lastname"],
                                "contact_details" => $exportData["contact"],
                                "email_id" => $exportData["email"],
                                "created_by" => session()->get('id')
                            ];
                            if($tenant['tenant_id'] == 1) {
                                $this->createContact($data); 
                            } else {
                                $this->tenantCreateContact($data, $tenant);
                            }                                             
                            array_push($emaillist, $exportData["email"]);
                        }
                    }
                    $return_email = implode("," , $emaillist);
                    echo json_encode(['success'=> "success", 'csrf' => csrf_hash(), "query" =>  $return_email]);                    
                }
            }
        }
    }
    public function getExternalContact($emailid){
        if(session()->get('tenant_id') > 1 ){
            $dbname = "nps_".session()->get('tenant_name');     
            //new DB creation for Tenant details
            $db = db_connect();
            $db->query('USE '.$dbname);
            $new_db_select ="SELECT * FROM ".$dbname.".nps_external_contacts  WHERE `nps_external_contacts`.`email_id` = '". $emailid."'";
            $result = $db->query($new_db_select);
            if(count($result->getResult()) > 0) {
                return false;
            }
            return true;
        }else {
            $model = new ExternalcontactsModel();
            $multiClause = array('email_id' => $emailid);
            $contactlist = $model->where($multiClause)->first();
            if($contactlist){
                return false;
            }
            return true;
        }
    }
    public function createContact($exportData){

        $model = new ExternalcontactsModel();
        $multiClause = array('email_id' => $exportData['email_id']);
        $contactlist = $model->where($multiClause)->first();
        if(!$contactlist){
            $model = new ExternalcontactsModel();
            $result = $model->insertBatch([$exportData]);                 
        }
    }
    public function tenantCreateContact($exportData, $tenantdata) {
        $dbname = "nps_".$tenantdata['tenant_name'];     
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $new_db_select ="SELECT * FROM ".$dbname.".nps_external_contacts  WHERE `nps_external_contacts`.`email_id` = '". $exportData['email_id']."'";        
        $result = $db->query($new_db_select);
        if(count($result->getResult()) == 0) {
            $key = array_keys($exportData); 
            $values = array_values($exportData); 
            $new_db_insert_user ="INSERT INTO ".$dbname.".nps_external_contacts ( ". implode(',' , $key) .") VALUES('". implode("','" , $values) ."')";
            $db->query($new_db_insert_user);
        }
    }
    public function sendEmail(){
        $externalList = $this->getSurveycontactdata();
        $model = new SurveyModel();  
        $getSurvey = $model->where('user_id', session()->get('id'))->find(); 
        if ($this->request->getMethod() == 'post') {
            $userId = session()->get('id');
            $tenant  = [
                "tenant_id" => session()->get('tenant_id'),
                "tenant_name" =>  session()->get('tenant_name')
            ];
            if($tenant['tenant_id'] == 1) {
                $this->createMailTemplate($this->request->getPost(),$userId);
            } else {
                $this->createMailTemplatesubTenant($this->request->getPost(),$userId, $tenant);
            }
            foreach($this->request->getPost('checkoutemail') as $listMail) {
                $emailstatus = $this->createTemplateForSurvey($this->request->getPost(),$userId, $listMail, $tenant);
            }
            session()->setFlashdata('response',$emailstatus);
            return redirect()->to(base_url('emailtemplate'));
        }
        return view('admin/emailtemplate',["getSurvey" => $getSurvey,"externalList" => $externalList]);
    }

    public function createTemplateForSurvey($postdata, $userId, $email, $tenant){
        $whitelist = array( '127.0.0.1','::1');
        $model = new ExternalcontactsModel();
        $multiClause = array('email_id' => $email);
        $contactlist = $model->where($multiClause)->first();
        $surveyrandom = $this->randomSurvey();
        $template = view("template/email-template-survey", [ "userId" => $userId, "postdata" => $postdata,"contactdata" => $contactlist, "tenantdata" => $tenant, "surveyrandom" => $surveyrandom]); 
        $mail = new PHPMailer(true);      
        $subject =  $postdata['subject'] ? $postdata['subject']  :"What did you think about NPS";    
        try {
            if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){

                $mail->isSMTP();  
                $mail->addCustomHeader('MIME-Version: 1.0');
                $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
                $mail->SMTPDebug    = 1;
                $mail->Host         = 'smtp.gmail.com'; //smtp.google.com
                $mail->SMTPAuth     = true;     
                $mail->Username     = 'hctoolssmtp@gmail.com';  
                $mail->Password     = 'iyelinyqlqdsmhro';
                $mail->SMTPSecure   = 'tls';  
                $mail->Port         = 587;  
                $mail->Subject      = $subject;
                $mail->Body         = $template;
                $mail->setFrom('hctoolssmtp@gmail.com', 'CI-NPS');
                $mail->addAddress($email);  
                $mail->isHTML(true);      
                $response = $mail->send();
            } else {
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= 'From: <jeyprabu@hctools.in>' . "\r\n";
                $response = mail($email,$subject,$template, $headers);
             }    
            if(!$response) {
                return "Something went wrong. Please try again.". $mail->ErrorInfo;
            }
            else {
                return "Email sent successfully.";
            }
        } catch (Exception $e) {
            return "Something went wrong." . $mail->ErrorInfo;
        }
    

    }
    public function createMailTemplate($postData, $userId) 
    {
        $List = implode(', ', $postData["checkoutemail"]);
        $model = new CreatecontactsModel();  
        $data = [
            "subject" =>  $postData['subject'] ? $postData['subject']  :"What did you think about NPS",
            "survey_id" => $postData["survey"],
            "email_list" => $List,
            "message" => $postData["editor"],
            "user_id" => $userId
        ]; 
        $result = $model->insertBatch([$data]);
    }
    public function createMailTemplatesubTenant($postData, $userId, $tenant) {
        $dbname = "nps_".$tenant['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $List = implode(', ', $postData["checkoutemail"]);
        $data = [
            "subject" =>   $postData['subject'] ? $postData['subject']  :"What did you think about NPS",
            "survey_id" => $postData["survey"],
            "email_list" => $List,
            "message" => $postData["editor"],
            "user_id" => $userId
        ];
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_insert_user ="INSERT INTO ".$dbname.".nps_email_send_list ( ". implode(',' , $key) .") VALUES('". implode("','" , $values) ."')";
        $db->query($new_db_insert_user);

    }
    public function getSurveyAnwser($email, $survey_id, $userid, $tenantid, $randomKey)
    {
        $model = new AnswercreateModel();
        $returnkey = $model->where('mail_status', $randomKey)->first();
        $email = urldecode($email);
        if($returnkey ==''){
            if($tenantid > 1) {
                $getSurveyData = $this->getcollectionsubtenant(trim($email), $survey_id, $userid, $tenantid, $randomKey);
            } else {
                $getSurveyData = $this->getcollection(trim($email), $survey_id, $userid, $tenantid, $randomKey);
            }
            $data =  ["logo_user" => $getSurveyData['userData']['logo_update']];
            session()->set($data);
            return view('validateanswer',["getSurveyData" => $getSurveyData,"randomKey" => $randomKey]);
        } else {
            $response = "Your survey feedback already added. Please check with Admin";
            session()->setFlashdata('response',$response);
            return view('thankyou');
        }
    }
    public function getcollectionsubtenant($email, $survey_id, $userid, $tenantid) {
        $model = new TenantModel();
        $tenant = $model->where('tenant_id', $tenantid)->first();
        $dbname = "nps_".$tenant['tenant_name'];     
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $multiClause ="SELECT * FROM ".$dbname.".nps_external_contacts  WHERE `nps_external_contacts`.`email_id` = '". $email."' AND `nps_external_contacts`.`created_by` = '". $userid."'";
        $externalcount = $db->query($multiClause);
        if(count($externalcount->getRowArray()) >0) {
            $externalList = $externalcount->getRowArray();
        }
        $multiClause2 ="SELECT * FROM ".$dbname.".nps_survey_details  WHERE `nps_survey_details`.`campign_id` = '". $survey_id."' AND `nps_survey_details`.`user_id` = '". $userid."'";
        $getSurveycount = $db->query($multiClause2);
        if(count($getSurveycount->getRowArray()) >0) {
            $getSurvey = $getSurveycount->getRowArray();
        }
        $multiClause3 ="SELECT * FROM ".$dbname.".nps_question_details  WHERE `nps_question_details`.`user_id` = '". $userid."' AND `nps_question_details`.`question_id` = '". $getSurvey['question_id_1']."'";
        $getquestion1list = $db->query($multiClause3);
        if(count($getquestion1list->getRowArray()) >0) {
            $getquestion1 = $getquestion1list->getRowArray();
        }
        $multiClause5 ="SELECT * FROM ".$dbname.".nps_users  WHERE `nps_users`.`id` = '". $userid."' AND `nps_users`.`tenant_id` = '". $tenantid."'";
        $getuserlist = $db->query($multiClause5);
        if(count($getuserlist->getRowArray()) >0) {
            $user = $getuserlist->getRowArray();
        }
        $db->close(); 
        $questioncollection = array();
        array_push($questioncollection, $getquestion1);

        $getSurveyData  = [
            "email_id" => $externalList['email_id'],
            "contactId" => $externalList['id'],
            "contactname" => $externalList['name'],
            "campaignId" => $getSurvey['campign_id'],
            "campaignname" => $getSurvey['campain_name'],
            "questionlist" => $questioncollection,
            "userData"  => $user,
            "tenantData" => $tenant
        ];
        return $getSurveyData;
        
    }
    public function getcollection($email, $survey_id, $userid, $tenantid){
        $model = new ExternalcontactsModel(); 
        $multiClause = array('created_by' => $userid,'email_id' => $email);
        $externalList = $model->where($multiClause)->first();    
        $model = new SurveyModel();  
        $multiClause2 = array('user_id' => $userid,'campign_id' => $survey_id);
        $getSurvey = $model->where($multiClause2)->first(); 
        $model = new QuestionModel();
        $multiClause3 = array('user_id' => $userid,'question_id' => $getSurvey['question_id_1']); 
        $getquestion1 = $model->where($multiClause3)->first(); 
        $questioncollection = array();
        array_push($questioncollection, $getquestion1);
        $model = new UserModel();
        $multiClause5 = array('id' => $userid,'tenant_id' => $tenantid); 
        $user = $model->where($multiClause5)->first();
        $model = new TenantModel();
        $tenantDetails = $model->where('tenant_id',  $user['tenant_id'])->first();

        $getSurveyData  = [
            "email_id" => $externalList['email_id'],
            "contactId" => $externalList['id'],
            "contactname" => $externalList['name'],
            "campaignId" => $getSurvey['campign_id'],
            "campaignname" => $getSurvey['campain_name'],
            "questionlist" => $questioncollection,
            "userData"  => $user,
            "tenantData" => $tenantDetails
        ];
        return $getSurveyData;
    }
    public function randomSurvey() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%&*";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public function createsurveyanswer(){
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'RESULT_TextField' => 'required|numeric',
                'RESULT_TextField1' => 'required',
            ];
            $errors = [
                'RESULT_TextField' => [
                    'required' => 'You must choose a first question.',
                ],
                'RESULT_TextField1' => [
                    'required' => 'You must choose a second question.',
                ]
            ];
            
            if($this->request->getPost('tenantid') > 1) {
                $getSurveyData = $this->getcollectionsubtenant($this->request->getPost('emailid'),$this->request->getPost('surveyid'), $this->request->getPost('userid'),$this->request->getPost('tenantid'));
            } else {
                $getSurveyData = $this->getcollection($this->request->getPost('emailid'),$this->request->getPost('surveyid'), $this->request->getPost('userid'),$this->request->getPost('tenantid'));
            }
            
             if (!$this->validate($rules, $errors)) {
                return view('validateanswer', [
                    "validation" => $this->validator,
                    "getSurveyData" => $getSurveyData,
                    "randomKey" => $this->request->getPost("randomkey")
                ]);
            } else {
                $model = new AnswercreateModel();
                $returnkey = $model->where('mail_status', $this->request->getPost("randomkey"))->first();
                if($returnkey == '') {
                    $answer = array();
                    $answer2 = implode(",", $this->request->getPost("RESULT_TextField1"));
                    array_push($answer, $this->request->getPost("RESULT_TextField"), $answer2);
                    $data = [
                        "campign_id" => $this->request->getPost("surveyid"),
                        "email" => $this->request->getPost("emailid"),
                        "user_id" => $this->request->getPost("userid"),
                        "question_id" => $this->request->getPost("question")[0],
                        "answer_id" => $answer[0],
                        "question_id2" => $this->request->getPost("question")[1],
                        "mail_status" => $this->request->getPost("randomkey"),
                        "answer_id2" => $answer[1],
                        "ip_details" => getHostByName(getHostName())
                    ];
                    $model = new AnswercreateModel();
                    $result = $model->insertBatch([$data]);
                    $db = db_connect();        
                    $surveyresponseId = $db->insertID();
                    $data['id'] = $surveyresponseId;
                    if($this->request->getPost('tenantid') > 1) {
                        $this->AnswerReponseforSubTenant($this->request->getPost(),$data);
                    }
                    $response = "Your survey feedback has beed recorded.";
                } else {
                    $response = "Your survey feedback already added. Please check with Admin";

                }
                session()->setFlashdata('response',$response);
                return view('thankyou', ["getSurveyData" => $getSurveyData]);
            }

        }

    }
    public function AnswerReponseforSubTenant($postdata, $data){
        $model = new TenantModel();
        $tenant = $model->where('tenant_id', $postdata['tenantid'])->first();
        $dbname = "nps_".$tenant['tenant_name'];  
        $db = db_connect();
        $db->query('USE '.$dbname);   
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_insert_user ="INSERT INTO ".$dbname.".nps_survey_response ( ". implode(',' , $key) .") VALUES('". implode("','" , $values) ."')";
        $db->query($new_db_insert_user);
        $db->close(); 
    }
    public function getquestionnext(){
        $output="Ajax request Success.";
        if ($this->request->isAJAX()) {
            $query = service('request')->getPost();
            $userId = $query['id'];
            $QandA1 = $query['QandA1'];
            if($QandA1 >8) {
                $priority = 3;
            } else if($QandA1 <9 && $QandA1 > 6) {
                $priority = 2;
            } else {
                $priority = 1;
            }
            $model = new QuestionModel();
            $multiClause3 = array('user_id' => $userId, "info_details" => 'other','priority' => $priority ); 
            $getquestion1 = $model->where($multiClause3)->find(); 
            echo json_encode(['success'=> $output, 'csrf' => csrf_hash(), 'query' => $getquestion1]);
        }
    }
}
