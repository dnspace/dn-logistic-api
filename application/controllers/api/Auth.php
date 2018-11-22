<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * Class : Auth (AuthController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class Auth extends REST_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Auth_model','MLog');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 5000; // 5000 requests per hour per user/key
        $this->methods['reset_pass_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['create_pass_post']['limit'] = 100; // 100 requests per hour per user/key
    }

    /**
     * Index Page for this controller.
     */
    public function index_get()
    {
        $ranstring = $this->common->randomString();
        $this->response([
            'status' => FALSE,
            'message' => $ranstring
        ], REST_Controller::HTTP_OK);
    }
    
    /**
     * This function used to logged in user
     */
    public function auth_post()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        
        $result = $this->MLog->auth_me($username, $password);
		
        if(count($result) > 0)
        {
            foreach ($result as $res)
            {
                $this->response([
                    'status' => TRUE,
                    'accessId'=>$res->user_id,
                    'accessUR'=>$res->user_key,
                    'accessName'=>$res->user_fullname,
                    'isAdmin'=>$res->is_admin,
                    'accessRepo'=>$res->fsl_code,
                    'accessCoverage'=>$res->coverage_fsl,
                    'role'=>$res->group_enc,
                    'roleText'=>$res->group_display,
                    'message' => 'User sign in'
                ], REST_Controller::HTTP_OK);
            }
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'status_r' => $result,
                'message' => 'Invalid Account!'
			], REST_Controller::HTTP_OK);
        }
    }
    
	/**
     * This function is used to check user data exist
     */
    function check_email_exist()
    {
		$status = FALSE;
		$email = $this->input->post('femail', TRUE);
		
		if($this->MLog->check_email_exist($email))
		{
			$status = TRUE;
		}
		return $status;
	}
	
    /**
     * This function used to generate reset password request link
     */
    function reset_pass_post()
    {
        $email = $this->input->post('email', TRUE);
        
        if($this->MLog->check_email_exist($email))
        {
            $encoded_email = urlencode($email);

            $data['email'] = $email;
            $data['activation_id'] = generateRandomString(15);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['agent'] = getBrowserAgent();
            $data['client_ip'] = $this->input->ip_address();
            
            $save = $this->MLog->reset_password_user($data);                
            
            if($save)
            {
                $data1['reset_link'] = $this->config->item('frontend') . "reset_pass_confirm/" . $data['activation_id'] . "/" . $encoded_email;
                $userInfo = $this->MLog->get_info_by_email($email);

                if(!empty($userInfo)){
                    $data1["name"] = $userInfo[0]->user_fullname;
                    $data1["email"] = $userInfo[0]->user_email;
                    $data1["message"] = "Reset Your Password";
                }

                $sendStatus = resetPasswordEmail($data1);

                if($sendStatus){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Reset password link sent successfully, please check your email.'
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Email has been failed, try again.'
                    ], REST_Controller::HTTP_OK);
                }
            }
            else
            {
                $this->response([
                    'status' => FALSE,
                    'message' => 'It seems an error while sending your details, try again.'
                ], REST_Controller::HTTP_OK);
            }
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Your email is not registered with us.'
            ], REST_Controller::HTTP_OK);
        }
    }

    // This function used to reset the password 
    function reset_pass_confirm_get()
    {
        // Get email and activation code from URL values at index 3-4
        $activation_id = $this->get('activation_id');
        $email = $this->get('email');

        $email = urldecode($email);
        
        // Check activation id in database
        $is_correct = $this->MLog->check_activation_details($email, $activation_id);
        
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;
        
        if ($is_correct == 1)
        {
            $this->response([
                'status' => TRUE,
                'email' => $email,
                'activation_code' => $activation_id,
                'message' => 'This email is registered with us.'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => TRUE,
                'message' => 'This email is not registered with us.'
            ], REST_Controller::HTTP_OK);
        }
    }
    
    // This function used to create new password
    function create_pass_post()
    {
        $status = '';
        $message = '';
        $femail = $this->input->post("femail", TRUE);
        $activation_id = $this->input->post("activation_code");

        $password = $this->input->post('password', TRUE);
        $cpassword = $this->input->post('cpassword', TRUE);
        
        // Check activation id in database
        $is_correct = $this->MLog->check_activation_details($email, $activation_id);
        
        if($is_correct == 1)
        {                
            $this->MLog->create_password($email, $password);
            $this->response([
                'status' => TRUE,
                'message' => 'Password changed successfully'
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->MLog->create_password($email, $password);
            $this->response([
                'status' => TRUE,
                'message' => 'Password changed failed'
            ], REST_Controller::HTTP_OK);
        }
    }
}

?>