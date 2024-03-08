<?php

$filePath = '../public/Front/commandes.pdf';
$uploadUrl = 'https://tmpfiles.org/api/v1/upload';

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $uploadUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Build cURL file array
$fileData = [
    'file' => new CURLFile($filePath)
];

// Set cURL POST data
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

// Execute cURL session
$response = curl_exec($ch);

// Get HTTP status code
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for successful upload
if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    $uploadedFileUrl = $responseData['data']['url'];

    // Écrivez le lien dans un fichier texte
    $file = fopen('../public/Front/link.txt', 'w');
    fwrite($file, $uploadedFileUrl);
    fclose($file);

    // Réponse JSON indiquant le succès
    $jsonResponse = json_encode(['status' => 'success', 'message' => 'Link saved to file.'], JSON_UNESCAPED_SLASHES);

    header('Content-Type: application/json');
    //echo $jsonResponse;
} else {
    // Réponse JSON indiquant une erreur
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Error uploading file. HTTP status code: ' . $httpCode]);
}

// Fermez la session cURL
curl_close($ch);
?>
