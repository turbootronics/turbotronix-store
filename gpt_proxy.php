<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "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";

$input = json_decode(file_get_contents("php://input"), true);
$question = trim($input["message"] ?? '');

if (!$question) {
    echo json_encode(["error" => "No input provided"]);
    exit;
}

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "user", "content" => $question]
    ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["error" => "Curl error: " . curl_error($ch)]);
    exit;
}
curl_close($ch);

echo $response;
