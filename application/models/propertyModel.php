<?php

require 'CrudFunction.php';
class propertyModel extends CI_Model implements CrudFunction{
    
    //Definisikan nama table dan primary key nya.
    var $table = "property";
    var $primaryKey = "id_property";

    //Implementasi Crud Functionnya.
    public function getAll(){
        return $this->db->get($this->table)->result();
    }

    public function getByPrimaryKey($primaryKey){
        $this->db->where($this->primaryKey, $primaryKey);
        return $this->db->get($this->table)->row();
    }

    public function insert($data){
        return $this->db->insert($this->table,$data);
    }

    public function update($data, $primaryKey){
        $this->db->where($this->primaryKey,$primaryKey);
        return $this->db->update($this->table,$data);
    }

    public function delete($primaryKey){
        $this->db->where($this->primaryKey,$primaryKey);
        return $this->db->delete($this->table);
    }

    //Mencari data menggunakan Like
    //Menghitung total barisnya berapa?
    public function totalRow($search = null){
        $this->db->like("id_property",$search);
        $this->db->or_like("nama_property",$search);
        $this->db->or_like("harga_property",$search);
        $this->db->or_like("stock_property",$search);
        $this->db->or_like("detail_property",$search);
        $this->db->or_like("lokasi_property",$search);
        $this->db->or_like("tipe_property",$search);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    //Mencari Isi atau datanya
    public function getLimitData($limit,$start=0, $search=null){
        //ganti
        $this->db->like("id_property",$search);
        $this->db->or_like("nama_property",$search);
        $this->db->or_like("harga_property",$search);
        $this->db->or_like("stock_property",$search);
        $this->db->or_like("detail_property",$search);
        $this->db->or_like("lokasi_property",$search);
        $this->db->or_like("tipe_property",$search);
        $this->db->limit($limit,$search);
        return $this->db->get($this->table)->result();
    }

    // public function get_join_lengkap($tipeProperty){
    //     $this->db->select("p.*, t.tipe")
    //     ->from("property as p")
    //     ->join("tipe_property as t", "p.tipe_property = t.id_tipe")
    //     ->where('p.tipe_property', $tipeProperty);
    //     return $this->db->get()->result();
    // }

}