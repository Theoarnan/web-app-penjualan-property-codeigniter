<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class TypeProperty extends Restserver\Libraries\REST_Controller{

    //Panggil Customer
    function __construct(){
        parent:: __construct();
        $this->load->model("typeModel");
    //cek Token
        header('Content-Type: application/json');
        // if(checkToken()==FALSE){
        //     $this->response(["pesan"=>"Silahkan Login dulu Gan!"],401);
        //     exit();
        // }
    }

    //Get AllProperty berdasarkan pencarian page dan search
    public function index_get($id = null){
        if ($id == null){
        $perpage = ($this->get("per_page")== NULL)? "10" : $this->get("per_page");
        $page = intval($this->get("page"));
        $search = ($this->get("search")== NULL)? "" : $this->get("search");
        $start = ((int)$page -1) *(int) $perpage;

        $total_row = $this->typeModel->totalRow($search);
        $total_page = ceil($total_row / $perpage);
        $typemodels = $this->typeModel->getLimitData($perpage,$start,$search);
        
        $data = array(
            "meta" => array(
                "page" => $page,
                "per_page" => $perpage,
                "search" => $search,
                "total_row" => $total_row,
                "total_page" => $total_page
            ),
            "data_type" => $typemodels,
        );
        $this->response($data, 200); 
    }else{
        $typemodel = $this->typeModel->getByPrimaryKey($id);
        if ($typemodel == null) {
            $this->response(array("message" => "Data tidak ditemukan"), 400);
        } else {
            $this->response($typemodel, 200);
        }
    }
}
    
    //POST Customer
    public function index_post(){
        $dataRequest = json_decode(file_get_contents("php://input"));
        $datatype = $dataRequest->data_type;
        //Tambah Customer

        $dataSimpan = array();
        foreach ($datatype as $type){
            $dataSimpan[] = array(
                //Nama field -> nama objek -> Field object
                "tipe" => $type->tipe,
            );
        }
        $result = $this->typeModel->insertBatch($dataSimpan);
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

        $types = json_decode(file_get_contents("php://input"));
        // $idTipe = $this->put("id_tipe", true);
        // $typemodel = $this->typeModel->getByPrimaryKey($idTipe);

        //Update Customer
        $data = array(
            "tipe" => $types->tipe,
        );
        $result = $this->typeModel->update($data, $types->id_tipe);
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
    public function index_delete($idTipe){
        // $types = json_decode(file_get_contents("php://input"));
        // $idTipe = $this->delete("id_tipe", true);
        $typemodel = $this->typeModel->getByPrimaryKey($idTipe);

        $result = $this->typeModel->delete($idTipe);
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