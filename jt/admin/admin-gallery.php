<?php
	session_start();

	//check if user is logged in
	/*if (!isset($_SESSION['username'])) {
		header('Location: login.php');
	}*/

	require_once( "common.inc.php" );
	require_once( "config.php" );
	require_once( "Gallery.class.php" );

	displayPageHeader( "Ramen Dayo Menu Admin" );

	//Check if sent by update-menu and display relevant menu item updated/deleted message
	if ( isset( $_GET["del"] ) and $_GET["del"] == 1 )  {
		echo "<div class=\"floatleft\"><i class=\"fa fa-check-square\"></i></div>";
		echo "<div class=\"floatleft\"><p class=\"adminmsg\">Dish: '".$_GET["MenuTitle"]."' successfully deleted.</p>";
		echo "</div><div style=\"clear: both;\"></div>";
	} elseif  ( isset( $_GET["upd"] ) and $_GET["upd"] == 1 ) {
		echo "<div class=\"floatleft\"><i class=\"fa fa-check-square\"></i></div>";
		echo "<div class=\"floatleft\"><p class=\"adminmsg\">Dish: '".$_GET["MenuTitle"]."' has been successfully updated.<br>";
		echo "<a href=\"update-menu.php?ImageID=".$_GET["ImageID"]."\"> Edit this menu item again</a></p>";
		echo "</div><div style=\"clear: both;\"></div>";
	}
//if  ( isset( $_POST["added"] ) and $_POST["added"] == 1 ) {
	//echo "<div class=\"floatleft\"><i class=\"fa fa-check-square\"></i></div>";
	//echo "<div class=\"floatleft\"><p class=\"adminmsg\">Dish: '".$_POST["MenuTitle"]."' has been successfully added.<br>";
	//echo "<a href=\"update-menu.php?ImageID=".$_POST["ImageID"]."\"> Edit this menu item.</a></p>";
	//echo "</div><div style=\"clear: both;\"></div>";
