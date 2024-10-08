<?php



    require '../vendor/autoload.php';

    $deviceToken = $_POST['deviceToken'];
    $title  = $_POST['title'];
    $body  = $_POST['body'];

    use Google\Auth\Credentials\ServiceAccountCredentials;
    //include 'firebase-adminsdk.json';
    // Ruta al archivo JSON con las credenciales de la cuenta de servicio
    $pathToServiceAccountJson = 'pagosproumm-firebase-adminsdk-ev8oe-cf69dd3a3c.json';
    try
    {
        if (file_exists($pathToServiceAccountJson)) {
            echo "El archivo existe.";
        } else {
            echo "El archivo no existe.";
        }
        // Define los alcances requeridos (en este caso, para usar Firebase Cloud Messaging)
        $scopes = ['https://www.googleapis.com/auth/cloud-platform'];

        // Cargar las credenciales de la cuenta de servicio
        $credentials = new ServiceAccountCredentials($scopes, $pathToServiceAccountJson);

        // Obtener el token de acceso para hacer solicitudes autenticadas
        $accessToken = $credentials->fetchAuthToken();
        $token = $accessToken['access_token'];

        // Muestra el token de acceso
        echo "Token de acceso: " . $token;

        $url = 'https://fcm.googleapis.com/v1/projects/pagosproumm/messages:send';

        // Cuerpo de la solicitud
        $data = [
            "message" => [
                "token" => $deviceToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body
                ]
            ]
        ];

        // Encabezados
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json; charset=UTF-8',
        ];

        // Configuración de la solicitud HTTP con cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Ejecutar la solicitud
        $result = curl_exec($ch);
        //var_dump($result);

        // Cerrar la conexión cURL
        curl_close($ch);

        // Mostrar la respuesta

        $json=array('Respuesta'=>'Ok','Msg'=>$result,'message'=>$token);


    }
    catch(PDOException $error) {
        //echo $sql . "<br>" . $error->getMessage();
        $json=array('Respuesta'=>'Ok','Msg'=>"Error",'message'=>$error->getMessage());
    }
 
    echo json_encode($json);
    

?>