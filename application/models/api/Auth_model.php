<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    protected $pTbl = "users";
    protected $pKey = "user_id";
    protected $uKey = "user_key";

    protected $sTbl = "user_group";
    protected $sKey = "group_id";

    /**
     * This function used to check the login credentials of the user
     * @param string $username : This is username of the user
     * @param string $password : This is encrypted password of the user
     */
    function auth_me($username, $password)
    {
        $this->db->select('u.user_id, u.user_key, u.user_pass, u.user_fullname, u.is_admin, u.fsl_code, u.coverage_fsl, g.group_enc, g.group_display');
        $this->db->from('users as u');
        $this->db->join('user_group as g','g.group_id = u.group_id');
        $this->db->where('u.user_key', $username);
        // $this->db->where('u.deleted_at', NULL);
        $query = $this->db->get();
        
        $user = $query->result();
        
        if(!empty($user)){
            if(verifyHashedPassword($password, $user[0]->user_pass)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function check_email_exist($email)
    {
        $this->db->select('user_id');
        $this->db->where('user_email', $email);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function reset_password_user($data)
    {
        $result = $this->db->insert('reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by username-id for forget password username
     * @param string $username : username id of customer
     * @return object $result : Information of customer
     */
    function get_info_by_username($username)
    {
        $this->db->select('user_id, user_key, user_fullname');
        $this->db->from('users');
        $this->db->where('user_key', $username);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function get_info_by_email($email)
    {
        $this->db->select('user_id, user_email, user_fullname');
        $this->db->from('users');
        $this->db->where('user_email', $email);
        $this->db->where('is_deleted', 0);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function check_activation_details($email, $activation_id)
    {
        $this->db->select('res_id');
        $this->db->from('reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows;
    }

    // This function used to create new password by reset link
    function create_password($email, $password)
    {
        $this->db->where('user_email', $email);
        $this->db->where('is_deleted', 0);
        $this->db->update('users', array('user_pass'=>getHashedPassword($password)));
        $this->db->delete('reset_password', array('email'=>$email));
    }
}

?>