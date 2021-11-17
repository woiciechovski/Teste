<?php

namespace ASPTest;

/**
 * SQLite connnection
 */
class SQLiteConnection
{
    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect()
    {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }

        return $this->pdo;
    }

    public function createTableUser()
    {
        $pdo = $this->connect();
        $sql = "CREATE TABLE IF NOT EXISTS user (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            secondName TEXT,
            email TEXT,
            age INTEGER,
            password TEXT
        )";
        $pdo->exec($sql);
    }
}
