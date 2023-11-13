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
    // Prepare the data for updating
    $idPedido = isset($jsonParam['idPedido']) ? intval($jsonParam['idPedido']) : 0;
    $qtd = isset($jsonParam['qtd']) ? $jsonParam['qtd'] : '';
    $cdProduto = isset($jsonParam['Produto_idProduto']) ? $jsonParam['Produto_idProduto'] : '';
    $cdCor = isset($jsonParam['Cor_idCor']) ? $jsonParam['Cor_idCor'] : '';
    $cdTamanho = isset($jsonParam['Tamanho_idTamanho']) ? intval($jsonParam['Tamanho_idTamanho']) : 0;

    // Prepare the SQL statement for updating
    $updateQuery = "UPDATE Pedido SET qtd = '$qtd', Produto_idProduto = '$cdProduto', Cor_idCor = '$cdCor', 
        Tamanho_idTamanho = '$cdTamanho' WHERE idPedido = '$idPedido'";

    if ($con->query($updateQuery) === true) {
        // Update successful
        $response = array(
            'success' => true,
            'message' => 'Pedido atualizado com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in update
        $response = array(
            'success' => false,
            'message' => 'Erro ao atualizar o pedido: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para atualizar o pedido!'
    );
    echo json_encode($response);
}

$con->close();

?>