<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $column = $input['column'] ?? null;

    if (!$column) {
        echo json_encode(['status' => 'error', 'message' => 'No se seleccionó ninguna columna.']);
        exit;
    }

    $fileData = $_SESSION['fileData'] ?? null;
    if (!$fileData) {
        echo json_encode(['status' => 'error', 'message' => 'No hay datos cargados.']);
        exit;
    }

    $headers = $fileData[0];
    $colIndex = array_search($column, $headers);

    if ($colIndex === false) {
        echo json_encode(['status' => 'error', 'message' => 'Columna no encontrada.']);
        exit;
    }

    $values = array_column(array_slice($fileData, 1), $colIndex);

    if (empty($values)) {
        echo json_encode(['status' => 'error', 'message' => 'La columna no contiene datos válidos.']);
        exit;
    }

    $isNumeric = count(array_filter($values, 'is_numeric')) === count($values);

    $content = "<h5 class='text-info'>Resultados para la columna: $column</h5>";
    if ($isNumeric) {
        $mean = round(array_sum($values) / count($values), 2);
        sort($values);
        $median = $values[(int)(count($values) / 2)];
        $mode = array_keys(array_count_values($values), max(array_count_values($values)))[0];

        $content .= "<p><strong>Media:</strong> $mean</p>";
        $content .= "<p><strong>Mediana:</strong> $median</p>";
        $content .= "<p><strong>Moda:</strong> $mode</p>";
    } else {
        $mode = array_keys(array_count_values($values), max(array_count_values($values)))[0];

        $content .= "<p><strong>Moda:</strong> $mode</p>";
        $content .= "<p class='text-muted'>Nota: Solo se puede calcular la moda para datos no numéricos.</p>";
    }

    echo json_encode(['status' => 'success', 'content' => $content]);
}
