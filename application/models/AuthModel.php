<?php

class AuthModel extends CI_Model {
    //put your code here
    function checkAdmin($username,$password){
        
        $this->db->select("*");
        $this->db->from("admin");
        $this->db->where("username_admin", $username);
        $admin = $this->db->get()->row();
        if($admin != null){
            if(password_verify($password, $admin->password_admin)){
                return $admin;
            }
        }
        return null;
    }
    function updateExpiredAndTokenAdmin($id,$data){
        $this->db->where("id_admin",$id);
        return $this->db->update("admin",$data);
    }
}