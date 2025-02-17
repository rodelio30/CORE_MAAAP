<?php
$directory = "../assets/models/";
$models = [];

// Get all .glb files in the directory
foreach (glob($directory . "*.glb") as $file) {
    $models[] = [
        "name" => basename($file, ".glb"), // Extract file name without extension
        "url" => $file
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($models);
?>