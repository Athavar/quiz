<?php		//Definiere Variabeln und Klasse
//Schalter m für Mode
$maxFragen = 5;

class Frage{
	private $kategorie;
	private $frage;
	private $antworten;
	private $wert;
	private $loesung;
	
	
	function __construct($kategorie, $frage, $a1, $a2, $a3, $a4, $wert, $loesung) {
		$this->kategorie = $kategorie;
		$this->frage = $frage;
		$this->antworten = array($a1,$a2,$a3,$a4);
		$this->wert = $wert;
		$this->loesung = $loesung - 1;
	}
	function getKategorie(){
		return $this->kategorie;
	}
	function getFrage(){
		return $this->frage;
	}
	function getAntwort($index){
		return $this->antworten[$index];
	}
	function getLösung(){
		return $this->loesung;
	}
	
}
//Speichere Fragen in Array(Fragen/Antworten-Pool)
$quizDB = array();
require('fragen.php');
require('fragen2.php');
require('fragen3.php');
 ?>
<!doctype html>
<html>
<head lang="de">
<title>Quiz</title>
<link rel="stylesheet" type="text/css" href="styles.css" >
</head>
<body>
<div id="wrapper">
<header><h1>Das Random Quiz</h1></header>
<br>
<div id="quiz">
<?php 
session_start();	//Starte Session
if(!isset($_SESSION['frageAmount'])){		//Setzte Standartwerte in Session
	$_SESSION['frageAmount'] = 0;
}
if(!isset($_SESSION['frageRichtig'])){
	$_SESSION['frageRichtig'] = 0;
}

				
				

if(isset($_GET['m'])) {		//m Mode
	switch ($_GET['m']){
		case 'frage':	//Frage wird gestellt
			
			$_SESSION['frageID'] = rand(0,count($quizDB) - 1);
			echo '<h3 class="frage">'.$quizDB[$_SESSION['frageID']]->getKategorie().'</h3><br>';
			echo '<div class="frage">Frage ' . ($_SESSION['frageAmount'] + 1) . ':<br><div><span> '.$quizDB[$_SESSION['frageID']]->getFrage().'</span></div></div>';
			echo '<form  action="?" method="get"><input name="m" value="antwort" hidden>'.
			 '<div class="antworten">'.
			 '<div><input type="radio" name="a" value="0" id="antwort0" checked>'.
			 '<label for="antwort0">'.$quizDB[$_SESSION['frageID']]->getAntwort(0) .'</label></div>'.
			 '<div><input type="radio" name="a" value="1" id="antwort1">'.
			 '<label for="antwort1">'.$quizDB[$_SESSION['frageID']]->getAntwort(1) .'</label></div>'.
			 '<div><input type="radio" name="a" value="2" id="antwort2">'.
			 '<label for="antwort2">'.$quizDB[$_SESSION['frageID']]->getAntwort(2) .'</label></div>'.
			 '<div><input type="radio" name="a" value="3" id="antwort3">'.
			 '<label for="antwort3">'.$quizDB[$_SESSION['frageID']]->getAntwort(3) .'</label></div></div>';
			 echo '<input type="submit" value="Antworten"><input type="button" value="Andere Frage" href="?m=frage"></form>';
			
			break;
		case 'antwort':		//Antwort wird abgeliefert
			if(isset($_GET['a']) && is_numeric($_GET['a'])){
				if(isset($_SESSION['frageID'])){
					$_SESSION['frageAmount'] += 1;
					if($_GET['a'] == $quizDB[$_SESSION['frageID']]->getLösung()){
						//richtige Lösung
						echo "<p>Richtig</p>";
						$_SESSION['frageRichtig'] += 1;
						
					}else{
						echo "<p>Falsch</p>";
						//falsche Lösung
					}
					
					if($_SESSION['frageAmount'] >= $maxFragen){
						echo '<a href="?m=ergebnis">Ergebnis</a>';
					}else{
						echo '<a href="?m=frage">Nächste Frage</a>';
					}
				
				
				
					
					unset($_SESSION['frageID']);
				} else {
					echo 'Es gibt eine Antwort, aber keine Frage';
				}
			}else{
				if(isset($_SESSION['frageID'])){
					echo 'Es gibt eine Frage, aber keine Antwort';
				} else {
					echo 'Es gibt kein Frage und  Antwort';
				}
			}
			break;
		case 'ergebnis':	//Ergebnis wird angezeigt
			if($_SESSION['frageAmount'] >= $maxFragen){ //Alle Fragen beantwortet?
				echo '<h3>Ergebnis</h3><p>Sie haben ' . (($_SESSION['frageRichtig'] / $_SESSION['frageAmount']) * 100) . '% aller Fragen richtig</p>';
				$_SESSION['frageAmount'] = 0;				//zurücksetzen der Session
				$_SESSION['frageRichtig'] = 0;  
				echo '<a href="?m=frage">Neustart</a>';
			} else { //Nicht alle Fragen beantwortet
				echo '<p>Du hast erst '.$_SESSION['frageAmount'].' von '.$maxFragen.' Fragen beantwortet</p>';
				echo '<a href="?m=frage">Zurück zu den Fragen</a>';
			}
			break;
	}
		
} else { //Mode nicht gesetzt
	?>
	
	<a href="?m=frage">Zum den Quiz</a>
	
	
	<?php
}

?>
<div>
</div>
</body>
</html>