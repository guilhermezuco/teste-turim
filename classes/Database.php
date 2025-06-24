<?php
class Database {
    private $host = "localhost";
    private $db_name = "entrevista_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch(PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
            exit;
        }
        return $this->conn;
    }
}
