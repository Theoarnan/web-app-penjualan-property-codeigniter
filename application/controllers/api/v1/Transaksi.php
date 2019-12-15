<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Transaksi extends Restserver\Libraries\REST_Controller{

    function __construct() {
        parent :: __construct();
        $this->load->model(array("TransaksiModel"));
        $this->load->model(array("ItemTransaksiModel"));
        // if (checkToken() == false) {
        //     $this->response(["pesan" => "Silahkan Login"], 401);
        //     exit();
        // }
        header('Content-Type: application/json');
    }

    public function index_get($id = null){
        if($id == null){
            $perPage = ($this->get("per_page") == null ) ? "100" : $this->get("per_page");
            $page = ($this->get("page") == null ) ? "1" : $this->get("page");
            $search = ($this->get("search") == null ) ? "" : $this->get("search");
            $start = ((int)$page -1 ) * (int)$perPage;
            $total_row = $this->TransaksiModel->totalRow($search);
            $total_page = ceil($total_row / $perPage);
            $transaksis = $this->TransaksiModel->getLimitData($perPage, $start,$search);
           
            $data = array(
                "meta" => array(
                    "page" => $page,
                    "per_page" => $perPage,
                    "search" =>$search,
                    "total_data" => $total_row,
                    "total_page" => $total_page
                ),
                "data" => $transaksis,
            );
            $this->response($data,200);
        }else{
            $transaksi = $this->TransaksiModel->getByPrimaryKey($id);
            if($transaksi == null){
                $this->response(array("message"=>"Data tidak ditemukan"),400);
            }else{
                $itemTransaksi = $this->ItemTransaksiModel->get_join_lengkap($id);
                $transaksi->item_transaksi = $itemTransaksi;
                $this->response($transaksi,200);
            }
        }          
    }

    public function index_post(){
        //Menambah data transaksi
        $dataRequest = json_decode(file_get_contents("php://input"));
        $itemTransaksi = $dataRequest->item_transaksi;
        //Create transaksi dahulu
        $nomor = getLastNomor("transaksi_pesan")->nomor + 1;
        $nomorTransaksi = autoCreate(array("TRX"), "/", $nomor);
        $dataTransaksi = array(
            "nomor" => $nomor,
            "no_transaksipesan" => $nomorTransaksi,
            "tgl_pesan_transaksipesan" => date("Y-m-d")
            
        );
        $idTransaksi = $this->TransaksiModel->insertGetId($dataTransaksi);

        $dataSimpan = array();
        foreach ($itemTransaksi as $item){
            $dataSimpan[] = array(
                //Nama field -> nama objek -> Field object
                "property_id" => $item->property_id,
                "customer_id" => $item->customer_id,
                "total_item_transaksi" => $item->total,
                "harga_item_transaksi" => $item->harga,
                "transaksipesan_id" => $idTransaksi
            );
        }
        $result = $this->ItemTransaksiModel->insertBatch($dataSimpan);
        
        if($result){
            $this->response(array("message" => "Data Berhasil diSimpan"),200);
        } else {
            $this->response(array("message" => "Request Tidak Valid"),400);
        }
    }
}