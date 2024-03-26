<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	

	if(isset($_POST['textBoxValue'])){
		$output = '';
		$vendorIDString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		

		$sql = 'SELECT vendorID FROM vendor WHERE vendorID LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$vendorIDString]);
		

		if($stmt->rowCount() > 0){
			

			$output = '<ul class="list-unstyled suggestionsList" id="vendorDetailsVendorIDSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['vendorID'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>