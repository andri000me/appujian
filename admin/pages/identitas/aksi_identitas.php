<?php
// session_start();
include "../../../config/koneksi.php";
if (empty($_SESSION['admin_id'])){
} else {

$module=$_GET['q'];
$act=$_GET['act'];

if ($module=='identitas' AND $act=='update'){
    mysqli_query($conn, "UPDATE tb_identitas  SET  sekolah = '$_POST[sekolah]',
                                          alamat = '$_POST[alamat]',
                                          judul = '$_POST[judul]',
                                          deskripsi = '$_POST[deskripsi]'
                                      WHERE identitas_id      = '$_POST[id]'");
  header('location:../../pages.php?q='.$module);
}
}
?>
