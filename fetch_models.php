<?php
// $directory = "assets/models/";
// $models = [];

// // Get all .glb files in the directory
// foreach (glob($directory . "*.glb") as $file) {
//     $models[] = [
//         "name" => basename($file, ".glb"), // Extract file name without extension
//         "url" => $file
//     ];
// }

// GitHub Raw URL for the models directory
$github_base_url = "https://raw.githubusercontent.com/rodelio30/CORE_MAAAP/main/assets/models/";

// List of models manually (GitHub does not allow directory listing)
$models = [
    ["name" => "CORE_Campus", "url" => $github_base_url . "CORE_Campus.glb"],
    // Add more models if needed
];


// Return JSON response
header('Content-Type: application/json');
echo json_encode($models);
?>