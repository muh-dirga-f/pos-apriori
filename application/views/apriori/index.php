<?php
$data_item = $item;
$minSupport = 3;
$arr = [];
for ($i = 0; $i < count($data_item); $i++) {
  $ar = [];
  $val = explode(",", $data_item[$i]["item"]);
  for ($j = 0; $j < count($val); $j++) {
    $ar[] = $val[$j];
  }
  array_push($arr, $ar);
}

$frekuensi_item = frekuensiItem($arr);
$dataEliminasi = eliminasiItem($frekuensi_item, $minSupport);

// print_r($dataEliminasi);

do {
  $pasangan_item = pasanganItem($dataEliminasi);
  $frekuensi_item = FrekuensiPasanganItem($pasangan_item, $arr);
  $dataEliminasi = eliminasiItem($frekuensi_item, $minSupport);
} while ($dataEliminasi == $frekuensi_item);


function frekuensiItem($data)
{
  $arr = [];
  for ($i = 0; $i < count($data); $i++) {
    $jum = array_count_values($data[$i]);
    foreach ($jum as $key => $v) {
      if (array_key_exists($key, $arr)) {
        $arr[$key] += 1;
      } else {
        $arr[$key] = 1;
      }
    }
  }
  return $arr;
}

function eliminasiItem($data, $minSupport)
{
  $arr = [];
  foreach ($data as $key => $v) {
    if ($v >= $minSupport) {
      $arr[$key] = $v;
    }
  }
  return $arr;
}
function pasanganItem($data_filter)
{
  $n = 0;
  $arr = [];
  foreach ($data_filter as $key1 => $v1) {
    $m = 1;
    foreach ($data_filter as $key2 => $v2) {
      $str = explode("_", $key2);
      for ($i = 0; $i < count($str); $i++) {

        if (!strstr($key1, $str[$i])) {
          if ($m > $n + 1 && count($data_filter) > $n + 1) {
            $arr[$key1 . "_" . $str[$i]] = 0;
          }
        }
      }
      $m++;
    }
    $n++;
  }
  return $arr;
}

