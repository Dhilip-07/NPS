<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TenantModel;
use App\Models\QuestionModel;
use App\Models\SurveyModel;

class SurveyController extends BaseController
{
    public function index()
    {
        $model = new QuestionModel();  
        $questionList = $model->where('user_id', session()->get('id'))->find();       
        return view('admin/createsurvey', ["getQuestData" => $questionList]);
    }
    public function createSurvey(){
        $data = [];

        $model = new QuestionModel();  
        $questionList = $model->where('user_id', session()->get('id'))->find();       
        
        if ($this->request->getMethod() == 'post') {
                $rules = [
                    'suerveyname' => 'required|min_length[2]|max_length[200]',
                ];
                $errors = [
                    'suerveyname' => [
                        'required' => 'You must choose a suerveyname.',
                    ]
                ];
         
            if (!$this->validate($rules, $errors)) {

                return view('admin/createsurvey', [
                    "validation" => $this->validator, "getQuestData" => $questionList
                ]);
            } else {
                $model = new TenantModel();
                $tenant = $model->where('tenant_name', session()->get('tenant_name'))->first();
                $userId = session()->get('id');
                $surv_id = $this->insertSurvery($this->request->getPost(),$userId);
                if($tenant['tenant_id'] > 1) {
                    $this->tenantInsertSurvery($this->request->getPost(),$tenant,$surv_id, $userId);
                }
                session()->setFlashdata('response',"Create new Survey Successfully");
                return redirect()->to(base_url('surveyList'));
            }
        }
        return view('admin/createsurvey', ["getQuestData" => $questionList]);

    }
    public function insertSurvery($postData, $userId) 
    {
        $model = new SurveyModel();  
        $data = [
            "campain_name" => $this->escapeString($postData["suerveyname"]),
            "question_id_1" => $postData["quest_1"],
            "question_id_2" => $postData["quest_2"],
            "user_id" => $userId
        ];
        $result = $model->insertBatch([$data]);
        $db = db_connect();        
        $surv_id = $db->insertID();
        return $surv_id;
    }

    public function updateSurvery($postData, $surv_id) 
    {
        $model = new SurveyModel();  
        $data = [
            "campain_name" => $this->escapeString($postData["suerveyname"]),
            "question_id_1" => $postData["quest_1"],
            "question_id_2" => $postData["quest_2"],
        ];
        $model->update($surv_id,$data);
    }
    public function tenantInsertSurvery($postData, $tenantdata, $surv_id, $userId){

        $dbname = "nps_".$tenantdata['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $data = [
            "campign_id" => $surv_id,
            "campain_name" => $this->escapeString($postData["suerveyname"]),
            "question_id_1" => $postData["quest_1"],
            "question_id_2" => $postData["quest_2"],
            "user_id" => $userId
        ];
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_insert_user ="INSERT INTO ".$dbname.".nps_survey_details ( ". implode(',' , $key) .") VALUES('". implode("','" , $values) ."')";
        $db->query($new_db_insert_user);
    }
    public function tenantUpdateSurvery($postData, $tenantdata, $surv_id){

        $dbname = "nps_".$tenantdata['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $cols = array();
        $data = [
            "campain_name" => $this->escapeString($postData["suerveyname"]),
            "question_id_1" => $postData["quest_1"],
            "question_id_2" => $postData["quest_2"],
        ];
        foreach($data as $key=>$val) {
            $cols[] = "$key = '$val'";
        }

        $new_db_update_user ="UPDATE  ".$dbname.".`nps_survey_details` SET " . implode(', ', $cols) . " WHERE `nps_survey_details`.`campign_id` = ".$surv_id;
        $db->query($new_db_update_user);
    }
    public function surveyList(){
        $model = new SurveyModel();    
        $surveryList = $model->where('user_id', session()->get('id'))->find();    
        return view('admin/surveyList', ["surveryList" => $surveryList]);
    }
    public function editsurvey($surv_id) {
        $model = new SurveyModel();    
        $getSurveyData = $model->where('campign_id', $surv_id)->first();
        $model = new QuestionModel();  
        $questionList = $model->where('user_id', session()->get('id'))->find();    

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'suerveyname' => 'required|min_length[2]|max_length[200]',
            ];
            $errors = [
                'suerveyname' => [
                    'required' => 'You must choose a suerveyname.',
                ]
            ];
     
        if (!$this->validate($rules, $errors)) {

            return view('admin/editsurvey', [
                "validation" => $this->validator,"getQuestData" => $questionList
            ]);
        } else {
            $model = new TenantModel();
            $tenant = $model->where('tenant_name', session()->get('tenant_name'))->first();
            $userId = session()->get('id');
            $survid = $this->updateSurvery($this->request->getPost(),$surv_id);
            if($tenant['tenant_id'] > 1) {
                $this->tenantUpdateSurvery($this->request->getPost(),$tenant,$surv_id);
            }
            session()->setFlashdata('response',"Update Survey Successfully");
            return redirect()->to(base_url('surveyList'));
        }
    }
        return view('admin/editsurvey',  ["getSurveyData" => $getSurveyData, "getQuestData" => $questionList]);
    }
    public function deletesurvey($surv_id){
        $model = new SurveyModel();    
        $getSurveyData = $model->where('campign_id', $surv_id)->delete();  
        $modeldel = new TenantModel();
        $tenant = $modeldel->where('tenant_name', session()->get('tenant_name'))->first();

        if($tenant['tenant_id'] > 1) {
            $this->tenantDeleteSurvery($tenant,$surv_id);
        }
        session()->setFlashdata('response',"Survey deleted Successfully");
        return redirect()->to(base_url('surveyList'));
    }
    public function tenantDeleteSurvery($tenantdata, $surv_id) {
        $dbname = "nps_".$tenantdata['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $delete_query  = "DELETE FROM ".$dbname.".`nps_survey_details` WHERE `nps_survey_details`.`campign_id` =". $surv_id;
        $db->query($delete_query);
    }
    public function escapeString($val) {
        $db = db_connect(); 
        $connectionId = $db->connID;
        $val = mysqli_real_escape_string($connectionId, $val);
        return $val;
    }
}
