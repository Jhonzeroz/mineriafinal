<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $col1 = $input['col1'] ?? null;
    $col2 = $input['col2'] ?? null;

    if (!$col1 || !$col2) {
        echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar ambas columnas.']);
        exit;
    }

    $fileData = $_SESSION['fileData'] ?? null;
    if (!$fileData) {
        echo json_encode(['status' => 'error', 'message' => 'No hay datos cargados.']);
        exit;
    }

    $headers = $fileData[0];
    $col1Index = array_search($col1, $headers);
    $col2Index = array_search($col2, $headers);

    if ($col1Index === false || $col2Index === false) {
        echo json_encode(['status' => 'error', 'message' => 'Una o ambas columnas no fueron encontradas.']);
        exit;
    }

    $table = [];
    foreach ($fileData as $key => $row) {
        if ($key === 0) continue; 
        $value1 = $row[$col1Index] ?? null;
        $value2 = $row[$col2Index] ?? null;

        if ($value1 && $value2) {
            if (!isset($table[$value1])) {
                $table[$value1] = [];
            }
            $table[$value1][$value2] = ($table[$value1][$value2] ?? 0) + 1;
        }
    }

    // Generar HTML para la tabla
    $html = "<table id='TabladeContingencia' class='table table-striped table-bordered'>";
    $html .= "<thead><tr><th>$col1</th><th>$col2</th><th>Frecuencia</th></tr></thead><tbody>";
    foreach ($table as $key1 => $values) {
        foreach ($values as $key2 => $count) {
            $html .= "<tr><td>$key1</td><td>$key2</td><td>$count</td></tr>";
        }
    }
    $html .= "</tbody></table>";

    echo json_encode(['status' => 'success', 'content' => $html]);
}
