<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idInstance = $_POST["idInstance"];
    $apiTokenInstance = $_POST["apiTokenInstance"];
    $action = $_POST["action"];
    $response = "";

    $apiUrl = "https://1103.api.green-api.com";

    switch ($action) {
        case "getSettings":
            $url = "$apiUrl/waInstance$idInstance/getSettings/$apiTokenInstance";
            break;
        case "getStateInstance":
            $url = "$apiUrl/waInstance$idInstance/getStateInstance/$apiTokenInstance";
            break;
        case "sendMessage":
            $url = "$apiUrl/waInstance$idInstance/sendMessage/$apiTokenInstance";
            $data = json_encode([
                "chatId" => $_POST["chatId"],
                "message" => $_POST["message"]
            ]);
            break;
        case "sendFileByUrl":
            $url = "$apiUrl/waInstance$idInstance/sendFileByUrl/$apiTokenInstance";
            $data = json_encode([
                "chatId" => $_POST["chatId"],
                "urlFile" => $_POST["fileUrl"],
                "fileName" => $_POST["fileName"]
            ]);
            break;
        default:
            die("Invalid action");
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (isset($data)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>GREEN API Test</title>
	<link rel="stylesheet" type="text/css" href="styles.css">

    <script>
        function sendRequest(action) {
            let formData = new FormData(document.getElementById("apiForm"));
            formData.append("action", action);
            fetch("", { method: "POST", body: formData })
                .then(response => response.text())
                .then(data => document.getElementById("response").value = data);
        }
    </script>
</head>
<body>
    <h2>GREEN API Test</h2>
    <form id="apiForm">
        <label>ID Instance:</label><input type="text" name="idInstance" required><br>
        <label>API Token:</label><input type="text" name="apiTokenInstance" required><br>
        <label>Chat ID:</label><input type="text" name="chatId"><br>
        <label>Message:</label><input type="text" name="message"><br>
        <label>File URL:</label><input type="text" name="fileUrl"><br>
        <label>File Name:</label><input type="text" name="fileName"><br>
        <button type="button" onclick="sendRequest('getSettings')">getSettings</button>
        <button type="button" onclick="sendRequest('getStateInstance')">getStateInstance</button>
        <button type="button" onclick="sendRequest('sendMessage')">sendMessage</button>
        <button type="button" onclick="sendRequest('sendFileByUrl')">sendFileByUrl</button>
    </form>
    <textarea id="response" readonly rows="10" cols="50"></textarea>
</body>
</html>
