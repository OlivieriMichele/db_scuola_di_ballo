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

    public function aggiungiPersona($cf, $nome, $cognome, $nascita, $email, $telefono, $ruolo) {
        // Controlla ruolo valido
        if (!in_array($ruolo, ['INSEGNANTE', 'CLIENTE'])) {
            return ["success" => false, "error" => "Ruolo non valido"];
        }

        // Costruisci query dinamica con il campo giusto
        $query = "
            INSERT INTO PERSONA (cf, nome, cognome, nascita, email, telefono, $ruolo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ["success" => false, "error" => "Errore nella query"];
        }

        // Valorizza solo il campo corrispondente (es. INSEGNANTE = cf, CLIENTE = cf)
        $stmt->bind_param("sssssss", $cf, $nome, $cognome, $nascita, $email, $telefono, $cf);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok
            ? ["success" => true]
            : ["success" => false, "error" => "Errore durante l'inserimento"];
    }

}   // in fase di inserimento sostituisci con: $hash = password_hash("1234", PASSWORD_DEFAULT);
