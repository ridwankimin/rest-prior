<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CheckPJModel extends CI_Model
{
    function get_data($idpj, $idppjk, $kdupt) {
        // Query untuk mengambil data berdasarkan parameter
        // $this->db->where('column1', $idpj);
        // $this->db->where('column2', $idppjk);
        // $query = $this->db->get('your_table');

        $idpj = str_replace('-','',str_replace('.','',$idpj));
        $kdupt = $kdupt.'00';

        if($idpj<>'' && $idppjk=="") {
            // $this->db->select("pj.id,pj.kode_perusahaan AS kd_perusahaan,pj.jenis_perusahaan,pj.kota,pj.provinsi_id");
            // $this->db->from("pengguna_jasa AS pj");
            // $this->db->join("master_upt AS mu", "mu.id=pj.upt_id");
            // $this->db->where("pj.status", "DISETUJUI");
            // $this->db->where("pj.jenis_perusahaan", "PEMILIK_BARANG");
            // $this->db->where("is_active", "1");
            // $this->db->where("REPLACE(REPLACE(nomor_identitas,'.',''),'-','')", $idpj);
            // $this->db->where("mu.kode", $kdupt);
            // return $this->db->get()->result_array();
            $sql =  "SELECT pj.id,pj.kode_perusahaan AS kd_perusahaan,pj.jenis_perusahaan,pj.kota,pj.provinsi_id 
                    FROM pengguna_jasa AS pj
                    JOIN master_upt AS mu ON pj.upt_id=mu.id 
                    WHERE pj.status='DISETUJUI' 
                    AND pj.jenis_perusahaan='PEMILIK_BARANG'
                    AND is_active='1' AND REPLACE(REPLACE(nomor_identitas,'.',''),'-','')=?
                    AND mu.kode=?";
            
            $query = $this->db->query($sql, array($idpj, $kdupt));
        }

        if($idpj<>'' && $idppjk<>'') {
            $idpj = str_replace('-','',str_replace('.','',$idpj));
            $idppjk = str_replace('-','',str_replace('.','',$idppjk));
            // $kdupt = $kdupt;

            // $this->db->select("pj.id,calo.kode_perusahaan AS kd_perusahaan,calo.jenis_perusahaan,calo.kota,calo.provinsi_id");
            // // $this->db->from("calo_pengguna_jasa AS pj");
            // $this->db->where("REPLACE(REPLACE(imp.nomor_identitas,'.',''),'-','')", $idpj);
            // $this->db->where("REPLACE(REPLACE(calo.nomor_identitas,'.',''),'-','')", $idppjk);
            // $this->db->where("mu.kode", $kdupt);
            // $this->db->where("imp.status", "DISETUJUI");
            // $this->db->where("imp.is_active", "1");
            // $this->db->where("calo.status", "DISETUJUI");
            // $this->db->where("calo.is_active", "1");
            // $this->db->join("pengguna_jasa AS imp", "pj.pengguna_jasa_id = imp.id");
            // $this->db->join("pengguna_jasa AS calo", "pj.calo_id = calo.id");
            // $this->db->join("master_upt AS mu", "mu.id=calo.upt_id");
            // return $this->db->get('calo_pengguna_jasa AS pj')->result_array();

            $sql 	=  "SELECT pj.id,calo.kode_perusahaan AS kd_perusahaan,calo.jenis_perusahaan,calo.kota,calo.provinsi_id
                        FROM calo_pengguna_jasa AS pj 
                        JOIN pengguna_jasa AS imp ON pj.pengguna_jasa_id = imp.id
                        JOIN pengguna_jasa AS calo ON pj.calo_id = calo.id
                        JOIN master_upt AS mu ON mu.id=calo.upt_id
                        WHERE REPLACE(REPLACE(imp.nomor_identitas,'.',''),'-','')=?
                        AND REPLACE(REPLACE(calo.nomor_identitas,'.',''),'-','')=?
                        AND imp.status='DISETUJUI' AND imp.is_active='1' AND mu.kode=?
                        AND calo.status='DISETUJUI' AND calo.is_active='1'";
            
            $query = $this->db->query($sql, array($idpj, $idppjk, $kdupt));
        }

        // Return hasil query
        // return $query;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }

    }

    function getDataNew($idpj, $idppjk, $kdupt) {
        $reg = $this->load->database('regptk', TRUE);
        $npwp = substr($idpj, 0, 16);
        $nitku = substr($idpj, 16, 22);

        $cari = array(
            'pj.nomor_identitas' => $npwp,
            'pj.nitku' => $nitku,
            'r.master_upt_id' => $kdupt,
            'r.status' => 'DISETUJUI'
        );
        if($idppjk) {
            $cari['jk.nomor_identitas_ppjk'] = $idppjk;
            $cari['jk.status_ppjk'] = "AKTIF";
        }
        if ($idppjk) {
            $reg->select('jk.id, jk.pj_barantin_id as pemilik_id, jk.jenis_perusahaan, r.blockir as blokir_pemilik, pj.provinsi_id, pj.kota, jk.master_provinsi_id as provinsi_ppjk, jk.master_kota_kab_id as kota_ppjk');
        } else {
            $reg->select('u.id, u.username as kode_perusahaan, pj.jenis_perusahaan, r.blockir as blokir, pj.provinsi_id, pj.kota');
        }
        $reg->from('pj_barantins as pj');
        $reg->where($cari);
        if($idppjk) {
            $reg->join('ppjks as jk', 'pj.id=jk.pj_barantin_id');
        }
        $reg->join('registers as r', 'pj.id=r.pj_barantin_id');
        $reg->join('users as u', 'pj.user_id=u.id');
        return $reg->get()->result_array();
    }
}