<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "200.98.129.120:3306";
$username = 'marcosvir_santoro';
$password = '.ppS*G]@VmDz';
$database = 'marcosvir_santoro';

// $servername = 'localhost:3306';
// $username = 'root';
// $password = 'Lucas1234';
// $database = 'poisonCompany';

// Add the following lines to set CORS headers
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin (you can restrict this in a production environment)
header("Access-Control-Allow-Methods: POST, GET"); // Allow POST and GET requests
header("Access-Control-Allow-Headers: Content-Type"); // Allow Content-Type header

try {
    $conn = new PDO("mysql:host=$servername; dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}


$inputFile = file_get_contents('php://input');
if ($data = json_decode($inputFile, true)) {

    if (json_last_error() === JSON_ERROR_NONE) {
        $stmt = $conn->prepare("INSERT INTO pedido (Carrinho_idCarrinho, qtd, Produto_idProduto, Cor_idCor, Tamanho_idTamanho) 
        VALUES (:Carrinho_idCarrinho, :qtd, :Produto_idProduto, :Cor_idCor, :Tamanho_idTamanho)");

        $stmt->bindParam(':Carrinho_idCarrinho', $data['Carrinho_idCarrinho'], PDO::PARAM_INT);
        $stmt->bindParam(':qtd', $data['qtd'], PDO::PARAM_INT);
        $stmt->bindParam(':Produto_idProduto', $data['Produto_idProduto'], PDO::PARAM_INT);
        $stmt->bindParam(':Cor_idCor', $data['Cor_idCor'], PDO::PARAM_INT);
        $stmt->bindParam(':Tamanho_idTamanho', $data['Tamanho_idTamanho'], PDO::PARAM_INT);

        // Prepare the SQL statement for insertion
        if ($stmt->execute()) {
            $response = [
                "success" => true,
                "message" => "Data saved successfully."
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Failed to save data."
            ];
        }
    } else {
        $response = [
            "success" => false,
            "message" => "Invalid JSON format."
        ];
    }
} else {
    // No data provided
    $response = [
        "success" => false,
        "message" => "Input JSON file not found."
    ];
    
}
// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

// Close the database connection
$conn = null;
?>