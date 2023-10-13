<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\TenantController;
use App\Models\UserModel;
use App\Models\TenantModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class UserController extends BaseController
{
    
    public function login()
    {
        $data = [];

        if ($this->request->getMethod() == 'post') {

            $rules = [
                'tenantname' => 'required|min_length[2]|max_length[50]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email',
                'password' => 'required|min_length[4]|max_length[255]|validateUser[tenantname,email,password]',
            ];

            $errors = [
                'password' => [
                    'validateUser' => "Email/Tenant/Password didn't match",
                ],
            ];

            if (!$this->validate($rules, $errors)) {

                return view('login', [
                    "validation" => $this->validator,
                ]);

            } else {
                $model = new UserModel();

                $user = $model->where('email', $this->request->getVar('email'))->first();
                $model = new TenantModel();
                $tenant = $model->where('tenant_name', $this->request->getVar('tenantname'))->first();

                // Stroing session values
                $this->setUserSession($user, $tenant);
                // Redirecting to dashboard after login
                if($user['role'] == "admin"){
                    return redirect()->to(base_url('admin'));
                }elseif($user['role'] == "user"){
                    return redirect()->to(base_url('user'));
                }
                
                // $data = [
                //     'otp_email' => $user['email'],
                //     'otp_firstname' => $user['firstname'],
                //     'otp_lastname' => $user['lastname'],
                //     'otp_postemail' => $this->request->getPost('email'),
                //     'otp_id' => $user['id'],
                // ];
                // session()->set($data);
                // $this->generateOtp($this->request->getPost(), $user);
                // return redirect()->to(base_url('verificationpage'));
            }
        }
        return view('login');
    }
    public function generateOtp(){
        $data = [];

        if ($this->request->getMethod() == 'post') {
            $rules = [                
                'vcode' => 'required|numeric|exact_length[6]|ValidateOtp[vcode]',
            ];
            $errors = [];
            if (!$this->validate($rules, $errors)) {
                return view('otppage', [
                    "validation" => $this->validator,
                ]);
            } else {
                $userid = session()->get('otp_id');
                $model = new UserModel();
                $multiClause = array('id' => $userid, "otp_check" => $this->request->getPost("vcode")); 
                $user = $model->where($multiClause)->first();                
                if($user){
                    $model = new TenantModel();
                    $tenant = $model->where('tenant_id', $user['tenant_id'])->first();
                    // storing session values
                    $this->setUserSession($user, $tenant);
                    // Redirecting to dashboard after login
                    if($user['role'] == "admin"){
                        return redirect()->to(base_url('admin'));
                    }elseif($user['role'] == "user"){
                        return redirect()->to(base_url('user'));
                    }
                }
                return redirect()->to(base_url('login'));
            }
        } else{
            $otpdata = random_int(100000, 999999);
            $postemail = session()->get('otp_postemail');
            $user_email = session()->get('otp_email');
            $user =  [
                "firstname" => session()->get('otp_firstname'),
                "lastname" => session()->get('otp_lastname'),
                "id" => session()->get('otp_id'),
            ];
            if($postemail == $user_email){
                $this->createOTPforLogin($postemail, $otpdata, $user);
                $this->updateOTPdata($user, $otpdata);  
                return view('otppage');          
            }
        }
    }
      
    public function updateOTPdata($user, $otpdata){
        $model = new UserModel();
        $data = ['otp_check' => $otpdata];
        $model->update($user['id'],$data);
    }
    public function createOTPforLogin($postemail, $otpdata, $userdata){
        $whitelist = array( '127.0.0.1','::1');
        $mail = new PHPMailer(true); 
        $template = view("template/email-template-otp", ["otpdata" => $otpdata, "userdata" => $userdata]); 
        $subject = "NPS Customer || Generate OTP for Login";
		try {
		    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
                $mail->isSMTP();  
                $mail->Host         = 'smtp.gmail.com'; //smtp.google.com
                $mail->SMTPAuth     = true;     
                $mail->Username     = 'hctoolssmtp@gmail.com';  
                $mail->Password     = 'iyelinyqlqdsmhro';
                $mail->SMTPSecure   = 'tls';  
                $mail->Port         = 587;  
                $mail->Subject      = $subject;
                $mail->Body         = $template;
                
                $mail->setFrom('hctoolssmtp@gmail.com', 'CI-NPS');
                
                $mail->addAddress($postemail);  
                $mail->isHTML(true);      
                $response = $mail->send();
            }else {
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= 'From: <jeyprabu@hctools.in>' . "\r\n";
                $response = mail($postemail,$subject,$template, $headers);
            }
			if(!$response) {
			    return "Something went wrong. Please try again.". $mail->ErrorInfo;
			}
		    else {
			    return "Activation OTP generate and sent to email.";
		    }
		    
		} catch (Exception $e) {
		    return "Something went wrong. Please try again.". $mail->ErrorInfo;
		}
    }
  

    private function setUserSession($user, $tenant)
    {
        $data = [
            'id' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'username' => $user['username'],
            'logo_update' => $user['logo_update'],
            'status' => $user['status'],
            'phone_no' => $user['phone_no'],
            'email' => $user['email'],
            'isLoggedIn' => true,
            "role" => $user['role'],
            "tenant_id" => $tenant['tenant_id'],
            "tenant_name" => $tenant['tenant_name'],
            "db_name" => $tenant['database_name'],
            "db_host" => $tenant['host'],
            "db_username" => $tenant['username'],
            "db_password" => $tenant['password']
        ];

        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
    public function signup()
    {
        $data = [];

        if ($this->request->getMethod() == 'post') {
            $rules = [
                'firstname' => 'required|alpha',
                'lastname' => 'required|alpha',
                'username' => 'required|min_length[6]|max_length[50]',
                'tenantname' => 'required|min_length[2]|max_length[50]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email|validateEmail[email]',
                'phone_no' => 'required|numeric|exact_length[10]',
                'password' => 'required|min_length[4]|max_length[255]',
                'confirmpassword' => 'required|min_length[4]|max_length[255]|matches[password]',
            ];
            $errors = [
                'username' => [
                    'required' => 'You must choose a username.',
                ],
                'email' => [
                    'valid_email' => 'Please check the Email field. It does not appear to be valid.',
                    'validateEmail' => 'A Email Address is already available',
                ],
                
            ];
            if (!$this->validate($rules, $errors)) {
                return view('signup', [
                    "validation" => $this->validator,
                ]);
            } else {
                $model = new TenantModel();
                $tenant = $model->where('tenant_name', $this->request->getVar('tenantname'))->first();
                if(!$tenant) {
                    $TenantController = new TenantController();
                    $tenant = $TenantController->createTenantFront($this->request->getPost());
                }
                $userId = $this->insertUser($this->request->getPost(),$tenant);
                if($tenant['tenant_id'] > 1) {
                    $this->tenantInsertUser($this->request->getPost(),$tenant,$userId);
                }
                $emailstatus = $this->createTemplateForMailReg($this->request->getPost(), $userId );
                session()->setFlashdata('response',$emailstatus);
                return redirect()->to(base_url('login'));

            }
        }
        return view('signup');
    }
    public function insertUser($postdata, $tenantdata) {
        $model = new UserModel();
        $data = [
            "firstname" => $postdata['firstname'],
            "lastname" => $postdata['lastname'],
            "username" => $postdata['username'],
            "tenant_id" => $tenantdata['tenant_id'],
            "email" =>  $postdata['email'],
            "phone_no" =>  $postdata['phone_no'],
            "role" => "admin",
            "password" => password_hash($postdata['password'], PASSWORD_DEFAULT),
            "status" => "0"
        ];
        $result = $model->insertBatch([$data]);
        $db = db_connect();        
        $userId = $db->insertID();
        return $userId;
    }
    public function tenantInsertUser($postdata, $tenantdata, $userId){

        $dbname = "nps_".$tenantdata['tenant_name'];

        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $data = [
            "id" => $userId,
            "firstname" => $postdata['firstname'],
            "lastname" => $postdata['lastname'],
            "username" => $postdata['username'],
            "tenant_id" => $tenantdata['tenant_id'],
            "email" =>  $postdata['email'],
            "phone_no" =>  $postdata['phone_no'],
            "role" => "admin",
            "password" => password_hash($postdata['password'], PASSWORD_DEFAULT),
            "status" => "0"
        ];
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_insert_user ="INSERT INTO ".$dbname.".nps_users ( ". implode(',' , $key) .") VALUES('". implode("','" , $values) ."')";
        $db->query($new_db_insert_user);
    }

    public function getprofile() {
        if(!session()->get('email')) {
            return redirect()->to(base_url('login'));
        } else {
            $model = new UserModel();
            $userdata = $model->where('email', session()->get('email'))->first();            
            return view('update_profile', ["userdata" => $userdata]);
        }
    }
    public function updateprofile(){
        $data = [];
        if ($this->request->getMethod() == 'post') {
            $this->updateuser($this->request->getPost());
            session()->setFlashdata('response',"Data updated Successfully");
            return redirect()->to(base_url('userprofile'));
        }
    } 
    public function updateuser($postdata) {
        $model = new UserModel();
        $model->upsertBatch([
            [
				"firstname" => $postdata['firstname'],
				"lastname" => $postdata['lastname'],
				"email" =>  $postdata['email'],
				"phone_no" =>  $postdata['phone_no']
			]
        ]);
    } 
    public function changepassword()
    {
        return view('changepassword');
    } 
    public function updatepassword()
    {
        $data = [];
        if ($this->request->getMethod() == 'post') {
            // $rules = [
            //     'password' => 'required|min_length[4]|max_length[255]|passwordchecker[password]',
            //     'confirmpassword' => 'required|min_length[4]|max_length[255]|matches[password]',
            // ];
            // $errors = [
            //     'password' => [
            //         'passwordchecker' => "Current password is not same as old password",
            //     ],
            // ];
            // if (!$this->validate($rules, $errors)) {
            //     return view('changepassword', [
            //         "validation" => $this->validator,
            //     ]);
            // } else {
                $data = [
                    "password" => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                ];
         
                $updateId = session()->get('id');
                $tenantId = session()->get('tenant_id');
                $model = new UserModel();
                $model->update($updateId,$data);
                if($tenantId > 1){
                    $this->tenantUserPasswordUpdate($data,$tenantId,$updateId);
                }
                session()->setFlashdata('response',"Password Updated Successfully");
                return redirect()->to(base_url('changepassword'));
            // }
            return view('changepassword');
        }
    }

    public function tenantUserPasswordUpdate($data, $tenantId, $updateId) {
        if(empty(session()->get('tenant_name'))) { 
            $model = new TenantModel();
            $tenant = $model->where('tenant_id',$tenantId)->first();
            $ses_ten_id = $tenant['tenant_name'];
        }else{
            $ses_ten_id = session()->get('tenant_name');
        }
        
        $dbname = "nps_".$ses_ten_id;
        //new DB updation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_update_user ="UPDATE  ".$dbname.".`nps_users` SET `password` = '".$data["password"]."' WHERE `nps_users`.`id` = ".$updateId;
        $db->query($new_db_update_user);
    }
    public function validatepage($id){
        return view('validatepage', ["userId" => $id]);
    }

    public function activateOption($id){
        $model = new UserModel();    
        $usersvalidate = $model->where('id', $id)->first();
        $updateId = $usersvalidate['id'];
        $data = [ "status" => 1 ];
        $statusupdate = $model->update($updateId,$data);
        $tenantId = $usersvalidate["tenant_id"];
        if($tenantId > 1){
            $this->tenantUservalidate($data,$tenantId,$updateId);
        }
        session()->setFlashdata('response',"Your account is activated.");
        return redirect()->to(base_url('login'));   
    }

    public function forget()
    {
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'email' => 'required|min_length[6]|max_length[50]|valid_email'
            ];

            $errors = [
                'email' => [
                    'valid_email' => 'Please check the Email field. It does not appear to be valid.'
                ],
            ];
            if (!$this->validate($rules, $errors)) {
                return view('forgetpassword', [
                    "validation" => $this->validator,
                ]);
            } else {
                $model = new UserModel();
                $userData = $model->where('email', $this->request->getPost("email"))->first();
                if(!$userData) {
                    return view('forgetpassword', [
                        "valid" => 'Given Email is not available in Database.',
                    ]);
                }
                $updateId = $userData["id"];
                $newpassword = $this->randomPassword();
                $tenantId = $userData["tenant_id"];
                // $data = [
                //     "password" => password_hash($newpassword, PASSWORD_DEFAULT),
                // ];  
                // $model = new UserModel();
                // $model->update($updateId,$data);
                // if($tenantId > 1){
                //     $this->tenantUserForget($data,$tenantId,$updateId);
                // }
                $emailstatus = $this->createTemplateForMail($this->request->getPost(), $newpassword, $userData );
                session()->setFlashdata('response',$emailstatus);
                return redirect()->to(base_url('forget'));
            }
        }
        return view("forgetpassword");
    }
    public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%&*";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
    public function createTemplateForMail($postdata, $newpassword, $userData){
        $whitelist = array( '127.0.0.1','::1');
        $mail = new PHPMailer(true); 
        $template = view("template/email-template", ["password" => $newpassword, "userdata" => $userData, "postData" => $postdata]); 
        $subject = "NPS Customer || Forget Password";
		try {
		    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
                $mail->isSMTP();  
                $mail->Host         = 'smtp.gmail.com'; //smtp.google.com
                $mail->SMTPAuth     = true;     
                $mail->Username     = 'hctoolssmtp@gmail.com';  
                $mail->Password     = 'iyelinyqlqdsmhro';
                $mail->SMTPSecure   = 'tls';  
                $mail->Port         = 587;  
                $mail->Subject      = $subject;
                $mail->Body         = $template;
                
                $mail->setFrom('hctoolssmtp@gmail.com', 'CI-NPS');
                
                $mail->addAddress($postdata["email"]);  
                $mail->isHTML(true);      
                $response = $mail->send();
            }else {
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= 'From: <jeyprabu@hctools.in>' . "\r\n";
                $response = mail($postdata["email"],$subject,$template, $headers);
            }
			if(!$response) {
			    return "Something went wrong. Please try again.". $mail->ErrorInfo;
			}
		    else {
			    return "Activate link has been send to your email";
		    }
		    
		} catch (Exception $e) {
		    return "Something went wrong. Please try again.". $mail->ErrorInfo;
		}
    }
    public function createTemplateForMailReg($postdata, $userId){
        $whitelist = array( '127.0.0.1','::1');
        $mail = new PHPMailer(true); 
        $template = view("template/email-template-reg", ["postdata" => $postdata, "userId" => $userId]); 
        $subject = "NPS Customer || Create Account";

		try {
            if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){

                $mail->isSMTP();  
                $mail->Host         = 'smtp.gmail.com'; //smtp.google.com
                $mail->SMTPAuth     = true;     
                $mail->Username     = 'hctoolssmtp@gmail.com';  
                $mail->Password     = 'iyelinyqlqdsmhro';
                $mail->SMTPSecure   = 'tls';  
                $mail->Port         = 587;  
                $mail->Subject      = $subject;
                $mail->Body         = $template;
                
                $mail->setFrom('hctoolssmtp@gmail.com', 'CI-NPS');
                
                $mail->addAddress($postdata["email"]);  
                $mail->isHTML(true);   
                $response = $mail->send();  
            } else {
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= 'From: <jeyprabu@hctools.in>' . "\r\n";
                $response = mail($postdata["email"],$subject,$template, $headers);
            }
			if(!$response) {
			    return "Something went wrong. Please try again.". $mail->ErrorInfo;
			}
		    else {
			    return "A New Account has created.";
		    }
		    
		} catch (Exception $e) {
		    return "Something went wrong. Please try again.". $mail->ErrorInfo;
		}
    }
    public function tenantUserForget($data, $tenantId, $updateId) {
        $model = new TenantModel();
        $tenant = $model->where('tenant_id', $tenantId)->first();
        $dbname = "nps_".$tenant['tenant_name'];
        //new DB updation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_update_user ="UPDATE  ".$dbname.".`nps_users` SET `password` = '".$data["password"]."' WHERE `nps_users`.`id` = ".$updateId;
        $db->query($new_db_update_user);
    }
    public function tenantUservalidate($data, $tenantId, $updateId) {
        $model = new TenantModel();
        $tenant = $model->where('tenant_id', $tenantId)->first();
        $dbname = "nps_".$tenant['tenant_name'];
        //new DB updation for Tenant details
        $db = db_connect();
        $db->query('USE '.$dbname);
        $key = array_keys($data); 
        $values = array_values($data); 
        $new_db_update_user ="UPDATE  ".$dbname.".`nps_users` SET `status` = 1, updated_at =now() WHERE `nps_users`.`id` = ".$updateId;
        $db->query($new_db_update_user);
    }
    public function resetpwd(){
        $userId = $this->request->getGet('id');
        $model = new UserModel();
        $userData = $model->where('id', $userId)->first();

        if ($this->request->getMethod() == 'post') {
             $rules = [
                'password' => 'required|min_length[4]|max_length[255]',
                'confirmpassword' => 'required|min_length[4]|max_length[255]|matches[password]',
            ];
            $errors = [
                'password' => [
                    'passwordchecker' => "Current password is not same as old password",
                ],
            ];
            if (!$this->validate($rules, $errors)) {
                return view('resetpassword', [
                    "validation" => $this->validator
                ]);
            } else {
                $data = [
                    "password" => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                ];
         
                $updateId = $this->request->getPost('userId');
                $tenantId = $this->request->getPost('tenant_id');
                $model = new UserModel();
                $model->update($updateId,$data);
                if($tenantId > 1){
                    $this->tenantUserPasswordUpdate($data,$tenantId,$updateId);
                }
                session()->setFlashdata('response',"Password Updated Successfully");
                return redirect()->to(base_url('login'));
            }
        }
        return view('resetpassword', ["userdata" => $userData]);
    }
}
