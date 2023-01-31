<?php

class Apriori extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    chek_role();
    $this->load->database();
  }

  public function index()
  {
    $penjualan = $this->db
      ->join('barang', 'barang.id_barang = penjualan.id_barang', 'left')
      ->group_by('penjualan.id_dtlpen')
      ->get('penjualan')->result_array();
    $item = array();
    $i = 1;
    foreach ($penjualan as $key => $each) {
      $data = $this->db
        ->join('barang', 'barang.id_barang = penjualan.id_barang', 'left')
        ->where('penjualan.id_dtlpen', $each['id_dtlpen'])
        ->get('penjualan')->result_array();
      $nama = [];
      foreach ($data as $key => $value) {
        array_push($nama, $value['nama_barang']);
      }
      array_push($item, ['id' => $i, 'item' => join(",", $nama)]);
      $i++;
    }
    $data['item'] = $item;
    $data['active'] = "apriori";
    $this->template->load('template/template', 'apriori/index', $data);
  }
}
