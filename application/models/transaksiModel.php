<?php

require 'CrudFunction.php';
class TransaksiModel extends CI_Model implements CrudFunction
{

    //Definisikan nama table dan primary key nya.
    var $table = "transaksi_pesan";
    var $primaryKey = "id_transaksipesan";

    //Implementasi Crud Functionnya.
    public function getAll()
    {
        return $this->db->get($this->table)->result();
    }

    public function getByPrimaryKey($primaryKey)
    {
        $this->db->where($this->primaryKey, $primaryKey);
        return $this->db->get($this->table)->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($data, $primaryKey)
    {
        $this->db->where($this->primaryKey, $primaryKey);
        return $this->db->update($this->table, $data);
    }

    public function delete($primaryKey)
    {
        $this->db->where($this->primaryKey, $primaryKey);
        return $this->db->delete($this->table);
    }


    //Tambahan
    function insertGetId($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    //Mencari data menggunakan Like
    //Menghitung total barisnya berapa?
    public function totalRow($search = null)
    {
        $this->db->like("no_transaksipesan", $search);
        $this->db->or_like("tgl_pesan_transaksipesan", $search);
        $this->db->or_like("nomor", $search);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    //Mencari Isi atau datanya
    public function getLimitData($limit, $start = 0, $search = null)
    {
        $this->db->like("no_transaksipesan", $search);
        $this->db->or_like("tgl_pesan_transaksipesan", $search);
        $this->db->or_like("nomor", $search);
        $this->db->limit($limit, $search);
        return $this->db->get($this->table)->result();
    }
}
