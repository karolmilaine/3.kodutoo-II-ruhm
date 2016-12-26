<?php

require("../../config.php");
require("functions.php");

$event_name = "";
$place = "";
$description = "";
$date = "";
$time = "";
$event_nameError = "";
$placeError = "";
$descriptionError = "";
$dateError = "";
$timeError = "";

//$searching = "r";
//kui ei ole kasutaja id'd
if (!isset($_SESSION["userId"])){
	//suunan sisselogimise lehele
	header("Location: login.php");	
	exit();
}

//kui on ?logout aadressireal siis login välja
if (isset($_GET["logout"])) {
	session_destroy();
	header("Location: login.php");
	exit();
}

if(!isset($_POST["event_name"])){
	//if(empty( $_POST["event_name"] ) ){
		$event_nameError = "See väli on kohustuslik";
	}else{
		$event_name = $_POST["event_name"];
		//}
} 

if(isset($_POST["place"])){
	if(empty($_POST["place"])){
		$placeError = "See väli on kohustuslik";
	} else {
		$_POST["place"] = cleanInput($_POST["place"]);
		$place = $_POST["place"];
	}
}

if(isset($_POST["description"])){
	if(empty($_POST["description"])){
		$descriptionError = "See väli on kohustuslik";
	} else {
		$_POST["description"] = cleanInput($_POST["description"]);
		$description = $_POST["description"];
	}
}

if(isset($_POST["date"])){
	if(empty($_POST["date"])){
		$dateError = "See väli on kohustuslik";
	} else {
		$_POST["date"] = cleanInput($_POST["date"]);
		$date = $_POST["date"];
	}
}

if(isset($_POST["time"])){
	if(empty($_POST["time"])){
		$timeError = "See väli on kohustuslik";
	} else {
		$_POST["time"] = cleanInput($_POST["time"]);
		$time = $_POST["time"];
	}
}

if (isset($_POST["event_name"]) && isset($_POST["place"]) && isset($_POST["description"]) && isset($_POST["date"])&& isset($_POST["time"]) &&
!empty($_POST["event_name"]) && !empty($_POST["place"]) && !empty($_POST["description"]) && !empty($_POST["date"]) && !empty($_POST["time"]))
	{
		work($_SESSION["userName"], $event_name, $place, $description, $date, $time);
	}



	// sorteerib
if(isset($_GET["sort"]) && isset($_GET["direction"])){
	$sort = $_GET["sort"];
	$direction = $_GET["direction"];
}else{
	// kui ei ole määratud siis vaikimis id ja ASC
	$sort = "id";
	$direction = "ascending";
}


//kas kasutaja otsib
if(isset($_GET["searching"])){
	$searching = cleanInput($_GET["searching"]);
		$workData = getwork($searching, $sort, $direction);
	} else {
		$searching = "";
		$workData = getwork($searching, $sort, $direction);
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">

	<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
</head>

<p>Tere tulemast <?=$_SESSION["firstName"];?> <?=$_SESSION["lastName"];?>!</p>
<p>Kasutajanimi: <a href="user.php"><?=$_SESSION["userName"];?></a></p>
<p>E-mail: <?=$_SESSION["userEmail"];?></p>
<p>Sugu: <?=$_SESSION["gender"];?></p>
<a class="btn btn-success" href="?logout=1">Logi välja</a>  <br> <br>

	<h1>Sisesta vabatahtlik töö:</h1>


<form method="POST">

<h3>Sisesta ürituse nimi</h3>
	<input name="event_name" placeholder="Ürituse nimi" type="text" value="<?=$event_name;?>"> <?=$event_nameError; ?> <br><br>

<h3>Ürituse asukoht</h3>

	<input name="place" placeholder="Asukoht" type="text" value="<?=$place;?>"> <?=$placeError; ?> <br><br>
	
<h3>Ürituse kirjeldus</h3>
	
	<input name="description" placeholder="Kirjeldus" type="text" value="<?=$description;?>" > <?=$descriptionError; ?> <br><br>

<h3>Ürituse toimumise kuupäev</h3>
	
	<input name="date" placeholder="Kuupäev" type="date" value="<?=$date;?>" > <?=$dateError; ?> <br><br>

<h3>Ürituse toimimise kellaaeg</h3>

	<input name="time" placeholder="Kellaaeg" type="time" value="<?=$time;?>" > <?=$timeError; ?> <br><br>

<input type="submit" class="btn btn-success" value="Sisesta">

</form>

<br><br>
<form>
	<input type="search" name="searching" value="<?=$searching;?>">
	<input type="submit" class="btn btn-success" value="Otsi">
</form>

<br><br>

<?php
	$direction = "ascending";
	if (isset($_GET["direction"])){
		if ($_GET["direction"] == "ascending"){
			$direction = "descending";
		}
	}

$html = "<table class='table table-striped table-bordered'>";

$html .= "<tr>";
$html .= "<th>
						<a href='?searching=".$searching."&sort=id&direction=".$direction."'>
							id
						</a>
					</th>";
$html .= "<th>
						<a href='?searching=".$searching."&sort=event_name&direction=".$direction."'>
							Üritus
						</a>
					</th>";
$html .= "<th>
						<a href='?searching=".$searching."&sort=place&direction=".$direction."'>
							Asukoht
						</a>
					</th>";
$html .= "<th>
						<a href='?searching=".$searching."&sort=description&direction=".$direction."'>
							Kirjeldus
						</a>
					</th>";
$html .= "<th>
						<a href='?searching=".$searching."&sort=date&direction=".$direction."'>
							Toimumise kuupäeev
						</a>
					</th>";
$html .= "<th>
						<a href='?searching=".$searching."&sort=time&direction=".$direction."'>
							Toimumise kellaaeg
						</a>
					</th>";



	$html .="</tr>";
	
	foreach($workData as $m) {
	
	$html .="<tr>";
		$html .= "<td>".$m->id."</td>";
		$html .= "<td>".$m->event_name."</td>";
		$html .= "<td>".$m->place."</td>";
		$html .= "<td>".$m->description."</td>";
		$html .= "<td>".$m->date."</td>";
		$html .= "<td>".$m->time."</td>";
		$html .= "<td><a class=\"btn btn-success\" href='edit.php?id=".$m->id."'>Muuda</a></td>";
	$html .="</tr>";
	
	}
$html .="</table>";
echo $html;

?>

</html>
