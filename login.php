<?php

require("../../config.php");
require("functions.php");

if (isset($_SESSION["userId"])){
	//suunan sisselogimise lehele
	header("Location: data.php");
	exit();
}


$signupEmailError = "";
$signupPasswordError = "";
$signupPasswordError2 = "";
$signupUsernameError = "";
$signupEmail = "";
$signupGender = "";
$signupFirstName= "";
$signupFirstNameError = "";
$signupUsername = "";
$signupLastName = "";
$signupLastNameError = "";

$loginEmail2 = "";
$loginEmail3 = "";
$loginEmailError = "";
$loginPasswordError = "";
//kas on üldse olemas selline muutuja
if(isset($_POST["signupEmail"])){
	//jah on olemas
	//kas on tühi
	if(empty($_POST["signupEmail"])){
		$signupEmailError = "See väli on kohustuslik";	
	} else {
		//email on olemas
		$_POST["signupEmail"] = cleanInput($_POST["signupEmail"]);
		$signupEmail = $_POST["signupEmail"];
	}
}
if(isset($_POST["signupUsername"])) {
	if(empty($_POST["signupUsername"])){
		$signupUsernameError = "Igal kasutajal peab olema kasutajanimi";
	} else {
		$_POST["signupUsername"] = cleanInput($_POST["signupUsername"]);
		$signupUsername = $_POST["signupUsername"];
		}
}
if(isset($_POST["signupPassword"])) {
	if(empty($_POST["signupPassword"])){
		$signupPasswordError = "Parool peab olema";
	} else {
		//Siia jõuan siis kui parool on olemas ja kui parool ei ole tühi
		//kas parooli pikkus on väiksem kui kaheksa
			if (strlen($_POST["signupPassword"]) < 8) {
			$signupPasswordError = "Parool peab olema vähemalt 8 tähemärki pikk";
			}
		}
}
if(isset($_POST["signupPassword2"])) {	
	if(empty($_POST["signupPassword2"])){
		$signupPasswordError2 = "Parool peab olema";
	} else {
		//Siia jõuan siis kui parool on olemas ja kui parool ei ole tühi
		//kas parooli pikkus on väiksem kui kaheksa
		if (strlen($_POST["signupPassword2"]) < 8) {
			$signupPasswordError2 = "Parool peab olema vähemalt 8 tähemärki pikk";
		}else {
			//Kontrollin, kas paroolid ühtivad
			if ($_POST["signupPassword2"] != $_POST["signupPassword"]){
			$signupPasswordError2 = "Paroolid ei ühti";
					} 
			}
		}
	}
if(isset($_POST["signupFirstName"])) {
	if(empty($_POST["signupFirstName"])){
		$signupFirstNameError = "Eesnimi sisestamine on kohustuslik";
	} else {
		$_POST["signupFirstName"] = cleanInput($_POST["signupFirstName"]);
		$signupFirstName = $_POST["signupFirstName"];
	}
}
if(isset($_POST["signupLastName"])) {
	if(empty($_POST["signupLastName"])){
		$signupLastNameError = "Perekonnanimi sisestamine on kohustuslik";
	} else {
		$_POST["signupLastName"] = cleanInput($_POST["signupLastName"]);
		$signupLastName = $_POST["signupLastName"];
	}
}
if( isset( $_POST["signupGender"] ) ){
	if(!empty( $_POST["signupGender"] ) ){
		$signupGender = $_POST["signupGender"];
	}		
} 



//peab olema email ja parool ja ühtegi errorit 

if ( isset($_POST["signupEmail"]) && 
	 isset($_POST["signupPassword"]) &&
	 isset($_POST["signupPassword2"]) &&
 	 ($_POST["signupPassword2"] == $_POST["signupPassword"]) &&
	 $signupEmailError == "" && 
	 empty($signupPasswordError)) 
{
	$password = hash("sha512", $_POST["signupPassword"]);
	
signUp($signupUsername, $password, $signupEmail, $signupFirstName, $signupLastName, $signupGender);
}
$notice = "";
if(isset($_POST["loginEmail"])){
	//jah on olemas
	//kas on tühi
	if(!empty($_POST["loginEmail"])){
		$_POST["loginEmail"] = cleanInput($_POST["loginEmail"]);
		
if (isset($_POST["loginEmail"]) && isset($_POST["loginPassword"]) && 
	!empty($_POST["loginEmail"]) && !empty($_POST["loginPassword"]))
	{
//ei pea olema sama nimi mis function.php-s. Seal on $error
	
	$notice = login($_POST["loginEmail"], $_POST["loginPassword"]);
	$loginEmail2 = $_POST["loginEmail"];
	
} else {
	$loginEmailError = "Sisselogimiseks peab sisestama e-maili";
	$loginPasswordError = "Sisselogimiseks peab sisetama parooli";
}
	}
}

?>

<!DOCTYPE html>
<html>
<head>

	<title>Logi sisse või loo kasutaja</title>
</head>
<body>

	<h1>Logi sisse</h1>
	<form method="POST">
		<p style="color:red;"><?=$notice;?></p>
		<label>E-post</label> <br>
		<input name="loginEmail" type="text" value="<?=$loginEmail2;?>"> <?php echo $loginEmailError; ?> <br><br>
		<input name="loginPassword" placeholder="Parool" type="password"> <?php echo $loginPasswordError; ?> <br><br>
		<input type="submit" value="Logi sisse">
	
	</form>

	<h1>Loo kasutaja</h1>
	<form method="POST"> <br>
	
		<input name="signupUsername" placeholder="Kasutajanimi" type="text" value="<?=$signupUsername;?>"> <?=$signupUsernameError; ?> <br><br>
		<input name="signupPassword" placeholder="Parool" type="password"> <?php echo $signupPasswordError; ?> <br><br>
		<input name="signupPassword2" placeholder="Sisesta parool uuesti" type="password"> <?php echo $signupPasswordError2; ?> <br><br>
		<input name="signupEmail" placeholder="E-post" type="text" value="<?=$signupEmail;?>"> <?php echo $signupEmailError; ?> <br><br>
		<input name="signupFirstName" placeholder="Eesnimi" type="text" value="<?=$signupFirstName;?>"> <?php echo $signupFirstNameError; ?> <br><br>
		<input name="signupLastName" placeholder="Perekonnanimi" type="text" value="<?=$signupLastName;?>"> <?php echo $signupLastNameError; ?> <br><br>
		
		<?php if($signupGender == "male") { ?>
			<input name="signupGender" value="male" type="radio" checked> Mees <br>
		<?php }else { ?> <!--Tühikud peavad olema-->
			<input name="signupGender" value="male" type="radio"> Mees <br>
		<?php } ?>	
		
		
		<?php if($signupGender == "female") { ?>
			<input name="signupGender" value="female" type="radio" checked> Naine <br>
		<?php }else { ?> <!--Tühikud peavad olema-->
			<input name="signupGender" value="female" type="radio"> Naine <br>
		<?php } ?>
		
		
		<?php if($signupGender == "other") { ?>
			<input name="signupGender" value="other" type="radio" checked> Ei soovi avaldada <br><br>
		<?php }else { ?> <!--Tühikud peavad olema-->
			<input name="signupGender" value="other" type="radio"> Ei soovi avaldada <br><br>
		<?php } ?>
			
			
		<input type="submit" value="Loo kasutaja">
	
	</form>
	
</body>
</html>