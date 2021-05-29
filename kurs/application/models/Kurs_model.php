<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kurs_model extends CI_Model{
    public function insert($nama_file, $direktori, $mata_uang, $nilai){
        $this->db->trans_start();
        $this->db->query("INSERT INTO `tb_arsip_kurs`(`id`, `direktori`, `nama_file`) VALUES (NULL,?,?)",
        array($direktori, $nama_file));
        $id = $this->db->query(" SELECT LAST_INSERT_ID()");
        $this->db->query("INSERT INTO `tb_kurs`(`id`, `arsip_kurs_id`, `mata_uang`, `nilai`) VALUES (NULL,?,?,?)",
        array($id->row_array(0), $mata_uang, $nilai));
        $this->db->trans_complete();     
        if ($this->db->trans_status() === true){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $this->db->trans_start();
        $this->db->query("DELETE FROM `tb_kurs` WHERE `arsip_kurs_id`=BINARY ?",
        array($id));
        $this->db->query("DELETE FROM `tb_arsip_kurs` WHERE `id`=BINARY ?",
        array($id));
        $this->db->trans_complete();     
        if ($this->db->trans_status() === true){
            return true;
        }else{
            return false;
        }
    }

    public function exists_mata_uang($mata_uang){
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM `tb_kurs` WHERE `mata_uang`=BINARY ?",
        array($mata_uang));
        $this->db->trans_complete();     
        if($query->num_rows() == 1){
            $row = $query->row_array(0);
            return true;
        }else{
            return false;
        }
    }

    public function exists_id($id){
        $this->db->trans_start();
        $query = $this->db->query("SELECT * FROM `tb_arsip_kurs` INNER JOIN `tb_kurs` ON `tb_arsip_kurs`.`id`=`tb_kurs`.`arsip_kurs_id` WHERE `tb_arsip_kurs`.`id`=BINARY ?",
        array($id));
        $this->db->trans_complete();     
        if($query->num_rows() == 1){
            $row = $query->row_array(0);
            $out["exists"] = true;
            $out["mata_uang"] = $row["mata_uang"];
            $out["nilai"] = $row["nilai"];
            $out["direktori"] = $row["direktori"];
            $out["nama_file"] = $row["nama_file"];
            return $out;
        }else{
            $out["exists"] = false;
            $out["mata_uang"] = "";
            $out["nilai"] = "";
            $out["direktori"] = "";
            $out["nama_file"] = "";
            return $out;
        }
    }

    public function list_kurs(){
        return $this->db->query("SELECT * FROM `tb_kurs` ORDER BY `mata_uang` ASC");
    }

    public function list_arsip_kurs(){
        return $this->db->query("SELECT * FROM `tb_arsip_kurs` ORDER BY `nama_file` ASC");
    }
}