<?php

class Database {
    private $host = 'localhost';
    private $user = 'unibowebprogramming';
    private $pass = 'aU74pudmHUeD';
    private $name = 'my_unibowebprogramming';
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if ($this->conn->connect_error) {
            die("Connessione fallita: " . $this->conn->connect_error);
        }
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT password_hash, ruolo FROM USER WHERE UserName = ?");
        if (!$stmt) {
            return ["success" => false, "error" => "Errore nella query"];
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows !== 1) {
            return ["success" => false, "error" => "Utente non trovato"];
        }

        $stmt->bind_result($hash, $ruolo);
        $stmt->fetch();
        $stmt->close();

        if ($password === $hash) { // password_verify($password, $hash) per non tenere le password in chiaro
            return ["success" => true, "ruolo" => $ruolo];
        } else {
            return ["success" => false, "error" => "Password errata"];
        }
    }
}   // in fase di inserimento sostituisci con: $hash = password_hash("1234", PASSWORD_DEFAULT);

