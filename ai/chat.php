<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($input['message'] ?? '');

if ($userMessage === '') {
    echo json_encode(["reply" => "Please ask a question."]);
    exit;
}

$siteContent = include __DIR__ . '/site-content.php';

// Combine content
$context = implode("\n- ", $siteContent);

// OpenAI request
$payload = [
    "model" => "gpt-4.1-mini",
    "temperature" => 0,
    "messages" => [
        [
            "role" => "system",
            "content" =>
                "You are BONG AI, a website assistant.
Answer ONLY using the website content provided.
If the answer is not in the content, say:
'I can’t find that information on this website.'"
        ],
        [
            "role" => "system",
            "content" => "Website content:\n- " . $context
        ],
        [
            "role" => "user",
            "content" => $userMessage
        ]
    ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk-XXXXXXXXXXXXXXXX",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$reply = $data['choices'][0]['message']['content'] ?? "Sorry, I can’t answer that.";

echo json_encode(["reply" => $reply]);
