<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TenantModel;
use App\Models\QuestionModel;
use App\Models\SurveyModel;
use App\Models\AnswerListModel;

class AnswerlistController extends BaseController
{
    public function index()
    {
        $model = new AnswerListModel();  
        $answerList = $model->where('created_id', session()->get('id'))->find();       
        return view('admin/answerlist', ["getAnswerData" => $answerList]);
    }

    public function createAnswer(){
        $data = [];
        $model = new AnswerListModel();  
        $answerList = $model->where('created_id', session()->get('id'))->find();   

        if ($this->request->getMethod() == 'post') {
                $rules = [
                    'answer' => 'required|min_length[2]|max_length[100]',
                    'ainfo' => 'required|min_length[2]|max_length[100]',
                ];
                $errors = [
                    'answer' => [
                        'required' => 'You must choose a answer.',
                    ]
                ];
         
            if (!$this->validate($rules, $errors)) {

                return view('admin/createanswer', [
                    "validation" => $this->validator,
                ]);
            } else {
                $model = new TenantModel();
                $tenant = $model->where('tenant_name', session()->get('tenant_name'))->first();
                $userId = session()->get('id');
                $answerId = $this->insertAnswer($this->request->getPost(),$userId);
                if($tenant['tenant_id'] > 1) {
                    $this->tenantInsertAnswer($this->request->getPost(),$tenant,$answerId, $userId);
                }
                session()->setFlashdata('response',"Create new Answer successfully");
                return redirect()->to(base_url('answerList'));
            }
        }
        return view('admin/createanswer' ,  ["answercollection" => $answerList]);

    }

    public function insertAnswer($postData, $userId) 
    {
        $model = new AnswerListModel();  
        $data = [
            "answer_name" => $this->escapeString($postData["answer"]),
            "description" => $this->escapeString($postData["ainfo"]),
            "created_id" => $userId
        ];
        $result = $model->insertBatch([$data]);
        $db = db_connect();        
        $questionId = $db->insertID();
        return $questionId;
    }

    public function updateAnswer($postData, $answerId) 
    {
        $model = new AnswerListModel();  
        $data = [
            "answer_name" => $this->escapeString($postData["answer"]),
            "description" => $this->escapeString($postData["ainfo"])
        ];
        $model->update($answerId,$data);
    }
    public function tenantInsertAnswer($postData, $tenantdata, $answerId, $userId){

        $dbname = "nps_".$tenantdata['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $data = [
            "answer_id" => $answerId,
            "answer_name" => $this->escapeString($postData["answer"]),
            "description" => $this->escapeString($postData["ainfo"]),
            "created_id" => $userId
        ];
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_insert_user ="INSERT INTO ".$dbname.".nps_answers_details ( ". implode(',' , $key) .") VALUES('". implode("','" , $values) ."')";
        $db->query($new_db_insert_user);
    }
    public function tenantUpdateAnswer($postData, $tenantdata, $qid){

        $dbname = "nps_".$tenantdata['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $cols = array();
        $data = [
            "answer_id" => $qid,
            "answer_name" => $this->escapeString($postData["answer"]),
            "description" => $this->escapeString($postData["ainfo"])
        ];
        foreach($data as $key=>$val) {
            $cols[] = "$key = '$val'";
        }

        $new_db_update_user ="UPDATE  ".$dbname.".`nps_answers_details` SET " . implode(', ', $cols) . " WHERE `nps_answers_details`.`answer_id` = ".$qid;
        $db->query($new_db_update_user);
    }
    public function deleteAnswer($q_id){
        $model = new AnswerListModel();    
        $getQuestData = $model->where('answer_id', $q_id)->delete();  
        $modeldel = new TenantModel();
        $tenant = $modeldel->where('tenant_name', session()->get('tenant_name'))->first();

        if($tenant['tenant_id'] > 1) {
            $this->tenantDeleteAnswer($tenant,$q_id);
        }
        session()->setFlashdata('response',"Answer deleted Successfully");
        return redirect()->to(base_url('answerList'));
    }
    public function tenantDeleteAnswer($tenantdata, $q_id) {
        $dbname = "nps_".$tenantdata['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $delete_query  = "DELETE FROM ".$dbname.".`nps_answers_details` WHERE `nps_answers_details`.`answer_id` =". $q_id;
        $db->query($delete_query);
    }
    public function editAnswer($ans_id) {
        $model = new AnswerListModel();    
        $getQuestData = $model->where('answer_id', $ans_id)->first();  

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'answer' => 'required|min_length[2]|max_length[100]',
                'ainfo' => 'required|min_length[2]|max_length[100]',
            ];
            $errors = [
                'answer' => [
                    'required' => 'You must choose a answer.',
                ]
            ];
     
        if (!$this->validate($rules, $errors)) {

            return view('admin/editanswer', [
                "validation" => $this->validator,
            ]);
        } else {
            $model = new TenantModel();
            $tenant = $model->where('tenant_name', session()->get('tenant_name'))->first();
            $userId = session()->get('id');
            $answerId = $this->updateAnswer($this->request->getPost(),$ans_id);
            if($tenant['tenant_id'] > 1) {
                $this->tenantUpdateAnswer($this->request->getPost(),$tenant,$ans_id);
            }
            session()->setFlashdata('response',"Update Answer Successfully");
            return redirect()->to(base_url('answerList'));
        }
    }
        return view('admin/editanswer',  ["getQuestData" => $getQuestData ]);
    }
    public function escapeString($val) {
        $db = db_connect(); 
        $connectionId = $db->connID;
        $val = mysqli_real_escape_string($connectionId, $val);
        return $val;
    }
}
