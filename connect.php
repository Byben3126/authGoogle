<?php
require("config.php");
require("functions.php");

if (isset($_GET["error"])) {
    header('Location: index.php?errorLogin='.urlencode($_GET["error"]));
}elseif(isset($_GET["code"])){

    $apiUrl = "https://oauth2.googleapis.com/token";

    $postData = [
        'code' => $_GET["code"],
        'client_id' => GOOGLE_ID_CLIENT,
        'client_secret' => GOOGLE_KEY_PRIVATE,
        'redirect_uri' => 'http://localhost:80/localhost/phpauthgoogle/connect.php',
        'grant_type' => 'authorization_code'
    ];

    // Initialisez la session cURL
    $ch = curl_init();
    if ($ch && false) {
        // Configuration des options de cURL
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        // Définir l'en-tête Content-Type
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        // Encodage des données au format application/x-www-form-urlencoded
        $postDataEncoded = http_build_query($postData);
        // Définir le corps de la requête avec les données encodées
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataEncoded);

         // Exécutez la requête cURL
        $response = (array) json_decode(curl_exec($ch));
        curl_close($ch);
    }else{
        // Gestion de l'alternative si cURL n'est pas disponible
        
        $response = file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($postData),
            ],
        ]));
        
        if ($response) {
            $response = (array) json_decode($response);
        }
        
    }
   
    if (isset($response) && isset($response['access_token'])) {
        
        $token = $response['access_token'];
        if(googleAhtentificator::auth($token)) {
            header('Location: index.php');
            exit;
        }
    }
   
}
header('Location: index.php?errorLogin='. urlencode("Probleme lors de l'authentification"));
exit;
?>