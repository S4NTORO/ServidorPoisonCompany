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
    // Prepare the data for insertion
    $cdCarrinho = isset($jsonParam['Carrinho_idCarrinho']) ? $jsonParam['Carrinho_idCarrinho'] : 1;
    $qtd = isset($jsonParam['qtd']) ? $jsonParam['qtd'] : 0;
    $cdTamanho = isset($jsonParam['Produto_idProduto']) ? intval($jsonParam['Produto_idProduto']) : 0;
    $cdCor = isset($jsonParam['Cor_idCor']) ? intval($jsonParam['Cor_idCor']) : 0;
    $cdProduto = isset($jsonParam['Tamanho_idTamanho']) ? intval($jsonParam['Tamanho_idTamanho']) : 0;
  

    // Prepare the SQL statement for insertion
    $insertQuery = "INSERT INTO Pedido (Carrinho_idCarrinho, qtd, Produto_idProduto, Cor_idCor, Tamanho_idTamanho) 
		VALUES ('$cdCarrinho', '$qtd', '$cdTamanho', '$cdCor', $cdProduto)";

    if ($con->query($insertQuery) === true) {
        // Insertion successful
        $response = array(
            'success' => true,
            'message' => 'Pedido inserido com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in insertion
        $response = array(
            'success' => false,
            'message' => 'Erro no registro do pedido: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para o registro do pedido!'
    );
    echo json_encode($response);
}

$con->close();

?>