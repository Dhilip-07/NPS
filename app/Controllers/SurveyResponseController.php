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

class SurveyResponseController extends BaseController
{
    public function index()
    {
        $model = new TenantModel();  
        $userId = array();
        $getTenantdata = $model->findall(); 
        $selectTenant = '';
        $selectfilter = '';
        if($this->request->getGet("tenantId") == '1' || $this->request->getGet("tenantId") == '' ) {
            $model = new UserModel();
            $userlist = $model->where('tenant_id', session()->get('tenant_id'))->findall();
        }else {
            $model = new UserModel();
            $userlist = $model->where('tenant_id', $this->request->getGet("tenantId"))->findall();
            $selectTenant = $this->request->getGet("tenantId");
        }
        foreach($userlist as $userarray){
            array_push($userId, $userarray['id']);
        }
        $model = new SurveyModel();  
        $getsurveyList = $model->whereIn('user_id', $userId)->find(); 
        $multiClauseold = '';
        if ($this->request->getMethod() == 'post') {
            $model = new SurveyModel();  
            $selectfilter = $this->request->getPost("surveyid");
            $multiClauseold = array('campign_id' => $this->request->getPost("surveyid"));  
            $getsurveyfirst = $model->whereIn('user_id', $userId)->where($multiClauseold)->first();
        } else {
            $getsurveyfirst = $model->whereIn('user_id', $userId)->first(); 
        }

        $camp_id = isset($getsurveyfirst) ? $getsurveyfirst['campign_id'] : '';
        if ($this->request->getMethod() == 'post') {
            $camp_id =  $this->request->getPost("surveyid");
        }
        // nps_survey_response Table for getting response data
        $model = new AnswercreateModel();
        $multiClause = array('campign_id' => $camp_id);  
        $getSurveyData = $model->whereIn('user_id', $userId)->where($multiClause)->orderBy('created_at', 'DESC')->find(); 
        $getfullcollection = array();
        foreach($getSurveyData as $key => $getdata){
            $model = new QuestionModel(); 
            $answer_id = $getdata['answer_id'];
            $getquestionData = $model->where('question_id', $getdata['question_id'])->first(); 
            if($answer_id >8){
                $priority = 3;
            }else if($answer_id < 9 && $answer_id >6){
                $priority = 2;
            } else {
                $priority = 1;
            }
            $getquestionData2 = $model->whereIn('user_id', $userId)->where('priority', $priority)->first(); 

            $model = new ExternalcontactsModel();  
            $getcontactData = $model->where('email_id', $getdata['email'])->first(); 
            $questionData = array();
            array_push($questionData, $getquestionData, $getquestionData2);
            $getSurveycollection = [
                "survey_id" => $getdata['id'],
                "campign_id"=> $getdata['campign_id'],
                "ip_details" => $getdata['ip_details'],
                "answer_id1" => $getdata['answer_id'],
                "answer_id2" => $getdata['answer_id2'],
                "created_at" => $getdata['created_at'],
                "questiondata" => $questionData,
                "userdata" => $getcontactData

            ];            
            array_push($getfullcollection, $getSurveycollection);

        }     
        return view('admin/surveyresponselist',['getSurveyData' =>  $getfullcollection, "getsurveylist" => $getsurveyList, "selectsurvey" => $getsurveyfirst, "getTenantdata" => $getTenantdata, "selectTenant" => $selectTenant]); 
    }
    public function getCustomerList(){
        if(session()->get('tenant_id') == 1){
            $model = new ExternalcontactsModel();    
            $userslist = $model->where('created_by', session()->get('id'))->find();   
        }else {
            $model = new TenantModel();
            $tenant = $model->where('tenant_id', session()->get('tenant_id'))->first();
            $dbname = "nps_".$tenant['tenant_name'];     
            //new DB creation for Tenant details
            $db = db_connect();
            $db->query('USE '.$dbname);
            $multiClause3 ="SELECT * FROM ".$dbname.".nps_external_contacts  WHERE `nps_external_contacts`.`created_by` =". session()->get('id');
            $listuser = $db->query($multiClause3);
            if(count($listuser->getResultArray()) >0) {
            $userslist =  $listuser->getResultArray();
            }
        }    
        return view('admin/getCustomerlist', ["userslist" => $userslist  ]);
    }
   
}
