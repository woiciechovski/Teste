<?php

namespace ASPTest;

use ASPTest\SQLiteConnection;

require __DIR__ . '/../../vendor/autoload.php';

(new SQLiteConnection())->createTableUser();

class Config
{
    /**
     * path to the sqlite file
     */
    const PATH_TO_SQLITE_FILE = 'db/phpsqlite.db';
}
