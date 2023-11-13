<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = 'localhost:3306';
$username = 'root';
$password = 'Lucas1234';
$dbname = 'poisonCompany';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the JSON parameter from the POST request
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!empty($jsonParam)) {
    // Prepare the data for deletion
    $idPedido = isset($jsonParam['idPedido']) ? intval($jsonParam['idPedido']) : 0;

    // Prepare the SQL statement for deletion
    $deleteQuery = "DELETE FROM Pedido WHERE idPedido = '$idPedido'";

    if ($con->query($deleteQuery) === true) {
        // Deletion successful
        $response = array(
            'success' => true,
            'message' => 'Pedido excluído com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in deletion
        $response = array(
            'success' => false,
            'message' => 'Erro ao excluir o Pedido: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para excluir o Pedido!'
    );
    echo json_encode($response);
}

$con->close();

?>