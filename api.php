<?php
// CONFIGURAÇÃO CABEÇALHOS PARA JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// CONEXÃO COM BANCO DE DADOS
try {
    $pdo = new PDO("mysql:host=localhost;dbname=teste_turim", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro de conexão ao BD: " . $e->getMessage()]);
    exit;
}

// AÇÃO PARA LER
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'ler') {
    $stmt = $pdo->query("SELECT nome FROM pessoa ORDER BY id DESC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
    exit;
}

// AÇÃO PARA GRAVAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (!isset($data->pessoas) || !is_array($data->pessoas) || empty($data->pessoas)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Nenhuma pessoa para gravar."]);
        exit;
    }

    $count = 0;

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO pessoa (nome) VALUES (:nome)");

        foreach ($data->pessoas as $pessoa) {
            $nome = trim($pessoa->nome ?? '');
            if ($nome) {
                $stmt->bindParam(':nome', $nome);
                $stmt->execute();
                $count++;
            }
        }

        $pdo->commit();

        http_response_code(201);
        echo json_encode(["success" => true, "message" => "{$count} pessoa cadastradas com sucesso."]);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erro ao gravar: " . $e->getMessage()]);
    }
}
