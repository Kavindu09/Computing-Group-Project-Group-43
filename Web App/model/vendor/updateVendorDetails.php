<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	

	if(isset($_POST['vendorDetailsVendorID'])) {
		
		$vendorDetailsVendorID = htmlentities($_POST['vendorDetailsVendorID']);
		$vendorDetailsVendorFullName = htmlentities($_POST['vendorDetailsVendorFullName']);
		$vendorDetailsVendorMobile = htmlentities($_POST['vendorDetailsVendorMobile']);
		$vendorDetailsVendorPhone2 = htmlentities($_POST['vendorDetailsVendorPhone2']);
		$vendorDetailsVendorEmail = htmlentities($_POST['vendorDetailsVendorEmail']);
		$vendorDetailsVendorAddress = htmlentities($_POST['vendorDetailsVendorAddress']);
		$vendorDetailsVendorAddress2 = htmlentities($_POST['vendorDetailsVendorAddress2']);
		$vendorDetailsVendorCity = htmlentities($_POST['vendorDetailsVendorCity']);
		$vendorDetailsVendorDistrict = htmlentities($_POST['vendorDetailsVendorDistrict']);
		$vendorDetailsStatus = htmlentities($_POST['vendorDetailsStatus']);
		
		

		if(!empty($vendorDetailsVendorID)){

			if(!empty($vendorDetailsVendorFullName) && !empty($vendorDetailsVendorMobile) && !empty($vendorDetailsVendorAddress)) {
				

				if(filter_var($vendorDetailsVendorMobile, FILTER_VALIDATE_INT) === 0 || filter_var($vendorDetailsVendorMobile, FILTER_VALIDATE_INT)) {

				} else {

					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid mobile number</div>';
					exit();
				}
				

				if(empty($vendorDetailsVendorID)){
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter the Vendor ID to update that vendor. You can find the Vendor ID using the Search tab</div>';
					exit();
				}
				

				if(isset($vendorDetailsVendorPhone2)){
					if(filter_var($vendorDetailsVendorPhone2, FILTER_VALIDATE_INT) === 0 || filter_var($vendorDetailsVendorPhone2, FILTER_VALIDATE_INT)) {

					} else {

						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for phone number 2.</div>';
						exit();
					}
				}
				

				if(!empty($vendorDetailsVendorEmail)) {
					if (filter_var($vendorDetailsVendorEmail, FILTER_VALIDATE_EMAIL) === false) {

						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid email</div>';
						exit();
					}
				}


				$vendorIDSelectSql = 'SELECT vendorID FROM vendor WHERE vendorID = :vendorID';
				$vendorIDSelectStatement = $conn->prepare($vendorIDSelectSql);
				$vendorIDSelectStatement->execute(['vendorID' => $vendorDetailsVendorID]);
				
				if($vendorIDSelectStatement->rowCount() > 0) {
					

					$purchaseVendorNameSql = 'UPDATE purchase SET vendorName = :vendorName WHERE vendorID = :vendorID';
					$purchaseVendorNameStatement = $conn->prepare($purchaseVendorNameSql);
					$purchaseVendorNameStatement->execute(['vendorName' => $vendorDetailsVendorFullName, 'vendorID' => $vendorDetailsVendorID]);
					
					$updateVendorDetailsSql = 'UPDATE vendor SET fullName = :fullName, email = :email, mobile = :mobile, phone2 = :phone2, address = :address, address2 = :address2, city = :city, district = :district, status = :status WHERE vendorID = :vendorID';
					$updateVendorDetailsStatement = $conn->prepare($updateVendorDetailsSql);
					$updateVendorDetailsStatement->execute(['fullName' => $vendorDetailsVendorFullName, 'email' => $vendorDetailsVendorEmail, 'mobile' => $vendorDetailsVendorMobile, 'phone2' => $vendorDetailsVendorPhone2, 'address' => $vendorDetailsVendorAddress, 'address2' => $vendorDetailsVendorAddress2, 'city' => $vendorDetailsVendorCity, 'district' => $vendorDetailsVendorDistrict, 'vendorID' => $vendorDetailsVendorID, 'status' => $vendorDetailsStatus]);
					
					$updateVendorInPurchaseTableSql = 'UPDATE purchase SET vendorName = :vendorName WHERE vendorID = :vendorID';
					$updateVendorInPurchaseTableStatement = $conn->prepare($updateVendorInPurchaseTableSql);
					$updateVendorInPurchaseTableStatement->execute(['vendorName' => $vendorDetailsVendorFullName, 'vendorID' => $vendorDetailsVendorID]);
					
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Vendor details updated.</div>';
					exit();
				} else {

					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Vendor ID does not exist in DB. Therefore, update not possible.</div>';
					exit();
				}
				
			} else {

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
				exit();
			}
		} else {
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter the Vendor ID to update that vendor. You can find the Vendor ID using the Search tab</div>';
			exit();
		}
	}
?>