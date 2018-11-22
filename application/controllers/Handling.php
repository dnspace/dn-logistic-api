<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Dashboard (DashboardController)
 * Dashboard Class to control all user related operations.
 * @author : Khazefa
 * @version : 1.0
 * @since : Mei 2017
 */
class Handling extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model','MUser');
    }
    
    /**
     * This function used to load 404 page
     */
    public function index()
    {
        $this->global['pageTitle'] = APP_NAME.' : 404 - Page Not Found';   
        $this->loadViews("404", $this->global, NULL, NULL);
    }
    
    /**
     * This function used to logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect ( 'login' );
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function check_email_exists()
    {
        $userId = $this->input->post("userId", TRUE);
        $email = $this->input->post("email", TRUE);

        if(empty($userId)){
            $result = $this->MUser->check_email_exists($email);
        } else {
            $result = $this->MUser->check_email_exists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
}

?>