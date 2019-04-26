<?php
session_start();

$adminusername="admin";
$adminpassword="123456";

	require_once( "common.inc.php" );
	require_once( "config.php" );
	require_once( "Pages.class.php" );

	displayPageHeader( "Website Admin: Login" );		

	//check if user submitted the login form and check if details are correct	
	if (isset($_POST['username']) and isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
	if ($username == $adminusername && $password == $adminpassword){
	//if correct set their session username
		$_SESSION['username'] = $username;
	}	else	{
			echo "<div class=\"floatleft\"><i class=\"fa fa-times-circle\"></i></div>";
			echo "<div class=\"floatleft\"><p class=\"errormsg\">Sorry your username or password was incorrect</p>";
			echo "</div><div style=\"clear: both;\"></div>";				
	}
	}

	//if user is logged in send them to the admin homepage
	if (isset($_SESSION['username'])){
		$adminusername = $_SESSION['username'];
		header('Location: default.php'); 
 
}else{		
	//Display the login form
?>
		<h3>Login to the admin website</h3>
		
<form action="login.php" method="post" style="margin-bottom: 50px;">
	<div style="width: 30em;">
		<label for="username">Username *</label>
		<input id="username" name="username" type="text" value=""><br>
		<label for="password">Password *</label>
		<input id="password" name="password" type="text" value="">
		<label for="submitButton"> </label>		
		<input id="submitButton" name="submitButton" type="submit" value="Login">
		<input id="resetButton" name="resetButton" style="margin-right: 20px;" type="reset" value="Reset Form">
	</div>
</form>
<p class="boldtext">Username="admin" Password="123456"</p>
<?php		
}

	displayPageFooter();





?>