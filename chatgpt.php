<?php
//EAANKwRZBP0eMBOZCNXewgjaAE4oLlcHzGRMF16Y2CwmOpzYHoY64OYw63NdcEtUWdkUYxjbYkA3ZBNFWoPs1TUlz3D3ZBOJfcqpyIUa970GRSEO6BaDhZCqNNPsKESlJYMELJlUdQZAS1WC4j0UG5LQcqZBA4pe9wh1ZAbZA9R8EyD1bBCqtQuzj8Dpot7Umni6Kj6W13aZB7BvHGE2i8y96wZD
$apiKey = 'sk-lGVkvnboqGTJq2hVzMJ6T3BlbkFJAU9dQk5ZFSsTcC7aqweqwAvWlS';
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
