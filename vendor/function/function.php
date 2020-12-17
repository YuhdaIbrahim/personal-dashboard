<?php 
 // koneksi database
$conn = mysqli_connect("localhost","root","","phpdasar");
// ambil data mahasiswa
function query ($query){
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	// looping pengambilan data
	while ($row = mysqli_fetch_assoc($result)) {
		# code pengambilan data dari dalam db(penambahan element baru di tiap array)
		$rows[] = $row;
	}
	return $rows;
}


// tambah data mahasiswa
function tambah($data) {
	// ambil data dari tiap element dalam form
 	global $conn;
 	$nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);

    // uploud gambar bila gagal maka fungsi lainnya tidak di jalankan
    $gambar = upload();
    if ( !$gambar ) {
    	# code...
    	return false;
    }

	 // query insert data
	// untuk mempermudah dan terlihat lebih enak
	$query = "INSERT INTO  mahasiswa
	            VALUES
	          ('','$nama','$nrp','$email','$jurusan','$gambar')
	          ";
	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);
}

	// fungsi uploud
	function upload()	{
		// ada 5 array yg dihasilkan enctype(input type FILES) array(assosiatif) yaitu : name,type,size,error,tmp_name(tempat penyimpanan sementara
		// variabel baru untuk uploud(type di pisah dengan variabel baru)
		$namaFile = $_FILES['gambar']['name'];
		$ukuranFile = $_FILES['gambar']['size'];
		$error = $_FILES['gambar']['error'];
		$tmpName= $_FILES['gambar']['tmp_name'];

	// mengecek apakah ada atau tidak ada gambvar yg di uploud
	if ($error === 4) {
		# code...
		echo "<script>
			alert ('pilih gambar terlebih dahulu');
			</script>
			";
			return false;
	}

	// memastikan agar hanya gambar yg bisa di uploud
	$ekstensiGambarValid = ['jpg','png','jpeg'];
	// sedikit trick untuk memecah gambar(ambil ekstensi gambarnya)
	$ekstensiGambar = explode('.', $namaFile);
	// pastikan ambil value akhirnya(gunakan end), dan paksa nilainya menjadi huruf kecil(strtolower)
	$ekstensiGambar = strtolower(end( $ekstensiGambar ));
	// 
	if ( !in_array($ekstensiGambar, $ekstensiGambarValid )){
		echo "<script>
			alert ('yang anda uploud bukan gambar');
			</script>
			";
			return false;
	}


	// cek ukuran gambar(pembatasan)
	if ($ukuranFile > 2000000 ) {
		echo "<script>
			alert ('ukuran terlalu besar');
			</script>
			";
			return false;
	}
	// generate nama gambar baru agar tidak timpah
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensiGambar;

		// gambar dinyatakan aman
	move_uploaded_file($tmpName, 'image/' . $namaFileBaru);

	return $namaFileBaru ;
}



// hapus & ubah data
function hapus($id){
	global $conn;
	mysqli_query($conn,"DELETE FROM mahasiswa WHERE id = $id ");

	return mysqli_affected_rows($conn);
}

// tambah data mahasiswa
function ubah($data) {
	// ambil data dari tiap element dalam form
 	global $conn;
 	$id = $data["id"];
 	$nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);
    $gambarLama = htmlspecialchars($data["gambarLama"]);

    // mengecek apa user mengganti gambarnya atau ngga
    if ($_FILES['gambar']['error'] === 4) {
    	# code...
    	$gambar = $gambarLama;
    } else {
    	$gambar = upload();
    }


 // query upadte data
// untuk mengeupdate data
$query = "UPDATE mahasiswa SET
          nama = '$nama',
          nrp = '$nrp',
          email = '$email',
          jurusan = '$jurusan',
          gambar = '$gambar'
          WHERE id = $id
          ";

          // mengembalikan data ke form
		mysqli_query($conn, $query);
		return mysqli_affected_rows($conn);
}

function cari($keyword) {
	$query = "SELECT * FROM mahasiswa
				WHERE
				nama LIKE '%$keyword%' OR
				nrp LIKE '%$keyword%' OR
				email LIKE '%$keyword%' OR
				jurusan LIKE '%$keyword%'
				";
	return query($query);
}

 ?>

