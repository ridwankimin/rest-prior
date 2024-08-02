<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PtkHistoryModel extends CI_Model
{
    function getDataHistory($data) {
        $dbapps = $this->load->database('apps', TRUE);
        $dbapps->select('ptk.id as idPtk,no_aju,tgl_aju,jenis_permohonan,jenis_karantina
        ,nama_pemohon,nama_pengirim,ptk.created_at,alasan_penolakan,nama_umum_tercetak AS komoditas,
        nama_penerima,status_ptk,m.nama as nama_upt,m.nama_satpel');
        $dbapps->join('master_upt as m','ptk.kode_satpel=m.id','left');
        $dbapps->join('ptk_komoditas as k','ptk.id=k.ptk_id','left');
        $dbapps->where($data);
        $dbapps->order_by('ptk.created_at', 'desc');
        return $dbapps->get('ptk')->result_array();
    }
}