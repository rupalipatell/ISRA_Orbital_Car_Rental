<?php
session_start();
session_unset();
session_destroy();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

if (headers_sent($file, $line)) {
    echo "Headers already sent in $file on line $line";
    exit;
}

header("Location: ../Frontend/login.php");
exit;