function frekuensiPasanganItem($data_pasangan, $data)
{
  $arr = $data_pasangan;
  $ky = "";
  $kali = 0;
  foreach ($data_pasangan as $key1 => $k) {
    for ($i = 0; $i < count($data); $i++) {
      $kk = explode("_", $key1);
      $jm = 0;
      for ($k = 0; $k < count($kk); $k++) {

        for ($j = 0; $j < count($data[$i]); $j++) {
          if ($data[$i][$j] == $kk[$k]) {
            $jm += 1;
            break;
          }
        }
      }
      if ($jm > count($kk) - 1) {
        $arr[$key1] += 1;
      }
    }
  }
  return $arr;
}
?>
<section class="content">
  <h3 class="text-left">Algoritma Apriori</h3>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class='box-header  with-border'>
          <h3 class='box-title'>Data Penjualan</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataPenjualan">
              <thead class="text-center">
                <tr>
                  <th width="80px" valign="middle" class="text-center">Id</th>
                  <th class="text-left">Item</th>
                </tr>
              </thead>
              <tbody>
                <?php
                for ($i = 0; $i < count($data_item); $i++) {
                  echo ("<tr>");
                  echo ("<td class='text-center'>" . $data_item[$i]["id"] . "</td>");
                  echo ("<td>" . $data_item[$i]["item"] . "</td>");
                  echo ("</tr>");
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="box box-info">
        <div class='box-header  with-border'>
          <h3 class='box-title'>Proses Apriori</h3>
        </div>
        <div class="box-body">
          <b>Iterasi 1 (Menghitung Frekuensi Awal Itemset:)</b>
          <div class="table-responsive">
            <table class="table table-bordered table-earning">
              <thead>
                <tr>
                  <th>Item</th>
                  <th width="50%">Frekuensi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                  $frekuensi_item = frekuensiItem($arr);
                  foreach ($frekuensi_item as $key => $val) {
                    echo ("<tr>");
                    echo ("<td>" . $key . "</td>");
                    echo ("<td>" . $val . "</td>");
                    echo ("</tr>");
                  }
                  ?>
                </tr>
              <tbody>
            </table>
          </div>
          <span style="margin-left:8px">
            Eliminasi Iterasi 1 (Membuang item yang tidak memenuhi nilai minimum suppor) sehingga menjadi:
          </span>
          <div class="table-responsive">
            <table class="table table-bordered table-earning">
              <thead>
                <tr>
                  <th>Item</th>
                  <th width="50%">Frekuensi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                  $dataEliminasi = eliminasiItem($frekuensi_item, $minSupport);
                  foreach ($dataEliminasi as $key => $val) {
                    echo ("<tr>");
                    echo ("<td>" . $key . "</td>");
                    echo ("<td>" . $val . "</td>");
                    echo ("</tr>");
                  }
                  ?>
                </tr>
              <tbody>
            </table>
          </div>
          <?php
          $iterasi = 2;
          do {
          ?>
            <b>Iterasi <?php echo $iterasi; ?> (Menghitung Frekuensi Awal Itemset:)</b>
            <div class="table-responsive table--no-card m-b-30">
              <table class="table table-bordered table-earning">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th width="50%">Frekuensi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php
                    $pasangan_item = pasanganItem($dataEliminasi);
                    $frekuensi_item = FrekuensiPasanganItem($pasangan_item, $arr);
                    foreach ($frekuensi_item as $key => $val) {

                      $ex = explode("_", $key);
                      $item = "";
                      $vl = "";
                      for ($k = 0; $k < count($ex); $k++) {
                        if ($k !== count($ex) - 1) {
                          $item .= "," . $ex[$k];
                        } else {
                          $vl = $ex[$k];
                        }
                      }
                      $aturan_asosiasi[] = array("item" => substr($item, 1), "val" => $vl, "sc" => $val);
                      echo ("<tr>");
                      echo ("<td>" . $key . "</td>");
                      echo ("<td>" . $val . "</td>");
                      echo ("</tr>");
                    }
                    ?>
                  </tr>
                <tbody>
              </table>
            </div>
            <span style="margin-left:8px">
              Eliminasi Iterasi <?php echo $iterasi; ?> (Membuang item yang tidak memenuhi nilai minimum suppor) sehingga menjadi:
            </span>
            <div class="table-responsive table--no-card m-b-30">
              <table class="table table-bordered table-earning">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th width="50%">Frekuensi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php
                    $dataEliminasi = eliminasiItem($frekuensi_item, $minSupport);
                    foreach ($dataEliminasi as $key => $val) {
                      echo ("<tr>");
                      // for ($j = 0; $j < count($frekuensi_item[$i]); $j++) {
                      echo ("<td>" . $key . "</td>");
                      echo ("<td>" . $val . "</td>");
                      // }
                      echo ("</tr>");
                    }
                    ?>
                  </tr>
                <tbody>
              </table>
            </div>
          <?php $iterasi++;
          } while ($dataEliminasi == $frekuensi_item)
          ?>
          <b>Karena tidak ada lagi frekuensi yang harus di eliminasi maka iterasi di hentikan.</b><br>
          <b>Hitung Support dan Confident:</b><br>
          <?php
          for ($i = 0; $i < count($aturan_asosiasi); $i++) {
            $x = 0;
            echo $i + 1 . ". Nilai confident, ";
            echo $aturan_asosiasi[$i]["item"] . " => " . $aturan_asosiasi[$i]["val"] . "=";
            $ex = explode(",", $aturan_asosiasi[$i]["item"]);

            for ($l = 0; $l < count($arr); $l++) {
              $jum = 0;
              for ($k = 0; $k < count($ex); $k++) {

                for ($j = 0; $j < count($arr[$l]); $j++) {
                  if ($arr[$l][$j] == $ex[$k]) {
                    $jum += 1;
                  }
                }
              }
              if (count($ex) == $jum) {
                $x += 1;
              }
            }
            $convident = (floatval($aturan_asosiasi[$i]["sc"]) / floatval($x)) * 100;
            $aturan_asosiasi[$i]["c"] = number_format($convident, 2, ".", ",");
            echo $aturan_asosiasi[$i]["sc"] . "/" . $x . "=" . number_format(floatval($aturan_asosiasi[$i]["sc"]) / floatval($x), 2, ".", ",") . "=" . number_format($convident, 0, ".", ",") . "%";
            echo  "<br>";
          }
          ?>
          <b>Berdasarkan algoritma Apriori, maka aturan asosiasi yang berhasil didapatkan adalah sebagai berikut : </b>
          <br>
          <?php
          for ($i = 0; $i < count($aturan_asosiasi); $i++) {
            $x = 0;
            echo $i + 1 . ". Jika " . $aturan_asosiasi[$i]["item"] . " maka " . $aturan_asosiasi[$i]["val"] . "<br>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  $(document).ready(function() {
    $('#dataPenjualan').DataTable();
  });
</script>