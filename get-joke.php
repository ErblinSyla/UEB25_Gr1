<?php
header('Content-Type: application/json');

$jokeApiUrl = "https://v2.jokeapi.dev/joke/Programming?type=single";
$response = @file_get_contents($jokeApiUrl);
if ($response !== false) {
    $data = json_decode($response, true);
    if (isset($data['joke'])) {
        echo json_encode(['joke' => $data['joke']]);
    } else {
        echo json_encode(['joke' => "Can't fetch a joke right now. Try again later!"]);
    }
} else {
    echo json_encode(['joke' => "Can't fetch a joke right now. Try again later!"]);
}
