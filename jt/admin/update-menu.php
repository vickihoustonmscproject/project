<?php
	session_start();

	//check if user is logged in
	//if (!isset($_SESSION['username'])) {
	//	header('Location: login.php');
	//}

	require_once( "common.inc.php" );
	require_once( "config.php" );
	require_once( "Gallery.class.php" );


	$ImageID = isset( $_REQUEST["ImageID"] ) ? (int)$_REQUEST["ImageID"] : 0;


if ( !$Gallery = Gallery::getMenuItem( $ImageID ) ) {
	displayPageHeader( "Website Admin: Error" );
	echo "<div><p class=\"errormsg\">News item not found.</a></div>";
	echo "<p><a href=\"admin-news.php\">Return to news admin page</a></p>";
	displayPageFooter();
	exit;
}



if ( isset( $_POST["action"] ) and $_POST["action"] == "Save Changes" ) {
	saveNews();
} elseif ( isset( $_POST["action"] ) and $_POST["action"] == "Delete Dish" ) {
	deleteNews();
	//	echo "Delete News";
} else {
	displayForm( array(), array(), $Gallery );
	//echo "We are here";
}



function displayForm( $errorMessages, $missingFields, $Gallery ) {
	displayPageHeader( "Website Admin: Update News - " . $Gallery->getValueEncoded( "MenuTitle" ) );
	if ( $errorMessages ) {
		foreach ( $errorMessages as $errorMessage ) {
			echo $errorMessage;
		}
	}
	$start = isset( $_REQUEST["start"] ) ? (int)$_REQUEST["start"] : 0;
  $selectedcat = $Gallery->getValueEncoded( "MenuCategory");

?>

<form action="update-menu.php" method="post" style="margin-bottom: 50px;">
<div style="width: 30em;">
	<input type="hidden" name="ImageID" id="ImageID" value="<?php echo $Gallery->getValueEncoded( "ImageID" ) ?>">
	<input type="hidden" name="ImageFile" id="ImageFile" value="<?php echo $Gallery->getValueEncoded( "ImageFile" ) ?>">
	<input type="hidden" name="start" id="start" value="<?php echo $start ?>">
	<label for="MenuTitle"<?php validateField( "NewsTitle", $missingFields ) ?>>Dish Title *</label>
	<input type="text" name="MenuTitle" id="MenuTitle" value="<?php echo $Gallery->getValueEncoded( "MenuTitle" ) ?>">
	<label for="ImageDescription"<?php validateField( "ImageDescription", $missingFields ) ?>>Dish Description *</label>
	<textarea rows="15" cols="50" name="ImageDescription" id="ImageDescription"><?php echo $Gallery->getValueEncoded( "ImageDescription" ) ?></textarea>
	<label for="MenuPrice"<?php validateField( "MenuPrice", $missingFields ) ?>>Price </label>
	<input type="text" name="MenuPrice" id="MenuPrice" value="<?php echo $Gallery->getValueEncoded( "MenuPrice" ) ?>">
	<label for="MenuCategory"<?php validateField( "MenuCategory", $missingFields ) ?>>Menu Category </label>
	<input type="text" name="MenuCategory" id="MenuCategory" value="<?php echo (int)$Gallery->getValueEncoded( "MenuCategory" ) ?>">

	<label for="saveButton"> </label>
	<input type="submit" name="action" id="saveButton" value="Save Changes">
	<input type="submit" name="action" id="deleteButton" value="Delete Dish" style="margin-right: 20px;">
MENUCATEGORY IS<?php echo $Gallery->getValueEncoded( "MenuCategory" )  ?>
</div>
</form>

<div style="width: 30em; margin-top: 20px; text-align: center;">
	<a href="admin-gallery.php">Back to menu admin page</a>
</div>

<?php

} // End function displayForm
	displayPageFooter();

function saveNews() {

	$requiredFields = array( "MenuTitle", "ImageDescription", "MenuPrice" );
	$missingFields = array();
	$errorFields = array();
	$errorMessages = array();

	$Gallery = new Gallery( array(
		"ImageID" => isset( $_POST["ImageID"] ) ? (int) $_POST["ImageID"] : "",
		"MenuTitle" => isset( $_POST["MenuTitle"] ) ? preg_replace( "/[^ \-\'\_a-zA-Z0-9.!]/", "", $_POST["MenuTitle"] ) : "",
		"ImageDescription" => stripcslashes($_POST["ImageDescription"]) ,
		"MenuPrice" => isset( $_POST["MenuPrice"] ) ? preg_replace( "/[^ \'\-a-zA-Z0-9.:\/]/", "", $_POST["MenuPrice"] ) : ""
	) );

	foreach ( $requiredFields as $requiredField ) {
		if ( !$Gallery->getValue( $requiredField ) ) {
			$missingFields[] = $requiredField;
		}
	}

	//validate the url
		if (empty($_POST["MenuPrice"])) {
				$website = "";
		}

	if ( $missingFields ) {
		$errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. <br>Please complete the fields highlighted below and click Save Changes to resend the form.</p>';
	}


	if ( $errorMessages ) {
		displayForm( $errorMessages, $missingFields, $Gallery );
	} else {
		$Gallery->update();
		//Update the record in the database and then go back to admin homepage
		header('Location: admin-gallery.php?upd=1&MenuTitle='.$_POST["MenuTitle"].'&ImageID='.$_POST["ImageID"].'');

	}

} //end function saveNews

function deleteNews() {
	$Gallery = new Gallery( array(
		"ImageID" => isset( $_POST["ImageID"] ) ? (int) $_POST["ImageID"] : "",

	) );
	$Gallery->deletegallery();
	//echo "<h1>DELETE</h1>";
	header('Location: admin-gallery.php?del=1&MenuTitle='.$_POST["MenuTitle"].'');
} //end function deleteNews

	?>
