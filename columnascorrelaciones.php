<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fileData = $_SESSION['fileData'] ?? null;

    if (!$fileData) {
        echo json_encode(['status' => 'error', 'message' => 'No hay datos cargados.']);
        exit;
    }

    $headers = $fileData[0];
    $quantitativeColumns = [];

    foreach ($headers as $index => $header) {
        $columnData = array_column($fileData, $index);
        array_shift($columnData); 

        if (count(array_filter($columnData, 'is_numeric')) > 0) {
            $quantitativeColumns[] = $header;
        }
    }

    echo json_encode(['status' => 'success', 'columns' => $quantitativeColumns]);
}
?>
