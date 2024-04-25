<?php 

$db = new SQLite3("hms.db"); 

if (!$db)
{
    echo 'Error: Database connection failed';
}