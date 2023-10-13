<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AnswercreateModel;
use App\Models\ExternalcontactsModel;
use App\Models\SurveyResponseModel;
use App\Models\CreatecontactsModel;
use App\Models\TenantModel;

class AdminController extends BaseController
{
    public function __construct()
    {
        if (session()->get('role') != "admin") {
            echo 'Access denied';
            exit;
        }
    }
    public function index()
    {
        $tenantId = ($this->request->getGet("tenantId") != '') ? $this->request->getGet("tenantId") :  session()->get('tenant_id');
        $userId = array();
        $model = new TenantModel();  
        $getTenantdata = $model->findall(); 
        // Filter concept
        $selectTenant = '';
        $selectRange = '';
        $selectfilter = '';
        $detractorsArray1  = array();
        $passivesArray1   = array();
        $promotersArray1  = array();        
        $getResponseCount  = array();        
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
        $daterange = '';
        $comparestartdate = '';
        $compareenddate = '';     
        $datacompare = array();
        $startDate = date('Y-m-d H:i:s', strtotime(' - 32 days'));
        $endDate  = date("Y-m-d H:i:s");
        if($this->request->getGet("daterange") != '' ) {
            $daterange = explode("_" , $this->request->getGet("daterange"));
            $startDate  =   date("Y-m-d H:i:s", strtotime($daterange[0]));
            $endDate    =   date("Y-m-d H:i:s", strtotime($daterange[1]));
            $returnDate1 = date("d-F-Y", strtotime($daterange[0]));
            $returnDate2 = date("d-F-Y", strtotime($daterange[1]));
            $returnDate = $returnDate1."_".$returnDate2;
            $selectRange = $returnDate;
            $daysdiffer = intval(abs((strtotime($daterange[0]) - strtotime($daterange[1]))/86400));
            $comparestartdate = date("Y-m-d H:i:s", strtotime($daterange[0]."-".$daysdiffer." days "));
            $compareenddate = date("Y-m-d H:i:s", strtotime($daterange[1]."-".$daysdiffer." days -1 second"));         

        }
        if($this->request->getGet("filter")  == "yes"){
            $c_promotersArray1=  array();   
            $c_passivesArray1=  array();   
            $c_detractorsArray1= array();   
            $selectfilter = $this->request->getGet("filter");
            $model = new AnswercreateModel();
            if(is_array($daterange)){
                $model->where("CAST(created_at AS DATE) BETWEEN '$comparestartdate' AND '$compareenddate'");
            }
            $getSurveyDatacompare = $model->whereIn('user_id', $userId)->findall();  
            foreach($getSurveyDatacompare as $key => $getSurveylist) {
                if($getSurveylist['answer_id'] > 8) {
                    array_push($c_promotersArray1, $getSurveylist['answer_id']);
                }            
                if($getSurveylist['answer_id'] <= 8 && $getSurveylist['answer_id'] > 6) {
                    array_push($c_passivesArray1, $getSurveylist['answer_id']);
                }            
                if($getSurveylist['answer_id'] <= 6) {
                    array_push($c_detractorsArray1, $getSurveylist['answer_id']);
                }                
            } 
            $comp_completedData = array_merge($c_promotersArray1, $c_passivesArray1, $c_detractorsArray1);
            $getCompareValue = $this->getcomparesurvey($tenantId, $userId,  $this->request->getGet("tenantId"), $comparestartdate, $compareenddate , $comp_completedData);
            $datacompare = [
                "promoters" => $c_promotersArray1, 
                "passives" => $c_passivesArray1, 
                "detractors" =>  $c_detractorsArray1,
                "getsurveyresponse" => $getCompareValue['getsurveyresponse'],
                "totalresponse" => $getCompareValue['totalresponse'],
                "getfullResponse" => $getCompareValue['getfullResponse'],
                "revenueData" => $getCompareValue['revenueData']               
            ];
        }
        
        $model = new AnswercreateModel();
        $model->where("CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'");
        $getSurveyData = $model->whereIn('user_id', $userId)->findall();  
        foreach($getSurveyData as $key => $getSurveylist) {
            if($getSurveylist['answer_id'] > 8) {
                array_push($promotersArray1, $getSurveylist['answer_id']);
            }            
            if($getSurveylist['answer_id'] <= 8 && $getSurveylist['answer_id'] > 6) {
                array_push($passivesArray1, $getSurveylist['answer_id']);
            }            
            if($getSurveylist['answer_id'] <= 6) {
                array_push($detractorsArray1, $getSurveylist['answer_id']);
            }            
        }
        $getresponseData = array(); 
        if($tenantId == 1){
            $model = new CreatecontactsModel(); 
            $model->where("CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'"); 
            $getresponseData = $model->whereIn('user_id', $userId)->findall();  
        } else {
            $model = new TenantModel();
            $tenant = $model->where('tenant_id', $tenantId)->first();
            $dbname = "nps_".$tenant['tenant_name'];     
            //new DB creation for Tenant details
            $db = db_connect();
            $db->query('USE '.$dbname);
            $userIdlist = implode(",",$userId);  
            $multiClause ="SELECT * FROM ".$dbname.".nps_email_send_list  WHERE `nps_email_send_list`.`user_id` IN ('".$userIdlist."')";
            $multiClause .=" AND CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'";
            $externalcount = $db->query($multiClause);
            if(count($externalcount->getResultArray()) >0) {
                $getresponseData = $externalcount->getResultArray();
            }
            $db->close();    
        }
        $responseOverall = array(); 
        $getsurveylist = array();
        foreach($getresponseData as $key => $overalldata){
            $split_email = explode(",", $overalldata['email_list']);
            foreach($split_email as $splitEmail){
                array_push($responseOverall,$splitEmail);
            }
        }
        $responseOverall = array_unique($responseOverall);
        $promoters = $promotersArray1;
        $passives = $passivesArray1;
        $detractors = $detractorsArray1;
        $completedData = array_merge($promoters, $passives, $detractors);
        // Response Rate
        if($tenantId == 1){
            if($this->request->getGet("tenantId") == '1' || $this->request->getGet("tenantId") == '' ) {
                $model = new ExternalcontactsModel();  
                $model->where("CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'");
                $getcontactdata = $model->whereIn('created_by', $userId)->findall();   
                $model = new SurveyResponseModel();  
                $model->where("CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'");
                $getsurveylist = $model->whereIn('user_id', $userId)->findall(); 
            } else {
                $model = new TenantModel();
                $tenant = $model->where('tenant_id', $this->request->getGet("tenantId"))->first();
                $gettotal = $this->getTotalResponse($tenant, $userId, $startDate, $endDate);
                $getcontactdata = $gettotal['getcontactdata'];
                $getsurveylist = $gettotal['getsurveylist'];
            }
        } else {
            $model = new TenantModel();
            $tenant = $model->where('tenant_id', $tenantId)->first();
            $gettotal = $this->getTotalResponse($tenant, $userId, $startDate, $endDate);
            $getcontactdata = $gettotal['getcontactdata'];
            $getsurveylist = $gettotal['getsurveylist'];
        }
       
        $totalresponse = (is_array($responseOverall)) ? count($responseOverall):'';
        $getsurveyresponse = (is_array($getsurveylist)) ? count($getsurveylist):'';
        $getfullResponse = array_count_values($completedData);
        $revenueData = count($completedData);
        $datacompare["totalresponse"] = $totalresponse;
        $data = [
            "promoters" => $promoters, 
            "passives" => $passives, 
            "detractors" =>  $detractors,
            "getsurveyresponse" => $getsurveyresponse,
            "totalresponse" => $totalresponse,
            "getfullResponse" => $getfullResponse,
            "revenueData" => $revenueData,
            "getTenantdata" => $getTenantdata,
            "selectTenant" => $selectTenant,
            "selectfilter" => $selectfilter,
            "selectRange" => $selectRange
        ];
        return view("admin/dashboard", ["getdashData" => $data, "getDatacomp" => $datacompare]);
    }
    public function getcomparesurvey($tenantId, $userId, $getTenantId, $comp_start, $comp_end, $completedData){
        $returnDate = $comp_start."_".$comp_end;
        if($tenantId == 1){
            if($getTenantId == '1' || $getTenantId == '' ) {
                $model = new ExternalcontactsModel();  
                $model->where("CAST(created_at AS DATE) BETWEEN '$comp_start' AND '$comp_end'");
                $getcontactdata = $model->whereIn('created_by', $userId)->findall();  
                $model = new SurveyResponseModel();  
                $model->where("CAST(created_at AS DATE) BETWEEN '$comp_start' AND '$comp_end'");
                $getsurveylist = $model->whereIn('user_id', $userId)->findall(); 

            } else {
                $model = new TenantModel();
                $tenant = $model->where('tenant_id', $getTenantId)->first();
                $gettotal = $this->getTotalResponse($tenant, $userId, $comp_start, $comp_end);
                $getcontactdata = $gettotal['getcontactdata'];
                $getsurveylist = $gettotal['getsurveylist'];
            }
        } else {
            $model = new TenantModel();
            $tenant = $model->where('tenant_id', $tenantId)->first();
            $gettotal = $this->getTotalResponse($tenant, $userId, $comp_start, $comp_end);
            $getcontactdata = $gettotal['getcontactdata'];
            $getsurveylist = $gettotal['getsurveylist'];
        }
        $totalresponse = (is_array($getcontactdata)) ? count($getcontactdata):'';
        $getsurveyresponse = (is_array($getsurveylist)) ? count($getsurveylist):'';
        $getfullResponse = array_count_values($completedData);
        $revenueData = count($completedData);
        $dataComp = [           
            "getsurveyresponse" => $getsurveyresponse,
            "totalresponse" => $totalresponse,
            "getfullResponse" => $getfullResponse,
            "revenueData" => $revenueData           
        ];
        return $dataComp;
    }
    public function getTotalResponse($tenant, $userId, $startDate, $endDate)
    {

        $dbname = "nps_".$tenant['tenant_name'];     
            //new DB creation for Tenant details
            $db = db_connect();
            $db->query('USE '.$dbname);
            $getcontactdata ='';
            $getsurveylist =array();
            $userIdlist = implode(",",$userId);  
            $multiClause1 ="SELECT * FROM ".$dbname.".nps_external_contacts  WHERE `nps_external_contacts`.`created_by` IN ('".$userIdlist."')";
            $multiClause1 .=" AND CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'";
            $externalcount = $db->query($multiClause1);
            if(count($externalcount->getResultArray()) >0) {
                $getcontactdata = $externalcount->getResultArray();
            }
            $multiClause2 ="SELECT * FROM ".$dbname.".nps_survey_response  WHERE `nps_survey_response`.`user_id` IN ('".$userIdlist."')";
            $multiClause2 .=" AND CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'";
            $externalcount = $db->query($multiClause2);
            if(count($externalcount->getResultArray()) >0) {
                $getsurveylist = $externalcount->getResultArray();
            }

            $db->close(); 
            $data = [
                "getcontactdata" => $getcontactdata,
                "getsurveylist" => $getsurveylist
            ];
            return $data;
    }
    public function ajaxrequest(){
        echo view('ajax-request');
    }

    public function updateRole(){
        $output="Ajax request Success.";
        if ($this->request->isAJAX()) {
            $query = service('request')->getPost();
            $userId = $query['id'];
            $data = [
                "role" => $query['query']
            ];
            $model = new UserModel();
            $model->update($userId,$data);
            var_dump($this->request->getPost('query'));
            echo json_encode(['success'=> $output, 'csrf' => csrf_hash(), 'query ' => $query ]);
        }
        // echo json_encode($output);
    }
}
