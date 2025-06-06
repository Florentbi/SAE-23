#!/opt/lampp/bin/php
<?php
// Connexion à la base de données
$id_bd = mysqli_connect("localhost", "root", "passroot", "sae23")
    or die("Connexion au serveur et/ou à la base de données impossible");

// Forcer l'encodage UTF-8
mysqli_query($id_bd, "SET NAMES 'utf8'");

// Récupération de la date et de l'heure
$date = date("Y-m-d");
$horaire = date("H:i:s");

// Récupération de la valeur MQTT
$temp208 = shell_exec("mosquitto_sub -h iot.iut-blagnac.fr -u student -P student -t iut/bate/etage2/E208/temperature -C 1 | jq -r '.value'");
$temp102 = shell_exec("mosquitto_sub -h iot.iut-blagnac.fr -u student -P student -t iut/bate/etage1/E102/temperature -C 1 | jq -r '.value'");
$hum208 = shell_exec("mosquitto_sub -h iot.iut-blagnac.fr -u student -P student -t iut/bate/etage2/E208/humidite -C 1 | jq -r '.value'");
$hum102 = shell_exec("mosquitto_sub -h iot.iut-blagnac.fr -u student -P student -t iut/bate/etage1/E102/humidite -C 1 | jq -r '.value'");

// Requête d'insertion
$requeteTemp208 = "INSERT INTO Mesure (date, horaire, valeur, NOM_cpt) VALUES ('$date', '$horaire', '$temp208', 'Temp_208')";
$requeteHum208 = "INSERT INTO Mesure (date, horaire, valeur, NOM_cpt) VALUES ('$date', '$horaire', '$hum208', 'Hum_208')";
$requeteTemp102 = "INSERT INTO Mesure (date, horaire, valeur, NOM_cpt) VALUES ('$date', '$horaire', '$temp102', 'Temp_102')";
$requeteHum102 = "INSERT INTO Mesure (date, horaire, valeur, NOM_cpt) VALUES ('$date', '$horaire', '$hum102', 'Hum_102')";

// Exécution de la requête
mysqli_query($id_bd, $requeteTemp208) or die("Erreur lors de l'insertion des données");
mysqli_query($id_bd, $requeteHum208) or die("Erreur lors de l'insertion des données");
mysqli_query($id_bd, $requeteTemp102) or die("Erreur lors de l'insertion des données");
mysqli_query($id_bd, $requeteHum102) or die("Erreur lors de l'insertion des données");

// Fermeture de la connexion
mysqli_close($id_bd);
?>
