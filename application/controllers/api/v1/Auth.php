<?php

class Auth extends Restserver\Libraries\REST_Controller {

    //Panggil Class AuthModel
    function __construct(){
        parent :: __construct();
        $this->load->model("AuthModel");
    }

    function index_post(){
        $username = $this->input->server("PHP_AUTH_USER");
        $password = $this->input->server("PHP_AUTH_PW");

        $admin = $this->AuthModel->checkAdmin($username,$password);
        if($admin != null){
            //Update Token dan Tanggal Expired dari Token
            $token = md5($admin->username_admin.date("d M Y H:i:s"));
            $tokenExpired = date('Y-m-d', strtotime(date("Y-m-d"). "+7 days"));

            //Menampung data token
            $dataToken = array(
                "token_admin"=>$token,
                "token_expired_admin"=>$tokenExpired
            );
            $this->AuthModel->updateExpiredAndTokenAdmin($admin->id_admin, $dataToken);
            
            $data = array(
                "username"=>$admin->username_admin,
                "token"=>$token,
                "token_expired"=>$tokenExpired
            );
            $this->response($data, 200);
            
        } else {
            $pesan = array(
                "message" => "Login Gagal, Username atau Password tidak ditemukan gan!"
            );
            $this->response($pesan, 401);
        }
    }
}