<?php
	if(!isset($_GET['q'])){
		header('Location: index.php');
	}
	include 'cek-akses.php';
	$q = $_GET['q'];
	function jk($var){
		if ($var == "l"){
			$result = "Laki-Laki";
		}
		else
			$result = "Perempuan";
		return $result;
	}
	if (isset($_GET['page'])){
		$page = $_GET['page'];
	}
	if(!isset($page)){
		$page = 1;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <title>Data Artis</title>
	   	<meta name="description" content="Tugas PBW">
	    <meta name="author" content="Fazlur Rahman">

	    <!-- Le styles -->
	    <link href="css/bootstrap.css" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css">
	    <link href="css/custom.css" rel="stylesheet">
	    <!-- Javascript -->
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</head>
	<div class="navbar navbar-inverse navbar-fixed-top">
	    <div class="navbar-inner">
	      	<div class="container">
		        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		        </a>
		        <a class="brand" href="index.php">Data Artis</a>
		        <div class="nav-collapse">
		          <ul class="nav">
		            <li class="active"><a href="index.php">Home</a></li>
		            <li><a href="tambah.php"><i class='icon-white icon-plus'></i> Tambah Data</a></li>
		          </ul>
		          <form class="navbar-search pull-left" action="search.php">
		            <input type="text" class="search-query span2" name="q" placeholder="Search">
		          </form>
		          <ul class="nav pull-right">
			          	<li><a href="logout.php"><i class='icon-white icon-off'></i> Logout</a></li>
			      </ul>
		        </div><!-- /.nav-collapse -->
	      	</div>
	    </div><!-- /navbar-inner -->
  	</div>
	<?php
		$id=mysql_connect("localhost","root","");
		if($id){
			// pilih database
			mysql_select_db("artis");
			// kirim peritah SQL
			$sql = "Select * From biodata where `nama` LIKE '%$q%'";
			$hasil = mysql_query($sql, $id);
			$total = mysql_num_rows($hasil);
			$num_rec_per_page = 4;
			$mulai = ($page-1)*$num_rec_per_page;
			$num_of_page = ceil($total/$num_rec_per_page);
			$sql = "Select * From biodata where `nama` LIKE '%$q%' limit $mulai,$num_rec_per_page";
			$hasil = mysql_query($sql, $id);
			if($total == 0){
				$kosong = 'yes';
			}
			// ambil hasilnya
			?>
	<br><br>
	<div class="container">
		<table class="table table-striped">
			<tr>
				<b><td><b>ID<td><b>Foto<td><b>Nama<td><b>Tanggal Lahir<td><b>Tempat Kelahiran<td><b>Jenis Kelamin<td><b>Tinggi
				<td colspan=3 align=center><b>Action</b>
			</tr>
<?php
	while($record=mysql_fetch_array($hasil))
	{
		$id_artis = $record['id_artis'];
		$image_content = $record['foto'];
		print("<tr>");
		print("<td>" . $id_artis . " ");
		echo '<td><img class="image-wrap" src="data:image/png;base64,' . base64_encode($image_content) . '" />';
		print("<td>" . $record['nama'] . " ");
		print("<td>" . $record['dob'] . " ");
		print("<td>" . $record['tl'] . "</td>");
		print("<td>" . jk($record['jk']) . " ");
		print("<td>" . $record['tinggi'] . " M");
		print("<td><a href='edit.php?id=$id_artis'>
			<button class='button' title='Edit'><i class='icon-edit icon-large'></i></button></a>");
		
                
		?><td><form name='hapus' method='get' action='hapus.php' onsubmit="return confirm('Click OK or Cancel to Continue');">
			<input type='hidden' name='id' value='<?php echo $id_artis;?>'>
			<button type='submit' class='button' title='Hapus'><i class='icon-trash icon-large'></i></button></a></form>
		<?php
		print("<td><a href='cetak.php?id=$id_artis'>
			<button class='button' title='Cetak'><i class='icon-print icon-large'></i></button></a>");
		print("</tr>");
	}
	// tutup koneksi
	mysql_close($id);
}
?>
		</table>
		<?php
		if(isset($kosong) and $kosong == 'yes'){
			echo '<div class="alert"><center><img src="img/sorry(iced-gif).gif" class="img-circle"><h1>Maaf<br><h3>Data yang anda cari tidak dapat ditemukan<br>Silahkan coba dengan keyword lain :)<br></div>';
		}
		?>
		<div class="pagination">
		  <ul>
		  	<?php
			for($hal=1; $hal<=$num_of_page;$hal++){
				if ($page == $hal){ $active = 'class ="active"'; }
				else { $active = ""; }
				echo "<li " . $active . " ><a href=search.php?q=$q&page=$hal>" . $hal . "</a></li>";
			}
			?>
		  </ul>
		</div>
	</div>