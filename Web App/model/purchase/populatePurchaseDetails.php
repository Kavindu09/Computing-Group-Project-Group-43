<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');


	if(isset($_POST['purchaseDetailsPurchaseID'])){
		
		$purchaseID = htmlentities($_POST['purchaseDetailsPurchaseID']);
		
		$purchaseDetailsSql = 'SELECT * FROM purchase WHERE purchaseID = :purchaseID';
		$purchaseDetailsStatement = $conn->prepare($purchaseDetailsSql);
		$purchaseDetailsStatement->execute(['purchaseID' => $purchaseID]);
		

		if($purchaseDetailsStatement->rowCount() > 0) {
			$row = $purchaseDetailsStatement->fetch(PDO::FETCH_ASSOC);
			echo json_encode($row);
		}
		$purchaseDetailsStatement->closeCursor();
	}
?>