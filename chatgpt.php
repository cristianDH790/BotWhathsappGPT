<?php
$apiKey = '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';
$mensaje='QUE ES UN POLLO?';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer '.$apiKey.'',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n     \"model\": \"gpt-3.5-turbo\",\n     \"messages\": [{\"role\": \"user\", \"content\": \".$mensaje.\"}],\n     \"temperature\": 0.7\n   }");

$response = curl_exec($ch);

curl_close($ch);
    //var_dump($response);
    $responseArr = json_decode($response, true);
    //echo $responseArr['choices'][0]['message']['content'];
?>
<br>
<?php 
echo $responseArr['choices'][0]['message']['content'];
?>
