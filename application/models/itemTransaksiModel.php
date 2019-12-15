<?php

// require 'CrudFunction.php';
class ItemTransaksiModel extends CI_Model {

    var $table = "item_transaksi";
    var $primaryKey = "id_item_transaksi";

    public function getAll() {
        return $this->db->get($this->table)->result();
    }

    public function getByPrimaryKey($primaryKey) {
        $this->db->where($this->primaryKey,$primaryKey);
        return $this->db->get($this->table)->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table,$data);
    }

    public function update($data, $primaryKey) {
        $this->db->where($this->primaryKey,$primaryKey);
        return $this->db->update($this->table,$data);
    }
    public function delete($primaryKey) {
        $this->db->where($this->primaryKey, $primaryKey);
        return $this->db->delete($this->table);
    }

    public function get_where($where){
        return $this->db->where($where)->get($this->table);
    }

    public function insertBatch($data){
        return $this->db->insert_batch($this->table, $data);
    }

    public function get_join_lengkap($idTransaksi){
        $this->db->select("it.*, p.nama_property, c.nama_customer")
        ->from("item_transaksi as it")
        ->join("property as p", "it.property_id = p.id_property")
        ->join("customer as c", "it.customer_id  = c.id_customer")
        ->where('it.transaksipesan_id', $idTransaksi);
        return $this->db->get()->result();
    }
}