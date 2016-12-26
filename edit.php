<?php
	//edit.php
	require("functions.php");
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		updatework(cleanInput($_POST["id"]), cleanInput($_POST["event_name"]), cleanInput($_POST["place"]), cleanInput($_POST["description"]), cleanInput($_POST["date"]), cleanInput($_POST["time"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
if(isset($_GET["delete"])){
		
		delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	//kui ei ole id-d aadressireal siis suunan data lehele
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	//saadan kaasa id
	$m = getSinglework($_GET["id"]);
	//var_dump($m);

?>
<br><br>
<a href="data.php"> Tagasi </a>
	<h1>Muuda jooksu andmed:</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >	
<h3>Ürituse nimetus</h3>
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > <br>

		
		<input id="event_name" name="event_name" placeholder="Ürituse nimi" type="text" value="<?php echo $m->event_name;?>"> <br>
		
<h3>Ürituse asukoht</h3>

	<input id="place" name="place" placeholder="Asukoht" type="text" value="<?php echo $m->place;?>"> <br><br>
	
<h3>Ürituse kirjeldus</h3>

	<input id="description" name="description" placeholder="Kirjeldus" type="text" value="<?php echo $m->description;?>"> <br><br>

<h3>Ürituse toimimse kuupäev</h3>
	
	<input id="date" name="date" placeholder="Kuupäev" type="date" value="<?php echo $m->date;?>"> <br><br>

<h3>Ürituse toimumise kellaaeg</h3>

	<input id="time" name="time" placeholder="Kellaaeg" type="time" value="<?php echo $m->time;?>"> <br><br>

<input type="submit" name="update" value="Sisesta">
<br>
<br>


<a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>

</form>