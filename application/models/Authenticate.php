<?php
Class Authenticate extends CI_Model {

	public function validateToken($token)
	{
		$privateKey = config_item('encryption_key');

		try {
			$decodeToken = JWT::decode($token, $privateKey);

			return $decodeToken;

		} catch (Exception $e) {

			return false;
			
		}
	}

	public function email($email)
	{
		return $this->db->where('user_email', $email)->get('licence_request')->num_rows();
	}

	public function phone($phone)
	{
		return $this->db->where('user_mobile', $phone)->get('licence_request')->num_rows();
	}

	public function Login()
	{
		$username = $this->input->post('email');

                if(!preg_match("/@./", $username)){

                  $this->db->where('user_name', $username);

                }else{

                  $this->db->where('email_id', $username);

                } 

            $query = $this->db->get('login_details');

                if($query->num_rows() > 0){


                    if(password_verify($this->input->post('password'), $query->row()->password)){

                        return $query->row();
                    }

                    return FALSE;

                }else{

                    return 0;

                }
	}

	public function User($username, $password)
	{
	        if(!preg_match("/@./", $username)){

	          $this->db->where('user_name', $username);

	        }else{

	          $this->db->where('email_id', $username);

	        } 

	    $query = $this->db->get('login_details');

	        if($query->num_rows() > 0){

	            if($query->row()->password == $password){

	                return $query->row();
	            }

	            return FALSE;

	        }else{

	            return 0;

	        }
	}

	
}
