<?php

$db = new SQLite3(
    __DIR__ . '/notebook.sqlite',
    SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE,
    ''
);

$db->exec("
CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    surname TEXT,
    name TEXT,
    patronymic TEXT,
    gender TEXT,
    birthdate TEXT,
    phone TEXT,
    address TEXT,
    email TEXT,
    comment TEXT
)
");