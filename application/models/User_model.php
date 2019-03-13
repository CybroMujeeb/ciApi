<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
 
 
/**
 
*
 
*/
 
class User_model extends CI_Model
 
{
 
    public function read(){
 
       $query = $this->db->query("select * from `tbl_user`");
 
       return $query->result_array();
 
    }


    public function getAllPatients($params = array()){

      if(array_key_exists("start",$params) && array_key_exists("limit",$params) && $params['limit'] > 0)
        {
              $this->db->limit($params['limit'], $params['start']);

            }elseif(!array_key_exists("start", $params) && array_key_exists("limit", $params) && $params['limit'] > 0){

              $this->db->limit($params['limit']);
        }
        
        return $this->db->where('patient_status', '1')->get('patient_registration')->result();

    }

    public function getPatient($id){

        return $this->db->where('patient_id', $id)->where('patient_status', '1')->get('patient_registration')->row();

    }
 
 
   public function insert($data){
 
       
 
       $this->user_name    = $data['name']; // please read the below note
 
       $this->user_password  = $data['pass'];
 
       $this->user_type = $data['type'];
 
 
 
       if($this->db->insert('tbl_user',$this))
 
       {    
 
           return 'Data is inserted successfully';
 
       }
 
         else
 
       {
 
           return "Error has occured";
 
       }
 
   }
 
 
 
   public function update($id,$data){
 
   
 
      $this->user_name    = $data['name']; // please read the below note
 
       $this->user_password  = $data['pass'];
 
       $this->user_type = $data['type'];
 
       $result = $this->db->update('tbl_user',$this,array('user_id' => $id));
 
       if($result)
 
       {
 
           return "Data is updated successfully";
 
       }
 
       else
 
       {
 
           return "Error has occurred";
 
       }
 
   }
 
 
 
   public function delete($id){
 
   
 
       $result = $this->db->query("delete from `tbl_user` where user_id = $id");
 
       if($result)
 
       {
 
           return "Data is deleted successfully";
 
       }
 
       else
 
       {
 
           return "Error has occurred";
 
       }
 
   }
 
 
 
}