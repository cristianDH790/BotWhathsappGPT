<?php
//la api de meta wsp caduca cada 24 horas ojo

    const TOKEN_ANDERCODE = "CRISTIANCODEMETAAPI";
    const WEBHOOK_URL = "https://circulodeenfermeros.com/webhook.php";

    function verificarToken($req,$res){
        try{
            $token = $req['hub_verify_token'];
            $challenge = $req['hub_challenge'];
    
            if (isset($challenge) && isset($token) && $token === TOKEN_ANDERCODE) {
                $res->send($challenge);
            } else {
                $res->status(400)->send();
            }

        }catch(Exception $e){
            $res ->status(400)->send();
        }
    }

    function recibirMensajes($req, $res) {
        
        try {
            
            $entry = $req['entry'][0];
            $changes = $entry['changes'][0];
            $value = $changes['value'];
            $mensaje = $value['messages'][0];
            
            $comentario = $mensaje['text']['body'];
            $numero = $mensaje['from'];
            
            $id = $mensaje['id'];
            
            $archivo = "log.txt";
            
            if (!verificarTextoEnArchivo($id, $archivo)) {
                $archivo = fopen($archivo, "a");
                $texto = json_encode($id).",".$numero.",".$comentario;
                fwrite($archivo, $texto);
                fclose($archivo);
                
                EnviarMensajeWhastapp($comentario,$numero);
            }
    
            $res->header('Content-Type: application/json');
            $res->status(200)->send(json_encode(['message' => 'EVENT_RECEIVED']));

        } catch (Exception $e) {
            $res->header('Content-Type: application/json');
            $res->status(200)->send(json_encode(['message' => 'EVENT_RECEIVED']));
        }
    }
    
    function EnviarMensajeWhastapp($comentario,$numero){
        $comentario = strtolower($comentario);

        if (strpos($comentario,'hola') !==false){
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "Hola estimada o estimado te saluda *cristian* brindame tu  _'nombre completo'_ , para poder atenderte.  🤓"
                ]
            ]);
        }else if ($comentario=='1') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=> "¡Hola! Somos ' *AprendeOnline* '. Especializados en cursos interactivos y accesibles. Ofrecemos una amplia gama de clases virtuales sobre diversos temas. Tu acceso a conocimiento de calidad desde cualquier lugar. Descubre nuestro catálogo y comienza a aprender hoy mismo. \n 📌 https://youtu.be/hNFCxTbrmEs?si=VxkNUa2tJQPPCUCi"
                ]
            ]);
        }else if ($comentario=='2') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "location",
                "location"=> [
                    "latitude" => "-12.067158831865067",
                    "longitude" => "-77.03377940839486",
                    "name" => "Estadio Nacional del Peru",
                    "address" => "📍 Cercado de Lima 🗺️"
                ]
            ]);
        }else if ($comentario=='3') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "document",
                "document"=> [
                    "link" => "http://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf",
                    "caption" => "📔 Temario del Curso #001"
                ]
            ]);
        }else if ($comentario=='4') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "audio",
                "audio"=> [
                    "link" => "https://filesamples.com/samples/audio/mp3/sample1.mp3",
                ]
            ]);
        }else if ($comentario=='5') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "to" => $numero,
                "text" => array(
                    "preview_url" => true,
                    "body" => "⏯️Introduccion al curso! https://youtu.be/6ULOE2tGlBM"
                )
            ]);
        }else if ($comentario=='6') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "📳 En breve un asesor se comunicara contigo. \n \n 👁 Verefique el horario de atencion."
                )
            ]);
        }else if ($comentario=='7') {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "➡️ *HORARIO DE ATENCION DEL ASESOR* 👇\n \n 🔵 *Dias de Atencion:* _Lunes a Viernes. _\n 🔵 *Horario:* _9:00 a.m. a 6:00 p.m._ ⭐"
                )
            ]);
        }else if (strpos($comentario,'gracias') !== false) {
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "¡De nada! 😊 Si necesitas más información o tienes alguna otra pregunta, estoy aquí para ayudarte en lo que necesites. 🌟"
                )
            ]);
        }else if (strpos($comentario,'adios') !== false || strpos($comentario,'bye') !== false || strpos($comentario,'nos vemos') !== false || strpos($comentario,'adiУ┤Иs') !== false){
            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => "Hasta luego. 👋 👋 "
                )
            ]);
        }else if (strpos($comentario,'!cristian:')!== false){
            $texto_sin_gchatgpt = str_replace("!cristian: ", "", $comentario);

            $apiKey = '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';

            $data = [
                'model' => 'gpt-3.5-turbo',
                'messages' => [['role' => 'user', 'content' => $texto_sin_gchatgpt]],
                'temperature' => 0.7,
                'max_tokens' => 300,
                'n' => 1,
                'stop' => ['\n']
            ];

            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ));

            $response = curl_exec($ch);
            $responseArr = json_decode($response, true);

            $data = json_encode([
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $numero,
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => $responseArr['choices'][0]['message']['content']
                )
            ]);
        }else{
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $numero,
                "type" => "text",
                "text"=> [
                    "preview_url" => false,
                    "body"=>  " ✅ *MENU DE INTERACCION!🥳👇* \n\n *🔸 Ingresa un numero para recibir informacion.* \n \n 1️⃣ Informacion de la empresa. \n 2️⃣ Ubicacion del local. \n 3️⃣ Enviar temario en pdf. \n 4️⃣ Audio explicando curso. \n 5️⃣ Video de Introduccion. \n 6️⃣ Hablar con un asesor. \n 7️⃣ *_Horario de Atencion._*  \n \n  👁️ _Para interactuar con nuestro bot escribe '!cristian:' antes de cada pregunta_ ",
                ]
            ]);
        }

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/json\r\nAuthorization: Bearer EAANKwRZBP0eMBOZCNXewgjaAE4oLlcHzGRMF16Y2CwmOpzYHoY64OYw63NdcEtUWdkUYxjbYkA3ZBNFWoPs1TUlz3D3ZBOJfcqpyIUa970GRSEO6BaDhZCqNNPsKESlJYMELJlUdQZAS1WC4j0UG5LQcqZBA4pe9wh1ZAbZA9R8EyD1bBCqtQuzj8Dpot7Umni6Kj6W13aZB7BvHGE2i8y96wZD\r\n",
                'content' => $data,
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents('https://graph.facebook.com/v17.0/119743207723663/messages ', false, $context);

        if ($response === false) {
            echo "Error al enviar el mensaje\n";
        } else {
            echo "Mensaje enviado correctamente\n";
        }
    }
    
    function verificarTextoEnArchivo($texto, $archivo) {
        $contenido = file_get_contents($archivo);
        
        if (strpos($contenido, $texto) !== false) {
            return true; // El texto ya existe en el archivo
        } else {
            return false; // El texto no existe en el archivo
        }
    }
    

    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $input = file_get_contents('php://input');
        $data = json_decode($input,true);

        recibirMensajes($data,http_response_code());
        
    }else if($_SERVER['REQUEST_METHOD']==='GET'){
        if(isset($_GET['hub_mode']) && isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === TOKEN_ANDERCODE){
            echo $_GET['hub_challenge'];
        }else{
            http_response_code(403);
        }
    }
?>
