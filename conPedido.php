<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$servername = "200.98.129.120:3306";
$username = 'marcosvir_santoro';
$password = '.ppS*G]@VmDz';
$database = 'marcosvir_santoro';

// Add the following lines to set CORS headers
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin (you can restrict this in a production environment)
header("Access-Control-Allow-Methods: POST, GET"); // Allow POST and GET requests
header("Access-Control-Allow-Headers: Content-Type"); // Allow Content-Type header

$con = new mysqli($servername, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the request parameter
$stringParam = file_get_contents('php://input');

// Retrieve the JSON parameter
$jsonParamRequest = json_decode(file_get_contents('php://input'), true);

// Checking if it's a JSON array
if ($stringParam[0] == '[') {
    $jsonParam = $jsonParamRequest[0]; // Take the first object of the JSON array as the filter
} else {
    $jsonParam = $jsonParamRequest; // Keep what was received if it's a JSON object
}

$json = array();// Create a response array

if (!empty($jsonParam)) {
    // Prepare the WHERE clause
    $whereClause = ' WHERE ';
    foreach ($jsonParam as $field => $value) {
        if ($value != '' && $value != '0') {
            $whereClause .= "$field = '$value' AND ";
        }
    }
    $whereClause = rtrim($whereClause, ' AND ');

    // Prepare the SQL statement
    $consulta = "SELECT idPedido, qtd, Carrinho_idCarrinho, Tamanho_idTamanho, Cor_idCor, Produto_idProduto 
                 FROM Pedido $whereClause";

    $result = $con->query($consulta);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Convert character encoding for each field
            foreach ($row as &$value) {
                $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }

            $pedido = array(
                "idPedido" => $row['idPedido'],
                "qtd" => $row['qtd'],
                "Carrinho_idCarrinho" => $row['Carrinho_idCarrinho'],
                "Tamanho_idTamanho" => $row['Tamanho_idTamanho'],
                "Cor_idCor" => $row['Cor_idCor'],
                "Produto_idProduto" => $row['Produto_idProduto']
            );
            $json[] = $pedido;
        }
    } else {
        $pedido = array(
            "idPedido" => 0,
            "qtd" => 0,
            "Carrinho_idCarrinho" => 0,
            "Tamanho_idTamanho" => 0,
            "Cor_idCor" => 0,
            "Produto_idProduto" => 0,
        );
        $json[] = $pedido;
    }


    $result->free_result();
}

$con->close();

// Send the JSON response
header('Content-Type: application/json; charset=utf-8');
echo json_encode($json);

?>