<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');


	if(isset($_POST['vendorDetailsVendorID'])){
		
		$vendorID = htmlentities($_POST['vendorDetailsVendorID']);
		
		$vendorDetailsSql = 'SELECT * FROM vendor WHERE vendorID = :vendorID';
		$vendorDetailsStatement = $conn->prepare($vendorDetailsSql);
		$vendorDetailsStatement->execute(['vendorID' => $vendorID]);
		

		if($vendorDetailsStatement->rowCount() > 0) {
			$row = $vendorDetailsStatement->fetch(PDO::FETCH_ASSOC);
			echo json_encode($row);
		}
		$vendorDetailsStatement->closeCursor();
	}
?>