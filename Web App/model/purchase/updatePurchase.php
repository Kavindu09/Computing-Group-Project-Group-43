<?php



	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['purchaseDetailsPurchaseID'])){

		$purchaseDetailsItemNumber = htmlentities($_POST['purchaseDetailsItemNumber']);
		$purchaseDetailsPurchaseDate = htmlentities($_POST['purchaseDetailsPurchaseDate']);
		$purchaseDetailsItemName = htmlentities($_POST['purchaseDetailsItemName']);
		$purchaseDetailsQuantity = htmlentities($_POST['purchaseDetailsQuantity']);
		$purchaseDetailsUnitPrice = htmlentities($_POST['purchaseDetailsUnitPrice']);
		$purchaseDetailsPurchaseID = htmlentities($_POST['purchaseDetailsPurchaseID']);
		$purchaseDetailsVendorName = htmlentities($_POST['purchaseDetailsVendorName']);
		
		$quantityInOriginalOrder = 0;
		$quantityInNewOrder = 0;
		$originalStockInItemTable = 0;
		$newStock = 0;
		$originalPurchaseItemNumber = '';
		

		if(isset($purchaseDetailsItemNumber) && isset($purchaseDetailsPurchaseDate) && isset($purchaseDetailsQuantity) && isset($purchaseDetailsUnitPrice)){
			

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
			
			if($purchaseDetailsPurchaseID == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a Purchase ID.</div>';
				exit();
			}
			
			if($purchaseDetailsItemNumber == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Item Number.</div>';
				exit();
			}
			
			if($purchaseDetailsQuantity == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter quantity.</div>';
				exit();
			}
			
			$orginalPurchaseQuantitySql = 'SELECT * FROM purchase WHERE purchaseID = :purchaseID';
			$originalPurchaseQuantityStatement = $conn->prepare($orginalPurchaseQuantitySql);
			$originalPurchaseQuantityStatement->execute(['purchaseID' => $purchaseDetailsPurchaseID]);
			

			$vendorIDsql = 'SELECT * FROM vendor WHERE fullName = :fullName';
			$vendorIDStatement = $conn->prepare($vendorIDsql);
			$vendorIDStatement->execute(['fullName' => $purchaseDetailsVendorName]);
			$row = $vendorIDStatement->fetch(PDO::FETCH_ASSOC);
			$vendorID = $row['vendorID'];
			
			if($originalPurchaseQuantityStatement->rowCount() > 0){
				
				$originalQtyRow = $originalPurchaseQuantityStatement->fetch(PDO::FETCH_ASSOC);
				$quantityInOriginalOrder = $originalQtyRow['quantity'];
				$originalOrderItemNumber = $originalQtyRow['itemNumber'];


				if($originalOrderItemNumber !== $purchaseDetailsItemNumber) {

					$newItemCurrentStockSql = 'SELECT * FROM item WHERE itemNumber = :itemNumber';
					$newItemCurrentStockStatement = $conn->prepare($newItemCurrentStockSql);
					$newItemCurrentStockStatement->execute(['itemNumber' => $purchaseDetailsItemNumber]);
					
					if($newItemCurrentStockStatement->rowCount() < 1){

						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Item Number does not exist in DB. If you want to update this item, please add it to DB first.</div>';
						exit();
					}
					

					$newItemRow = $newItemCurrentStockStatement->fetch(PDO::FETCH_ASSOC);
					$originalQuantityForNewItem = $newItemRow['stock'];
					$enteredQuantityForNewItem = $purchaseDetailsQuantity;
					$newItemNewStock = $originalQuantityForNewItem + $enteredQuantityForNewItem;
					

					$newItemStockUpdateSql = 'UPDATE item SET stock = :stock WHERE itemNumber = :itemNumber';
					$newItemStockUpdateStatement = $conn->prepare($newItemStockUpdateSql);
					$newItemStockUpdateStatement->execute(['stock' => $newItemNewStock, 'itemNumber' => $purchaseDetailsItemNumber]);
					

					$previousItemCurrentStockSql = 'SELECT * FROM item WHERE itemNumber=:itemNumber';
					$previousItemCurrentStockStatement = $conn->prepare($previousItemCurrentStockSql);
					$previousItemCurrentStockStatement->execute(['itemNumber' => $originalOrderItemNumber]);
					

					$previousItemRow = $previousItemCurrentStockStatement->fetch(PDO::FETCH_ASSOC);
					$currentQuantityForPreviousItem = $previousItemRow['stock'];
					$previousItemNewStock = $currentQuantityForPreviousItem - $quantityInOriginalOrder;
					

					$previousItemStockUpdateSql = 'UPDATE item SET stock = :stock WHERE itemNumber = :itemNumber';
					$previousItemStockUpdateStatement = $conn->prepare($previousItemStockUpdateSql);
					$previousItemStockUpdateStatement->execute(['stock' => $previousItemNewStock, 'itemNumber' => $originalOrderItemNumber]);
					
					$updatePurchaseDetailsSql = 'UPDATE purchase SET itemNumber = :itemNumber, purchaseDate = :purchaseDate, itemName = :itemName, unitPrice = :unitPrice, quantity = :quantity, vendorName = :vendorName, vendorID = :vendorID WHERE purchaseID = :purchaseID';
					$updatePurchaseDetailsStatement = $conn->prepare($updatePurchaseDetailsSql);
					$updatePurchaseDetailsStatement->execute(['itemNumber' => $purchaseDetailsItemNumber, 'purchaseDate' => $purchaseDetailsPurchaseDate, 'itemName' => $purchaseDetailsItemName, 'unitPrice' => $purchaseDetailsUnitPrice, 'quantity' => $purchaseDetailsQuantity, 'vendorName' => $purchaseDetailsVendorName, 'vendorID' => $vendorID, 'purchaseID' => $purchaseDetailsPurchaseID]);
					
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Purchase details added to database and stock values updated.</div>';
					exit();
					
				} else {

					

					$stockSql = 'SELECT * FROM item WHERE itemNumber=:itemNumber';
					$stockStatement = $conn->prepare($stockSql);
					$stockStatement->execute(['itemNumber' => $purchaseDetailsItemNumber]);
					
					if($stockStatement->rowCount() > 0){

						
						$row = $stockStatement->fetch(PDO::FETCH_ASSOC);
						$quantityInNewOrder = $purchaseDetailsQuantity;
						$originalStockInItemTable = $row['stock'];
						$newStock = $originalStockInItemTable + ($quantityInNewOrder - $quantityInOriginalOrder);
						
						$updateStockSql = 'UPDATE item SET stock = :stock WHERE itemNumber = :itemNumber';
						$updateStockStatement = $conn->prepare($updateStockSql);
						$updateStockStatement->execute(['stock' => $newStock, 'itemNumber' => $purchaseDetailsItemNumber]);
						
						$updatePurchaseDetailsSql = 'UPDATE purchase SET purchaseDate = :purchaseDate, unitPrice = :unitPrice, quantity = :quantity, vendorName = :vendorName, vendorID = :vendorID WHERE purchaseID = :purchaseID';
						$updatePurchaseDetailsStatement = $conn->prepare($updatePurchaseDetailsSql);
						$updatePurchaseDetailsStatement->execute(['purchaseDate' => $purchaseDetailsPurchaseDate, 'unitPrice' => $purchaseDetailsUnitPrice, 'quantity' => $purchaseDetailsQuantity, 'vendorName' => $purchaseDetailsVendorName, 'vendorID' => $vendorID, 'purchaseID' => $purchaseDetailsPurchaseID]);
						
						echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Purchase details added to database and stock values updated.</div>';
						exit();
						
					} else {

						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Item does not exist in DB. Therefore, first enter this item to DB using the <strong>Item</strong> tab.</div>';
						exit();
					}	
					
				}
	
			} else {
				

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Purchase details does not exist in DB for the given Purchase ID. Therefore, can\'t update.</div>';
				exit();
				
			}

		} else {

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
			exit();
		}
	}
?>