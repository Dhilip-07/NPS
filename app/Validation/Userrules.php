<?php

namespace App\Validation;
use App\Models\UserModel;
use App\Models\TenantModel;
class Userrules
{
    public function validateUser(string $str, string $fields, array $data)
    {
        $model = new TenantModel();
        $tenant = $model->where('tenant_name', $data['tenantname'])->first();
        if($tenant){
            $model = new UserModel();
            $multiClause = array('email' => $data['email'],'tenant_id' => $tenant['tenant_id']);
            $user = $model->where($multiClause)->first();
            
            if (!$user) {
                return false;
            }
        } else {
            return false;
        }

        return password_verify($data['password'], $user['password']);
    }
    public function ValidateOtp(string $str, string $fields, array $data){
        $model = new UserModel();
        $userid = session()->get('otp_id');
        $multiClause = array('id' => $userid, "otp_check" => $data["vcode"]); 
        $user = $model->where($multiClause)
        ->first();
        if ($user) {
            return true;
        }
        return false;
    }
    public function validateTenant(string $str, string $fields, array $data)
    {
        $model = new TenantModel();
        $tenant = $model->where('tenant_name', $data['tenantname'])
        ->first();

        if (!$tenant) {
            return true;
        }
    return false;
    }
    public function validateEmail(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $user = $model->where('email' ,$data['email'])
        ->first();

        if (!$user) {
            return true;
        }
    return false;
    }
    public function passwordchecker(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $user = $model->where('id', session()->get('id'))
            ->first();

        if (!$user) {
            return false;
        }
        if(password_verify($data['password'], $user['password'])) {
            return false;
        }else {
            return true;
        }
    }
    
}
