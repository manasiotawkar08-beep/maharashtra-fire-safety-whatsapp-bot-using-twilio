<?php
// api/ollama.php
// Ollama AI integration

function askOllama($question) {
    $url = OLLAMA_URL;
    
    $system = "You are a Maharashtra Fire Safety expert. Answer in 2-4 short sentences. Be direct. Include emergency numbers 101/108 when relevant.";
    
    $data = [
        'model' => OLLAMA_MODEL,
        'prompt' => "$system\n\nUser: $question\nAssistant:",
        'stream' => false,
        'options' => ['temperature' => 0.3, 'num_predict' => 150]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $result = json_decode($response, true);
        return trim($result['response'] ?? '');
    }
    return null;
}
?>
