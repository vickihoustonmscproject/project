<?php

	require_once( "config.php" );
	require_once( "Tour.class.php" );
	require_once( "Gallery.class.php" );

	function displayPageHeader( $pageTitle ) {

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<title><?php echo $pageTitle?></title>
<link rel="stylesheet" href="jackaltrades.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
/* Green tick for admin messages*/
.fa-check-square {
	font-size:48px;
	color:green;
}

/* Red cross for error messages testing testing*/
.fa-times-circle {
	font-size:48px;
	color:red;
}
</style>
</head>
<body>

<div class="container"><div class="inner-container">
<header><h1><?php echo $pageTitle?></h1></header>

<?php

	}

	function displayPageFooter() {
?>

</div> <!-- close div inner container-->
<footer><ul class="topnav">
  <li class="right"><a href="default.php">&copy; Ramen Dayo 2019</a></li>  
</ul></footer>
</div> <!-- close div container-->
</body>

</html>

<?php

	}

	function validateField( $fieldName, $missingFields ) {
		if ( in_array( $fieldName, $missingFields ) ) {
			echo ' class="error"';
		}
	}

	function setChecked( DataObject $obj, $fieldName, $fieldValue ) {
		if ( $obj->getValue( $fieldName ) == $fieldValue ) {
			echo ' checked="checked"';
		}
	}

	function setSelected( DataObject $obj, $fieldName, $fieldValue ) {
		if ( $obj->getValue( $fieldName ) == $fieldValue ) {
			echo ' selected="selected"';
		}
	}

	  function test_input($data) {
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  	  return $data;
	  }

	function checkLogin() {
		session_start();
		if ( !$_SESSION["member"] or !$_SESSION["member"] = Member::getMember( $_SESSION["member"]->getValue( "id" ) ) ) {
			$_SESSION["member"] = "";
			header( "Location: login.php" );
			exit;
		} else {
			$logEntry = new LogEntry( array (
				"memberId" => $_SESSION["member"]->getValue( "id" ),
				"pageUrl" => basename( $_SERVER["PHP_SELF"] )
			) );
			$logEntry->record();
		}
	}

?>
