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

// Retrieve the JSON parameter
$jsonParam = json_decode(file_get_contents('php://input'), true);

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

    $json = array();

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

    if ($json) {
        $encoded_json = json_encode($json);
        if ($encoded_json === false) {
            echo "Error encoding JSON: " . json_last_error_msg();
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo $encoded_json;
        }
    } else {
        echo "Empty JSON data.";
    }

    $result->free_result();
}

$con->close();

?>