<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Property extends Restserver\Libraries\REST_Controller
{

    //Panggil PropertyModel
    function __construct()
    {
        parent::__construct();
        $this->load->model("PropertyModel");
        //Cek
        header('Content-Type: application/json');
        if (checkToken() == FALSE) {
            $this->response(["pesan" => "Silahkan Login dulu Gan!"], 401);
            exit();
        }
    }

    //Get AllProperty berdasarkan pencarian page dan search
    public function index_get($id = null)
    {
        if ($id == null) {
            $perpage = ($this->get("per_page") == NULL) ? "500" : $this->get("per_page");
            $page = intval($this->get("page"));
            $search = ($this->get("search") == NULL) ? "" : $this->get("search");
            $start = ((int) $page - 1) * (int) $perpage;

            $total_row = $this->PropertyModel->totalRow($search);
            $total_page = ceil($total_row / $perpage);
            $propertys = $this->PropertyModel->getLimitData($perpage, $start, $search);
            // $idPropertyss = $this->PropertyModel->getByPrimaryKey($id, $search);
            $dataProperty = array();
            foreach ($propertys as $property) {
                $property->image_url = base_url() . "image/" . $property->gambar_property;
                $dataProperty[] = $property;
            }

            $data = array(
                "meta" => array(
                    "page" => $page,
                    "per_page" => $perpage,
                    "search" => $search,
                    "total_row" => $total_row,
                    "total_page" => $total_page,
                    // "id_property" => $idPropertyss
                ),
                "property_data" => $dataProperty,
            );
            $this->response($data, 200);
        } else {
            $property = $this->PropertyModel->getByPrimaryKey($id);
            if ($property == null) {
                $this->response(array("message" => "Data tidak ditemukan"), 400);
            } else {
                $property->image_url = base_url() . "image/" . $property->gambar_property;
                $this->response($property, 200);
            }
        }
    }

    //POST Property
    public function index_post()
    {

        //Proses menambah image
        $stringBase64 = $this->input->post("gambar_property", true);
        $fileName = md5(date("d-m-Y H:i:s") . rand(1, 100000));
        $fileName .= ".jpg";
        $decode = base64_decode($stringBase64);
        file_put_contents("image/$fileName", $decode);

        //Tambah Property
        $data = array(
            "nama_property" => $this->post("nama_property", true),
            "harga_property" => $this->post("harga_property", true),
            "stock_property" => $this->post("stock_property", true),
            "detail_property" => $this->post("detail_property", true),
            "lokasi_property" => $this->post("lokasi_property", true),
            "tipe_property" => $this->post("tipe_property", true),
            "gambar_property" => $fileName,
        );
        echo json_encode($data, \Restserver\Libraries\REST_Controller::HTTP_CREATED);
        $result = $this->PropertyModel->insert($data);
        if ($result) {
            $pesan = array(
                "message" => "Data Property Berhasil Disimpan"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_CREATED);
        } else {
            $pesan = array(
                "message" => "Data Property Gagal Disimpan"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //MengUpdate Data Property
    public function index_put()
    {
        $property = json_decode(file_get_contents("php://input"));
        // $idProperty = $this->put("id_property", true);
        // $property = $this->PropertyModel->getByPrimaryKey($idProperty);

        //Hapus gambar lama terlebih dahulu
        if (file_exists("image/$property->gambar_property")) {
            unlink("image/$property->gambar_property");
        }

        //Update Gambar Baru dan Data property Baru
        $stringBase64 = $property->gambar_property;
        $fileName = md5(date("d-m-Y H:i:s") . rand(1, 100000));
        $fileName .= ".jpg";
        $decode = base64_decode($stringBase64);
        file_put_contents("image/$fileName", $decode);

        //Update Property
        $data = array(
            "nama_property" => $property->nama_property,
            "harga_property" => $property->harga_property,
            "stock_property" => $property->stock_property,
            "detail_property" => $property->detail_property,
            "lokasi_property" => $property->lokasi_property,
            "tipe_property" => $property->tipe_property,
            "gambar_property" => $fileName,
        );
        $result = $this->PropertyModel->update($data, $property->id_property);
        if ($result) {
            $pesan = array(
                "message" => "Data Property Berhasil di Update"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_OK);
        } else {
            $pesan = array(
                "message" => "Data Property Gagal di Update"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Hapus Data Property
    public function index_delete($idProperty)
    {
        // $property = json_decode(file_get_contents("php://input"));
        // $idProperty = $this->delete("id_property", true);
        $property = $this->PropertyModel->getByPrimaryKey($idProperty);

        //Hapus Gambar
        // if (file_exists("image/$property->gambar_property")) {
        //     unlink("image/$property->gambar_property");
        // }

        $result = $this->PropertyModel->delete($idProperty);
        if ($result) {
            $pesan = array(
                "message" => "Data Property Berhasil di Hapus"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_OK);
        } else {
            $pesan = array(
                "message" => "Data Property Gagal di Hapus"
            );
            $this->response($pesan, Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
