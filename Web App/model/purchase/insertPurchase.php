<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['purchaseDetailsItemNumber'])){

		$purchaseDetailsItemNumber = htmlentities($_POST['purchaseDetailsItemNumber']);
		$purchaseDetailsPurchaseDate = htmlentities($_POST['purchaseDetailsPurchaseDate']);
		$purchaseDetailsItemName = htmlentities($_POST['purchaseDetailsItemName']);
		$purchaseDetailsQuantity = htmlentities($_POST['purchaseDetailsQuantity']);
		$purchaseDetailsUnitPrice = htmlentities($_POST['purchaseDetailsUnitPrice']);
		$purchaseDetailsVendorName = htmlentities($_POST['purchaseDetailsVendorName']);
		
		$initialStock = 0;
		$newStock = 0;
		

		if(isset($purchaseDetailsItemNumber) && isset($purchaseDetailsPurchaseDate) && isset($purchaseDetailsItemName) && isset($purchaseDetailsQuantity) && isset($purchaseDetailsUnitPrice)){
			

			if($purchaseDetailsItemNumber == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Item Number.</div>';
				exit();
			}
			

			if($purchaseDetailsItemName == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Item Name.</div>';
				exit();
			}
			

			if($purchaseDetailsQuantity == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Quantity.</div>';
				exit();
			}
			

			if($purchaseDetailsUnitPrice == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Unit Price.</div>';
				exit();
			}
			

			$purchaseDetailsItemNumber = filter_var($purchaseDetailsItemNumber, FILTER_SANITIZE_STRING);
			

			if(filter_var($purchaseDetailsQuantity, FILTER_VALIDATE_INT) === 0 || filter_var($purchaseDetailsQuantity, FILTER_VALIDATE_INT)){

			} else {

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for quantity.</div>';
				exit();
			}
			

			if(filter_var($purchaseDetailsUnitPrice, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($purchaseDetailsUnitPrice, FILTER_VALIDATE_FLOAT)){

			} else {

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for unit price.</div>';
				exit();
			}
			

			$stockSql = 'SELECT stock FROM item WHERE itemNumber=:itemNumber';
			$stockStatement = $conn->prepare($stockSql);
			$stockStatement->execute(['itemNumber' => $purchaseDetailsItemNumber]);
			if($stockStatement->rowCount() > 0){
				

				$vendorIDsql = 'SELECT * FROM vendor WHERE fullName = :fullName';
				$vendorIDStatement = $conn->prepare($vendorIDsql);
				$vendorIDStatement->execute(['fullName' => $purchaseDetailsVendorName]);
				$row = $vendorIDStatement->fetch(PDO::FETCH_ASSOC);
				$vendorID = $row['vendorID'];
				

				$insertPurchaseSql = 'INSERT INTO purchase(itemNumber, purchaseDate, itemName, unitPrice, quantity, vendorName, vendorID) VALUES(:itemNumber, :purchaseDate, :itemName, :unitPrice, :quantity, :vendorName, :vendorID)';
				$insertPurchaseStatement = $conn->prepare($insertPurchaseSql);
				$insertPurchaseStatement->execute(['itemNumber' => $purchaseDetailsItemNumber, 'purchaseDate' => $purchaseDetailsPurchaseDate, 'itemName' => $purchaseDetailsItemName, 'unitPrice' => $purchaseDetailsUnitPrice, 'quantity' => $purchaseDetailsQuantity, 'vendorName' => $purchaseDetailsVendorName, 'vendorID' => $vendorID]);
				

				$row = $stockStatement->fetch(PDO::FETCH_ASSOC);
				$initialStock = $row['stock'];
				$newStock = $initialStock + $purchaseDetailsQuantity;
				

				$updateStockSql = 'UPDATE item SET stock = :stock WHERE itemNumber = :itemNumber';
				$updateStockStatement = $conn->prepare($updateStockSql);
				$updateStockStatement->execute(['stock' => $newStock, 'itemNumber' => $purchaseDetailsItemNumber]);
				
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Purchase details added to database and stock values updated.</div>';
				exit();
				
			} else {

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Item does not exist in DB. Therefore, first enter this item to DB using the <strong>Item</strong> tab.</div>';
				exit();
			}

		} else {

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
			exit();
		}
	}
?>