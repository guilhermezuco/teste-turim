<?php
class Pessoa {
    private $conn;
    private $table = 'pessoa';

    public $nome;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table . " (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $stmt->bindParam(":nome", $this->nome);
        return $stmt->execute();
    }

    public function ler() {
        $query = "SELECT nome FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function excluirPorNome($nome) {
        $query = "DELETE FROM " . $this->table . " WHERE nome = :nome";
        $stmt = $this->conn->prepare($query);
        $nome = htmlspecialchars(strip_tags($nome));
        $stmt->bindParam(":nome", $nome);
        return $stmt->execute();
    }
}