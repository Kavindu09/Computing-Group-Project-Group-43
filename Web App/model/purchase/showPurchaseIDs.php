<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	

	if(isset($_POST['textBoxValue'])){
		$output = '';
		$purchaseIDString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		

		$sql = 'SELECT purchaseID FROM purchase WHERE purchaseID LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$purchaseIDString]);
		

		if($stmt->rowCount() > 0){
			

			$output = '<ul class="list-unstyled suggestionsList" id="purchaseDetailsPurchaseIDSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['purchaseID'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>