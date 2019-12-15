<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends Restserver\Libraries\REST_Controller{

    //Panggil Customer
    function __construct(){
        parent:: __construct();
        $this->load->model("customerModel");
    //cek Token
        header('Content-Type: application/json');
        if(checkToken()==FALSE){
            $this->response(["pesan"=>"Silahkan Login dulu Gan!"],401);
            exit();
        }
    }

    //Get AllProperty berdasarkan pencarian page dan search
    public function index_get($id = null){
        if ($id == null){
        $perpage = ($this->get("per_page")== NULL)? "10" : $this->get("per_page");
        $page = intval($this->get("page"));
        $search = ($this->get("search")== NULL)? "" : $this->get("search");
        $start = ((int)$page -1) *(int) $perpage;

        $total_row = $this->customerModel->totalRow($search);
        $total_page = ceil($total_row / $perpage);
        $customers = $this->customerModel->getLimitData($perpage,$start,$search);
        
        $data = array(
            "meta" => array(
                "page" => $page,
                "per_page" => $perpage,
                "search" => $search,
                "total_row" => $total_row,
                "total_page" => $total_page
            ),
            "customer_data" => $customers,
        );
        $this->response($data, 200); 
    }else{
        $customer = $this->customerModel->getByPrimaryKey($id);
        if ($customer == null) {
            $this->response(array("message" => "Data tidak ditemukan"), 400);
        } else {
            $this->response($customer, 200);
        }
    }
}
    
    //POST Customer
    public function index_post(){
        
        //Tambah Customer
        $data = array(
            "nama_customer" => $this->post("nama_customer", true),
            "alamat_customer" => $this->post("alamat_customer", true),
            "jk_customer" => $this->post("jk_customer", true),
            "telp_customer" => $this->post("telp_customer", true),
        );
        echo json_encode($data, 200);
        $result = $this->customerModel->insert($data);
        if ($result) {
            $pesan = array (
                "message" => "Data Customer Berhasil Disimpan"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_CREATED);
        } else {
            $pesan = array (
                "message" => "Data Customer Gagal Disimpan"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //MengUpdate Data Customer
    public function index_put(){
        $idCustomer = $this->put("id_customer", true);
        $customer = $this->customerModel->getByPrimaryKey($idCustomer);

        //Update Customer
        $data = array(
            "nama_customer" => $this->put("nama_customer", true),
            "alamat_customer" => $this->put("alamat_customer", true),
            "jk_customer" => $this->put("jk_customer", true),
            "telp_customer" => $this->put("telp_customer", true),
        );
        $result = $this->customerModel->update($data, $idCustomer);
        if ($result) {
            $pesan = array(
                "message" => "Data Customer Berhasil di Update"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_OK);
        } else {
            $pesan = array(
                "message" => "Data Customer Gagal di Update"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Hapus Data Customer
    public function index_delete($idCustomer){
        // $idCustomer = $this->delete("id_customer", true);
        $customer = $this->customerModel->getByPrimaryKey($idCustomer);

        $result = $this->customerModel->delete($idCustomer);
        if ($result) {
            $pesan = array(
                "message" => "Data Customer Berhasil di Hapus"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_OK);
        } else {
            $pesan = array(
                "message" => "Data Customer Gagal di Hapus"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}