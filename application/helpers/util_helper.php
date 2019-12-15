<?php

function checkToken(){
    $ci = &get_instance();

    $token = $ci->input->get_request_header("token");

    $query = "select token_admin,token_expired_admin from admin where token_admin = '$token'";
    $admin = $ci->db->query($query)->row();
    $hariIni = date("Y-m-d");

    //Cek Token sudah expired atau belum?
    if($admin != NULL){
        if($hariIni > $admin->token_expired_admin)
        //Token sudah Expired
        return false;
    }else{
        return false;
    }
    return true;
}

//Buat field tahun, bulan itu bukan tanggal_transaksi
function getLastNomor($table){
    $CI = &get_instance();
    $query = "select max(nomor) as nomor from $table where year(created_at) = year(now()) "; 
    $query .= "AND month(created_at) = month(now()) ";
    $nomor = $CI->db->query($query)->row();
    return $nomor;
}

function autoCreate($prefix, $delimeter, $nomor){
    $s = "";
    foreach ($prefix as $value){
        $s .= $value . $delimeter;
    }
    return $s . date("Y")
        . $delimeter
        . date("m")
        . $delimeter
        . str_pad($nomor, 4, "0", STR_PAD_LEFT);
}