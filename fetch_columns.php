<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fileData = $_SESSION['fileData'] ?? null;

    if (!$fileData) {
        echo json_encode(['status' => 'error', 'message' => 'No hay datos cargados.']);
        exit;
    }

    $columns = $fileData[0];
    echo json_encode(['status' => 'success', 'columns' => $columns]);
}
