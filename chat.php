<?php
header('Content-Type: application/json');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['message'])) {
    echo json_encode(["status" => "error", "message" => "Pesan tidak boleh kosong."]);
    exit;
}

$userMessage = $_POST['message'];

// 1. Susun URL Endpoint Gemini (API Key diletakkan di URL)
$url = API_URL . AI_MODEL . ":generateContent?key=" . API_KEY;

// 2. Format Payload Data Khusus untuk Gemini
$data = [
    "systemInstruction" => [
        "parts" => [
            ["text" => SYSTEM_PROMPT]
        ]
    ],
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $userMessage]
            ]
        ]
    ],
    "generationConfig" => [
        "temperature" => AI_TEMPERATURE,
        "maxOutputTokens" => AI_MAX_TOKENS
    ]
];

// 3. Eksekusi cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

// Abaikan SSL untuk localhost
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch); 
curl_close($ch);

// 4. Parsing Balasan dari Gemini
if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    
    // Mengecek struktur JSON balasan Gemini
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        $botReply = $responseData['candidates'][0]['content']['parts'][0]['text'];
        echo json_encode(["status" => "success", "reply" => $botReply]);
    } else {
        echo json_encode(["status" => "error", "message" => "Format balasan Gemini tidak dikenali.", "raw" => $response]);
    }
} else {
    $errorMsg = "Gagal terhubung ke API Gemini.<br>• HTTP Code: <b>$httpCode</b><br>";
    if ($curlError) $errorMsg .= "• cURL Error: <b>$curlError</b><br>";
    $errorMsg .= "• Detail: " . htmlspecialchars($response);
    echo json_encode(["status" => "error", "message" => $errorMsg]);
}
?>