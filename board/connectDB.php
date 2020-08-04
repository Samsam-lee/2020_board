<?php
require_once('db_info.php');

// DB 연결
function DB_Connect()
{
    $db_conn = new mysqli(db_info::db_url, db_info::user_id,
        db_info::user_password, db_info::db);

    // DB 연결이 안될 시 메시지 출력
    if ($db_conn->connect_errno) {
        echo "Failed to connect to the MySQL Server";
        exit(-1);
    }
    return $db_conn;
}
