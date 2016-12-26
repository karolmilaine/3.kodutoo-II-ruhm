<?php

require_once("../../config.php");
//see vail peab olema kõigil lehtedel, kus tahan kasutada session muutujat

session_start();

//************
//***Signup***
//************


function signUp($signupUsername, $password, $signupEmail, $signupFirstName, $signupLastName, $signupGender) {
	//echo $serverUsername;
	//Ühendus
	$database = "if16_karojyrg_2";

		$mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		// mysqli rida
		$stmt = $mysqli->prepare("INSERT INTO project_user (username, password, email, firstname, lastname, gender) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		// stringina üks täht iga muutuja kohta (?), mis t??t
		// string - s
		// integer - i
		// float (double) - d
		// küsimärgid asendada muutujaga
		$stmt->bind_param("ssssss",$signupUsername, $password, $signupEmail, $signupFirstName, $signupLastName, $signupGender);
		
		//täida käu
		if($stmt->execute()) {
			echo "Salvestamine õnnestus";
			
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		//panen Ühenduse kinni
		$stmt->close();
		$mysqli->close();
	}


function login($loginEmail, $loginPassword) {
	
	$error = "";
	$password = $loginPassword;
	$email = $loginEmail;
	
	$database = "if16_karojyrg_2";
		$mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT id, username, password, email, firstname, lastname, gender FROM project_user WHERE email = ?");
		
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määrna väärtused muutujasse
		$stmt->bind_result($id, $usernameFromDB, $passwordFromDB,  $emailFromDB, $firstnameFromDB, $lastnameFromDB, $genderFromDB);
		$stmt->execute();
		
		//andmed tulid andmebaasist või mitte
		//on tõene kui on vähemalt üks vastus
		
		if($stmt->fetch()){
			//oli sellise meiliga kasutaja
			//password millega kasutaja tahab sisse logida
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDB) {
				echo "Kasutaja logis sisse ".$id;
				
			$_SESSION["userId"] = $id;
			$_SESSION["userEmail"] = $emailFromDB;
			$_SESSION["userName"] = $usernameFromDB;
			$_SESSION["firstName"] = $firstnameFromDB;
			$_SESSION["lastName"] = $lastnameFromDB;
			$_SESSION["gender"] = $genderFromDB;
			header("Location: data.php");
			exit();
			
			} else {
				$error = "Vale parool";
			}
			//määran sessiooni muutujad
			
			
			//header("Location: login.php");
			
		} else {
			//ei ole sellist kasutajat selle meiliga
			$error = "Ei ole sellist e-maili";
		}
	
		return $error;
	}
	
function cleanInput($input) {
	// " tere tulemast " <--
	$input = trim($input);
	// "tere tulemast" <-- peale eelmist rida
	
	// " tere \\tulemast " <--
	$input = stripslashes($input);
	// "tere tulemast"

	// "<"
	$input = htmlspecialchars_decode($input);
	// "&lt"
	
	return $input;
}

	
function work($userName, $event_name, $place, $description, $date, $time){
	//echo $serverUsername;
	//Ühendus
	$database = "if16_karojyrg_2";

		$mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		// mysqli rida
		$stmt = $mysqli->prepare("INSERT INTO project_run (event_name, place, description, date, time) VALUES (?, ?, ?, ?, ?)");
		echo $mysqli->error;
		// stringina üks täht iga muutuja kohta (?), mis t??t
		// string - s
		// integer - i
		// float (double) - d
		// küsimärgid asendada muutujaga
		$stmt->bind_param("sssss", $event_name, $place, $description, $date, $time);
		
		//täida käu
		if($stmt->execute()) {
			echo "Salvestamine õnnestus";
			
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		//panen Ühenduse kinni
		$stmt->close();
		$mysqli->close();
	}
	
	//$searching
	

function getwork($searching, $sort, $direction){
	$database = "if16_karojyrg_2";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
	$allowedSortOptions = ["id", "name", "event_name"];
	
	if(!in_array($sort, $allowedSortOptions)){
			$sort = "id";
		}
		echo "Sorteerin: ".$sort." ";
	
	$orderBy= "ASC";
		if($direction == "descending"){
			$orderBy= "DESC";
		}
		echo "Järjekord: ".$orderBy." ";
	
	if($searching == "") {
		echo "Ei otsi";
	$stmt = $mysqli->prepare ("SELECT id, name, event_name, place, description, date, time FROM project_run WHERE deleted is NULL ORDER BY $sort $orderBy");
	}else{
		echo "Otsib";
		$searchword = "%".$searching."%";
		$stmt = $mysqli->prepare ("SELECT id, name, event_name, place, description, date, time FROM project_run WHERE deleted is NULL 
								   AND (name LIKE ? OR event_name LIKE ?) ORDER BY $sort $orderBy");
	//OR event_name LIKE ? OR place LIKE ? OR description LIKE ? OR date LIKE ? OR avg_pace LIKE ?
		$stmt->bind_param("ss", $searchword, $searchword);
		}
	$stmt->bind_result($id, $userName, $event_name, $place, $description, $date, $time);
	$stmt->execute();
	
	//tekitan massiivi
	$result = array();	
	
	
	//tee seda seni, kuni on rida andmeid, mis vastab select lausele
	while($stmt->fetch()) {
	//tekitan objekti
		$work = new StdClass();
		
		$work->id = $id;
		$work->userName = $userName;
		$work->event_name = $event_name;
		$work->place = $place;
		$work->description = $description;
		$work->date = $date;
		$work->time = $time;

		
		#echo $plate."<br>";
		//iga korda massiivi lisan juurde numbrimärgi
		array_push($result, $work);
	}
$stmt->close();
$mysqli->close();	
return $result;
}

	
	
 	
 function getSinglework($edit_id){
     
        $database = "if16_karojyrg_2";
 
 	//echo "id on ".$edit_id;
 		
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
 		
 $stmt = $mysqli->prepare("SELECT event_name, place, description, date, time FROM project_run WHERE id = ? ");
 		
		echo $mysqli->error;
		
		$stmt->bind_param("i", $edit_id);
 		$stmt->bind_result($event_name, $place, $description, $date, $time);
 		$stmt->execute();
 		
 		//tekitan objekti
 	$work = new Stdclass();
 		
 		//saime ühe rea andmeid
 		if($stmt->fetch()){
 		// saan siin alles kasutada bind_result muutujaid
 			
			$work->event_name = $event_name;
 			$work->place = $place;
			$work->description = $description;
			$work->date = $date;
			$work->time = $time;
 			
 			
 		}else{
 		// ei saanud rida andmeid kätte
 			// sellist id'd ei ole olemas
 			// see rida võib olla kustutatud
 			header("Location: data.php");
 			exit();
 		}
 		
 		$stmt->close();
 		$mysqli->close();
 		
 		return $work;
 		
 	}
 


 	function updatework($id, $event_name, $place, $description, $date, $time){
     	
         $database = "if16_karojyrg_2";
 
 		
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
 		echo $mysqli->error;
 		$stmt = $mysqli->prepare("UPDATE project_run SET event_name = ?, place = ?, description = ?, date = ?, time = ? WHERE id = ?");
    	$stmt->bind_param("sssssi", $event_name, $place, $description, $date, $time, $id);
 		echo $mysqli->error;
 		// kas õnnestus salvestada
 		if($stmt->execute()){
 			// õnnestus
 			echo "salvestus õnnestus!";
 		}
 		
 		$stmt->close();
 		$mysqli->close();
 		
 	}
 	
	function delete($id){
		
		$database = "if16_karojyrg_2";

		$mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE project_run SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	
	
?>