<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>SESSION DEBUG</h3>";

// Info básica
echo "<b>Now:</b> ".date('Y-m-d H:i:s')."<br>";
echo "<b>PHP SAPI:</b> ".php_sapi_name()."<br>";
echo "<b>session.save_path (ini_get):</b> ".ini_get('session.save_path')."<br>";
echo "<b>session.save_path (realpath):</b> ";
$sp = ini_get('session.save_path');
echo (@realpath($sp) ?: 'realpath fallo o no existe') . "<br>";

// Intento de crear/check folder
$tryPaths = [
    $sp,
    __DIR__ . DIRECTORY_SEPARATOR . 'tmp',
    __DIR__ . DIRECTORY_SEPARATOR . 'sessions',
];

foreach ($tryPaths as $p) {
    echo "<hr><b>Probar ruta:</b> $p<br>";
    if (!$p) { echo "ruta vacía\n"; continue; }
    // comprueba existencia
    if (file_exists($p)) {
        echo "exists: SI<br>";
        echo "is_writable: ".(is_writable($p) ? "SI" : "NO")."<br>";
        echo "realpath: ".(@realpath($p) ?: 'n/a')."<br>";
        // listar archivos (máx 10)
        $files = array_slice(scandir($p), 0, 20);
        echo "listing: <pre>".htmlspecialchars(print_r($files, true))."</pre>";
    } else {
        echo "exists: NO — intento mkdir...<br>";
        $ok = @mkdir($p, 0777, true);
        echo "mkdir result: ".($ok ? "OK" : "FALLO")."<br>";
        if ($ok) echo "is_writable: ".(is_writable($p) ? "SI" : "NO")."<br>";
    }
}

echo "<hr><b>Session start test:</b><br>";
session_start();
echo "session_id(): ".session_id()."<br>";
$_SESSION['debug_test'] = 'hello_'.time();
echo "_SESSION saved value: ".$_SESSION['debug_test']."<br>";

// comprobar archivo de sesión en save_path
$sp = ini_get('session.save_path');
if ($sp && file_exists($sp)) {
    $glob = glob($sp . DIRECTORY_SEPARATOR . 'sess_*');
    echo "<b>session files found:</b><pre>" . htmlspecialchars(print_r($glob, true)) . "</pre>";
} else {
    echo "No session.save_path o carpeta no existe.<br>";
}