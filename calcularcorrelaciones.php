<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $variable1 = $input['variable1'] ?? null;
    $variable2 = $input['variable2'] ?? null;

    if (!$variable1 || !$variable2) {
        echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar ambas variables.']);
        exit;
    }

    $fileData = $_SESSION['fileData'] ?? null;
    if (!$fileData) {
        echo json_encode(['status' => 'error', 'message' => 'No hay datos cargados.']);
        exit;
    }

    $headers = $fileData[0];
    $index1 = array_search($variable1, $headers);
    $index2 = array_search($variable2, $headers);

    if ($index1 === false || $index2 === false) {
        echo json_encode(['status' => 'error', 'message' => 'Una o ambas variables no fueron encontradas.']);
        exit;
    }
    $column1 = array_column($fileData, $index1);
    $column2 = array_column($fileData, $index2);
    array_shift($column1); // Quitar encabezado
    array_shift($column2); // Quitar encabezado

    $column1 = array_filter($column1, 'is_numeric');
    $column2 = array_filter($column2, 'is_numeric');

    if (empty($column1) || empty($column2)) {
        echo json_encode(['status' => 'error', 'message' => 'Ambas variables deben tener valores numéricos.']);
        exit;
    }

 
    function calculateCorrelation($x, $y) {
        $meanX = array_sum($x) / count($x);
        $meanY = array_sum($y) / count($y);
        $numerator = 0;
        $denominatorX = 0;
        $denominatorY = 0;

        for ($i = 0; $i < count($x); $i++) {
            $numerator += ($x[$i] - $meanX) * ($y[$i] - $meanY);
            $denominatorX += pow($x[$i] - $meanX, 2);
            $denominatorY += pow($y[$i] - $meanY, 2);
        }

        return $numerator / sqrt($denominatorX * $denominatorY);
    }

    $correlation = calculateCorrelation($column1, $column2);

    echo json_encode(['status' => 'success', 'correlation' => $correlation]);
}
?>