//}


	if ( isset( $_POST["action"] ) and $_POST["action"] == "new-gallery" )  {
		//Add a new dish to the menu
		processForm();
	} elseif ( isset( $_POST["delaction"] ) and $_POST["delaction"] == "delete" ) {
		//Delete the dish from the menu
		deleteimage();
		displayForm( array(), array(), new Gallery( array() ) );
	} else {
		//Display form to allow users to add a new dish to the menu
		displayForm( array(), array(), new Gallery( array() ) );
	}

	//Display the current menu items from the database
  displayMenu();
	displayGallery();


 function deleteimage() {
	  $Gallery = new Gallery( array(
			"ImageID" => $_POST["ImageID"],
			"MenuTitle" => $_POST["MenuTitle"],
			) );
	if ( isset( $_POST["ImageID"] ) ) {
	//Delete the image record from the gallery table
		$Gallery->deletegallery();
	} else {
		echo "<p class=\"errormsg\">That image does not exist</p>";
	}

	$file = "images/".$_POST["ImageFile"];
	$title = $_POST["MenuTitle"];
	//Delete the image file itself
	if (!unlink($file)) {
  		echo ("<p class=\"errormsg\">Error deleting $file</p>");
  	} else   {
	echo "<div class=\"floatleft\"><i class=\"fa fa-check-square\"></i></div>";
    echo ("<div class=\"floatleft\"><p class=\"adminmsg\">Deleted $title successfully</p>");
	echo "</div><div style=\"clear: both;\"></div>";
   }
 } // end function deleteimage



	function displayForm( $errorMessages, $missingFields, $Gallery ) {

		echo "<h3>Add a new dish to the menu</h3>";

		if ( $errorMessages ) {
			foreach ( $errorMessages as $errorMessage ) {
				echo $errorMessage;
			}
		} else {
	?>
<p>Complete the fields below to add new dish to the menu. Fields marked with an asterisk (*) are required.</p>
<?php } ?>
<form action="admin-gallery.php" enctype="multipart/form-data" method="post" style="margin-bottom: 50px;">
	<div style="width: 30em;">
		<input name="action" type="hidden" value="new-gallery"><input name="added" type="hidden" value="1">
		<label for="MenuTitle"<?php validateField( "MenuTitle", $missingFields ) ?>>
	Dish Title: *</label>
	<input id="MenuTitle" name="MenuTitle" type="text" value="<?php echo $Gallery->getValueEncoded( "MenuTitle" ) ?>">


		<label for="ImageFile">Select Image File *</label>
			<input id="ImageFile" name="ImageFile" type="file" <?php validateField( "ImageFile", $missingFields ) ?>>

			<label for="ImageDescription"<?php validateField( "ImageDescription", $missingFields ) ?>>
		Dish Description *</label>
		<textarea rows="15" cols="50" name="ImageDescription" id="ImageDescription"><?php echo $Gallery->getValueEncoded( "ImageDescription" ) ?></textarea>

	<label for="MenuPrice"<?php validateField( "MenuPrice", $missingFields ) ?>>
Price: £</label>
<input id="MenuPrice" name="MenuPrice" type="text" value="<?php echo $Gallery->getValueEncoded( "MenuPrice" ) ?>">

<label for="MenuCategory"<?php validateField( "MenuCategory", $missingFields ) ?>>
Category: </label>
<select name="MenuCategory" id="MenuCategory">
	<option value="0">Ramen & Bowls</option>
	<option value="1">Sides & Small Plates</option>
</select>
<!--<input id="MenuCategory" name="MenuCategory" type="text" value="<?php echo $Gallery->getValueEncoded( "MenuPrice" ) ?>">-->

		<div style="clear: both;">
			<label for="submitButton"> </label>
			<input id="submitButton" name="submitButton" type="submit" value="Add Dish">
			<input id="resetButton" name="resetButton" style="margin-right: 20px;" type="reset" value="Reset Form">
		</div>
	</div>
</form>
<?php


	}

	function processForm() {
		$requiredFields = array( "MenuTitle", "MenuPrice", "ImageFile", "ImageDescription");
		$missingFields = array();
		$errorMessages = array();
		$Gallery = new Gallery( array(
			"ImageFile" => $_FILES["ImageFile"]["name"],
			"ImageDescription" => isset( $_POST["ImageDescription"] ) ? preg_replace( "/[^ \-\'\_a-zA-Z0-9!]/", "", $_POST["ImageDescription"] ) : "",
			"MenuTitle" => isset( $_POST["MenuTitle"] ) ? preg_replace( "/[^ \-\'\_a-zA-Z0-9.!]/", "", $_POST["MenuTitle"] ) : "",
			"MenuCategory" => isset( $_POST["MenuCategory"] ) ? preg_replace( "/[^ \-\'\_a-zA-Z0-9.!]/", "", $_POST["MenuCategory"] ) : "",
			"MenuPrice" => isset( $_POST["MenuPrice"] ) ? preg_replace( "/[^ \-\'\_a-zA-Z0-9.!]/", "", $_POST["MenuPrice"] ) : "",
		) );


		foreach ( $requiredFields as $requiredField ) {
			if ( !$Gallery->getValue( $requiredField ) ) {
				$missingFields[] = $requiredField;
			}
		}

		if ( $missingFields ) {
			$errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. Please complete the fields highlighted and resend the form.</p>';
		}

		if ( $errorMessages ) {
			displayForm( $errorMessages, $missingFields, $Gallery );
		} else {
			$uploadsuccess=uploadimage(0);
			//upload the image to the server and if successful add record to the gallery table
			if ($uploadsuccess==1) {
				$Gallery->insertgallery();
			} else {
			 	displayForm( array(), array(), new Gallery( array() ) );
			}
		}

	}


		function uploadimage() {

			$target_dir = "images/";
			$target_file = $target_dir . basename($_FILES["ImageFile"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $check = getimagesize($_FILES["ImageFile"]["tmp_name"]);
			    if($check !== false) {
			        echo "File is an image - " . $check["mime"] . ".";
			        $uploadOk = 1;
			    } else {
			        echo "File is not an image.";
			        $uploadOk = 0;
			    }
			}
			// Check if file already exists


			//if (file_exists($target_file)) {
				//echo "<div class=\"floatleft\"><i class=\"fa fa-times-circle\"></i></div>";
				//echo "<div class=\"floatleft\"><p class=\"errormsg\">That image already exists in the gallery.";
			  //  $uploadOk = 0;
			//}

			// Check file size
			if ($_FILES["ImageFile"]["size"] > 500000) {
				echo "<div class=\"floatleft\"><i class=\"fa fa-times-circle\"></i></div>";
				echo "<div class=\"floatleft\"><p class=\"errormsg\">The file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
				echo "<div class=\"floatleft\"><i class=\"fa fa-times-circle\"></i></div>";
				echo "<div class=\"floatleft\"><p class=\"errormsg\">Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "<br>Sorry, your file was not uploaded.</p>";
				echo "</div><div style=\"clear: both;\"></div>";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["ImageFile"]["tmp_name"], $target_file)) {
					echo "<div class=\"floatleft\"><i class=\"fa fa-check-square\"></i></div>";
					echo "<div class=\"floatleft\"><p class=\"adminmsg\">Dish successfully added to the menu.</p>";
					echo "</div><div style=\"clear: both;\"></div>";
			        displayForm( array(), array(), new Gallery( array() ) );
			    } else {
					echo "<div class=\"floatleft\"><i class=\"fa fa-times-circle\"></i></div>";
					echo "<div class=\"floatleft\"><p class=\"errormsg\">Sorry, there was an error uploading your file</p>";
					echo "</div><div style=\"clear: both;\"></div>";
			    }
			}
			return $uploadOk;
  } //end function upload image


	function displayGallery() {
			$start = isset( $_GET["start"] ) ? (int)$_GET["start"] : 0;
			$order = isset( $_GET["order"] ) ? preg_replace( "/[^ a-zA-Z]/", "", $_GET["order"] ) : "ImageID";
			list( $Gallery, $totalRows ) = Gallery::showGallery( $start, 200, $order );

			if ($totalRows>0) {
		?>

<h3>Displaying all images in the gallery</h3>
<p> Click 'Delete Image' to remove a photo from the gallery.</p>
  <div class="grid">

	<?php
			$rowCount = 0;

			foreach ( $Gallery as $Gallery ) {
				$rowCount++;
				?>

    <div class="cell"><form action="admin-gallery.php" method="post">
      <img src="images/<?php echo $Gallery->getValueEncoded( "ImageFile" ) ?>" alt="<?php echo $Gallery->getValueEncoded( "ImageDescription" ) ?>" class="responsive-image">
	  <input name="submit" type="submit" value="Delete Image">
	  <input name="delaction" type="hidden" value="delete">
	  <input name="ImageID" type="hidden" value="<?php echo $Gallery->getValueEncoded( "ImageID" ) ?>">
	  <input name="ImageFile" type="hidden" value="<?php echo $Gallery->getValueEncoded( "ImageFile" ) ?>">
    </form>
    </div>

	<?php } ?>
</div>
<p></p>
<?php
	} else {
	  	echo "No images in the gallery";
	}
	} //End function displayGallery


	function displayMenu() {
	$start = isset( $_GET["start"] ) ? (int)$_GET["start"] : 0;
	$order = isset( $_GET["order"] ) ? preg_replace( "/[^ a-zA-Z]/", "", $_GET["order"] ) : "ImageID";
	list( $Gallery, $totalRows ) = Gallery::showGallery( $start, 200, $order );


?>

<h2>Displaying all menu items: <?php echo $start + 1 ?> to <?php echo $totalRows ?></h2>
<p>Click 'Update Dish' button to make changes to an existing menu item or 'Delete Dish' to remove it from the website.</p>


<table style="border: 1px solid #666;">
<tr>
	<th><?php if ( $order != "ImageID" ) { ?><a href="admin-gallery.php?order=ImageID"><?php } ?>Menu ID<?php if ( $order != "MenuID" ) { ?></a><?php } ?></th>
	<th><?php if ( $order != "MenuTitle" ) { ?><a href="admin-gallery.php?order=MenuTitle"><?php } ?>Dish Title<?php if ( $order != "MenuTitle" ) { ?></a><?php } ?></th>
	<th><?php if ( $order != "MenuPrice" ) { ?><a href="admin-gallery.php?order=MenuPrice"><?php } ?>Price<?php if ( $order != "MenuPrice" ) { ?></a><?php } ?></th>
<th><?php if ( $order != "MenuCategory" ) { ?><a href="admin-gallery.php?order=MenuCategory"><?php } ?>Price<?php if ( $order != "MenuCategory" ) { ?></a><?php } ?></th>
	<th>Update</th>
	<th>Delete</th>
</tr>

<?php
$rowCount = 0;
foreach ( $Gallery as $Gallery ) {
	$rowCount++;

?>

<tr<?php if ( $rowCount % 2 == 0 ) echo ' class="alt"' ?>>
	<td><a href="update-menu.php?ImageID=<?php echo $Gallery->getValueEncoded( "ImageID" ) ?>"><?php echo $Gallery->getValueEncoded( "ImageID" ) ?></a></td>
	<td><a href="update-menu.php?ImageID=<?php echo $Gallery->getValueEncoded( "ImageID" ) ?>"><?php echo $Gallery->getValueEncoded( "MenuTitle" ) ?></a></td>
	<td><?php echo $Gallery->getValueEncoded( "MenuPrice" ) ?></td>
	<td><?php echo $Gallery->getValueEncoded( "MenuCategory" ) ?></td>
	<td><button onclick="window.location.href='update-menu.php?ImageID=<?php echo $Gallery->getValueEncoded( "ImageID" ) ?>'">Update Dish</button></td>
	<td><form action="admin-gallery.php" method="post"><input name="submit" type="submit" value="Delete Dish"><input name="delaction" type="hidden" value="delete"><input name="ImageID" type="hidden" value="<?php echo $Gallery->getValueEncoded( "ImageID" ) ?>">
		<input name="MenuTitle" type="hidden" value="<?php echo $Gallery->getValueEncoded( "MenuTitle" ) ?>">
		<input name="ImageFile" type="hidden" value="<?php echo $Gallery->getValueEncoded( "ImageFile" ) ?>"></td></form>
</tr>

<?php

}

?>

</table>

<?php

} //End function displayMenu


	displayPageFooter();

?>
