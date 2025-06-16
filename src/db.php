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

    public function getAule() {
        $aule = [];
        $stmt = $this->conn->prepare("SELECT ID FROM AULA");
        if ($stmt && $stmt->execute()) {
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $aule[] = $row["ID"];
            }
            $stmt->close();
        }
        return $aule;
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

    public function creaStileSeNonEsiste($nome) {
        $stmt = $this->conn->prepare("SELECT nome FROM STILE WHERE nome = ?");
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            $stmt->close();
            $stmt = $this->conn->prepare("INSERT INTO STILE (nome) VALUES (?)");
            $stmt->bind_param("s", $nome);
            $stmt->execute();
        } else {
            $stmt->close();
        }
    }

    public function creaClasseSeNonEsiste($nome, $tipo) {
        $stmt = $this->conn->prepare("SELECT nome FROM CLASSE WHERE nome = ? AND tipo = ?");
        $stmt->bind_param("ss", $nome, $tipo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            $stmt->close();
            $stmt = $this->conn->prepare("INSERT INTO CLASSE (nome, tipo, EVENTO, CORSO) VALUES (?, ?, NULL, ?)");
            $stmt->bind_param("sss", $nome, $tipo, $tipo);
            $stmt->execute();
        } else {
            $stmt->close();
        }
    }

    public function creaCorso($nome, $tipo, $descrizione) {
        // 1. Stile
        $this->creaStileSeNonEsiste($nome);

        // 2. Classe
        $this->creaClasseSeNonEsiste($nome, $tipo);

        // 3. Corso
        $stmt = $this->conn->prepare("INSERT INTO CORSO (nome, tipo, descrizione) VALUES (?, ?, ?)");
        if (!$stmt) return ["success" => false, "error" => "Errore query corso"];
        $stmt->bind_param("sss", $nome, $tipo, $descrizione);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok ? ["success" => true] : ["success" => false, "error" => "Errore inserimento corso (già esistente?)"];
    }

    public function creaLezioneSeNonEsiste($id, $data, $ora) {
        $stmt = $this->conn->prepare("SELECT * FROM LEZIONE WHERE ID = ? AND data = ? AND ora = ?");
        $stmt->bind_param("isi", $id, $data, $ora);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();

        if ($res->num_rows === 0) {
            $stmt = $this->conn->prepare("INSERT INTO LEZIONE (ID, data, ora) VALUES (?, ?, ?)");
            if (!$stmt) return ["success" => false, "error" => "Errore prepare lezione: " . $this->conn->error];
            $stmt->bind_param("isi", $id, $data, $ora);
            $ok = $stmt->execute();
            $stmt->close();
            if (!$ok) {
                return ["success" => false, "error" => "Errore inserimento lezione: " . $this->conn->error];
            }
        }

        return ["success" => true];
    }

    public function creaEvento($nome, $tipo, $id, $data, $ora, $descrizione) {
        // 1. Crea stile se mancante
        $this->creaStileSeNonEsiste($nome);

        // 2. Crea classe se mancante
        $this->creaClasseSeNonEsiste($nome, $tipo);

        // 3. Crea lezione se mancante
        $lezione = $this->creaLezioneSeNonEsiste($id, $data, $ora);
        if (!$lezione["success"]) return $lezione;

        // 4. Inserisce evento
        $stmt = $this->conn->prepare("INSERT INTO EVENTO (nome, tipo, ID, data, ora, descrizione) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) return ["success" => false, "error" => "Errore prepare evento: " . $this->conn->error];
        $stmt->bind_param("ssisis", $nome, $tipo, $id, $data, $ora, $descrizione);
        $ok = $stmt->execute();
        $errore = $stmt->error;
        $stmt->close();

        return $ok
            ? ["success" => true]
            : ["success" => false, "error" => "Errore inserimento evento: " . $errore];
    }

    public function creaEdizioneCorso($nome, $tipo, $anno, $livello, $id, $data, $ora) {
        // Verifica o crea la lezione associata
        $lezione = $this->creaLezioneSeNonEsiste($id, $data, $ora);
        if (!$lezione["success"]) {
            return ["success" => false, "error" => "Errore lezione: " . $lezione["error"]];
        }

        // Inserisce l'edizione del corso
        $stmt = $this->conn->prepare("
            INSERT INTO EDIZIONE_CORSO (nome, tipo, anno, livello, ID, data, ora)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            return ["success" => false, "error" => "Errore prepare edizione: " . $this->conn->error];
        }

        $stmt->bind_param("ssisiss", $nome, $tipo, $anno, $livello, $id, $data, $ora);
        $ok = $stmt->execute();
        $errore = $stmt->error;
        $stmt->close();

        return $ok
            ? ["success" => true]
            : ["success" => false, "error" => "Errore inserimento edizione: " . $errore];
    }

    public function getEventiPerData($dataInizio) {
        $dataInizio = date('Y-m-d', strtotime($dataInizio));
        $dataFine = date('Y-m-d', strtotime($dataInizio . ' +6 days'));

        $eventi = [];
        $stmt = $this->conn->prepare("
            SELECT nome, data, ora, descrizione, 'EVENTO' AS tipo
            FROM EVENTO
            WHERE data BETWEEN ? AND ?
        ");
        $stmt->bind_param("ss", $dataInizio, $dataFine);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $eventi[] = $row;
            }
        }
        $stmt->close();

        $stmt = $this->conn->prepare("
            SELECT nome, data, ora, CONCAT('Livello: ', livello) AS descrizione, 'CORSO' AS tipo
            FROM EDIZIONE_CORSO
            WHERE data BETWEEN ? AND ?
        ");
        $stmt->bind_param("ss", $dataInizio, $dataFine);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $eventi[] = $row;
            }
        }
        $stmt->close();

        return $eventi;
    }

    public function getEventiPerInsegnante($cf, $dataInizio) {
        $dataInizio = date('Y-m-d', strtotime($dataInizio));
        $dataFine = date('Y-m-d', strtotime($dataInizio . ' +6 days'));
        $eventi = [];
        
        // Eventi pubblici
        $stmt = $this->conn->prepare("
            SELECT nome, data, ora, descrizione, 'EVENTO' AS tipo
            FROM EVENTO
            WHERE data BETWEEN ? AND ?
        ");
        if ($stmt) {
            $stmt->bind_param("ss", $dataInizio, $dataFine);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $eventi[] = $row;
                }
            }
            $stmt->close();
        }
    
        // Edizioni corso
        $stmt = $this->conn->prepare("
            SELECT nome, data, ora, CONCAT('Livello: ', livello) AS descrizione, 'CORSO' AS tipo
            FROM EDIZIONE_CORSO
            WHERE data BETWEEN ? AND ?
        ");
        if ($stmt) {
            $stmt->bind_param("ss", $dataInizio, $dataFine);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $eventi[] = $row;
                }
            }
            $stmt->close();
        }
    
        // Lezioni private (richieste)
        $stmt = $this->conn->prepare("
            SELECT 'Lezione privata' AS nome, data, ora,
                   CONCAT('Con allievo: ', cf_allievo) AS descrizione,
                   'PRIVATA' AS tipo
            FROM richiesata_lezione_privata
            WHERE cf_insegnante = ? AND data BETWEEN ? AND ?
        ");
        if ($stmt) {
            $stmt->bind_param("sss", $cf, $dataInizio, $dataFine);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $eventi[] = $row;
                }
            }
            $stmt->close();
        }
    
        return $eventi;
    }

}   // in fase di inserimento sostituisci con: $hash = password_hash("1234", PASSWORD_DEFAULT);
