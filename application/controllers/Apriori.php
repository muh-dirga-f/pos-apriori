<?php
class Apriori extends CI_Controller
{
  function __construct()
  {
		parent::__construct();
    chek_role();
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
    $this->template->load('template/template', 'apriori/index', $data);
  }
}
