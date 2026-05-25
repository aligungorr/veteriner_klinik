<?php
$host = "localhost";
$user = "root";
$password = "452932Ag..";
$database = "veteriner_klinik";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");


function call_procedure($conn, $proc_name, $params = []) {
    if (empty($params)) {
        $sql = "CALL $proc_name()";
    } else {
        $escaped = array_map(function($p) use ($conn) {
            if ($p === null) return "NULL";
            return "'" . mysqli_real_escape_string($conn, $p) . "'";
        }, $params);
        $sql = "CALL $proc_name(" . implode(",", $escaped) . ")";
    }

    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        // Trigger veya DB hatası — session'a yaz, sayfaya geri dön
        $_SESSION['hata'] = mysqli_error($conn);
        error_log("DB Hata [$proc_name]: " . mysqli_error($conn));
        return false;
    }

    if ($result === true) {
        return true;
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_free_result($result);
    while (mysqli_more_results($conn)) {
        mysqli_next_result($conn);
    }
    return $rows;
}
?>
