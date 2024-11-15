<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function MedidasDeTendencia($data) {
    $result = "<div class='row'>";
    foreach ($data[0] as $index => $columnName) {
        $values = array_column($data, $index);
        $values = array_filter($values, 'is_numeric'); // Solo valores numéricos

        if (count($values) > 0) {
            // Calcular media, mediana y moda
            $mean = round(array_sum($values) / count($values), 2);
            sort($values);
            $median = $values[(int) (count($values) / 2)];
            $mode = array_keys(array_count_values($values), max(array_count_values($values)))[0];

            // Generar una tarjeta para la columna
            $result .= "
            <div class='col-md-4'>
                <div class='card small-card shadow-sm mb-3'>
                    <div class='card-body'>
                        <h6 class='card-title'>$columnName</h6>
                        <p><strong>Media:</strong> $mean</p>
                        <p><strong>Mediana:</strong> $median</p>
                        <p><strong>Moda:</strong> $mode</p>
                    </div>
                </div>
            </div>";
        }
    }
    $result .= "</div>";
    return $result;
}
function COntingencia($data, $col1Index, $col2Index) {
    $headers = $data[0]; // Obtener encabezados de la primera fila
    $col1Header = $headers[$col1Index];
    $col2Header = $headers[$col2Index];

    $table = [];
    foreach ($data as $key => $row) {
        if ($key === 0) {
            continue; // Omitir encabezado
        }
        $col1Value = $row[$col1Index] ?? null;
        $col2Value = $row[$col2Index] ?? null;

        if ($col1Value && $col2Value) {
            if (!isset($table[$col1Value])) {
                $table[$col1Value] = [];
            }
            $table[$col1Value][$col2Value] = ($table[$col1Value][$col2Value] ?? 0) + 1;
        }
    }

    // Generar HTML para la tabla con encabezados correctos
    $html = "<table id='TabladeContingencia' class='table table-striped table-bordered'>";
    $html .= "<thead><tr><th>$col1Header</th><th>$col2Header</th><th>Frecuencia</th></tr></thead>";
    $html .= "<tbody>";

    foreach ($table as $key1 => $values) {
        foreach ($values as $key2 => $count) {
            $html .= "<tr><td>$key1</td><td>$key2</td><td>$count</td></tr>";
        }
    }

    $html .= "</tbody></table>";
    return $html;
}
function Correlacioness($data) {
    $numericColumns = [];
    foreach ($data[0] as $index => $columnName) {
        $values = array_column($data, $index);
        if (array_filter($values, 'is_numeric')) {
            $numericColumns[$columnName] = $values;
        }
    }

    $html = "<table id='correlationTable' class='styled-table'><thead><tr><th>Variable 1</th><th>Variable 2</th><th>Correlación</th></tr></thead><tbody>";
    $columns = array_keys($numericColumns);

    for ($i = 0; $i < count($columns); $i++) {
        for ($j = $i + 1; $j < count($columns); $j++) {
            $corr = correlation($numericColumns[$columns[$i]], $numericColumns[$columns[$j]]);
            $html .= "<tr><td>{$columns[$i]}</td><td>{$columns[$j]}</td><td>" . round($corr, 4) . "</td></tr>"; // Redondear a 4 decimales
        }
    }
    $html .= "</tbody></table>";
    return $html;
}


function correlation($x, $y) {
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
function InformacionBasica($data, $file) {
    // Información del archivo
    $fileSize = filesize($file['tmp_name']); // Tamaño del archivo en bytes
    $fileSizeKB = round($fileSize / 1024, 2); // Convertir a KB
    $fileSizeMB = round($fileSize / 1048576, 2); // Convertir a MB
    $fileName = $file['name'];
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    // Formatear la fecha de carga
    setlocale(LC_TIME, 'es_ES.UTF-8'); // Configurar el idioma a español
    $date = new DateTime();
    $uploadDate = strftime("%e de %B del %Y a las %H:%M:%S", $date->getTimestamp()); // Formato: 15 de noviembre del 2024 a las 14:31:38

    $totalRows = count($data);
    $totalColumns = count($data[0]);
    $columnNames = $data[0]; // Suponiendo que la primera fila tiene los nombres de las columnas
    $columnInfo = [];

    // Procesar información por columna
    foreach ($columnNames as $index => $columnName) {
        $columnData = array_column($data, $index);
        array_shift($columnData); // Remover el encabezado para el análisis

        // Contar valores únicos y nulos
        $uniqueValues = count(array_unique($columnData));
        $nullValues = count(array_filter($columnData, function($value) {
            return is_null($value) || $value === '';
        }));
        $sampleData = implode(', ', array_slice($columnData, 0, 3)); // Primeros 3 valores como muestra

        // Agregar información de la columna
        $columnInfo[] = [
            'name' => $columnName,
            'unique' => $uniqueValues,
            'nulls' => $nullValues,
            'sample' => $sampleData
        ];
    }

    // Generar HTML en formato de cards compactas con la información extendida
    $html = "
    <div class='row'>
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body text-center'>
                    <h6 class='card-title'>Total Filas</h6>
                    <p class='card-text'>$totalRows</p>
                </div>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body text-center'>
                    <h6 class='card-title'>Total Columnas</h6>
                    <p class='card-text'>$totalColumns</p>
                </div>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body text-center'>
                    <h6 class='card-title'>Tamaño de Archivo</h6>
                    <p class='card-text'>" . ($fileSizeMB >= 1 ? "$fileSizeMB MB" : "$fileSizeKB KB") . "</p>
                </div>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body text-center'>
                    <h6 class='card-title'>Nombre de Archivo</h6>
                    <p class='card-text'>$fileName</p>
                </div>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body text-center'>
                    <h6 class='card-title'>Tipo de Archivo</h6>
                    <p class='card-text'>$fileType</p>
                </div>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body text-center'>
                    <h6 class='card-title'>Fecha de Carga</h6>
                    <p class='card-text'>$uploadDate</p>
                </div>
            </div>
        </div>
    </div>";

    // Información detallada de cada columna en tarjetas pequeñas
    $html .= "<div class='row mt-3'>";
    foreach ($columnInfo as $info) {
        $html .= "
        <div class='col-md-4'>
            <div class='card small-card shadow-sm mb-3'>
                <div class='card-body'>
                    <h6 class='card-title'>{$info['name']}</h6>
                    <p><strong>Únicos:</strong> {$info['unique']}</p>
                    <p><strong>Nulos:</strong> {$info['nulls']}</p>
                    <p><strong>Ejemplo:</strong> {$info['sample']}</p>
                </div>
            </div>
        </div>";
    }
    $html .= "</div>";

    return $html;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['datafile'])) {
        $file = $_FILES['datafile'];
        $allowedExtensions = ['csv', 'xls', 'xlsx'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de archivo no permitido.']);
            exit;
        }

        $spreadsheet = IOFactory::load($file['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $columns = $sheetData[0]; // Primera fila contiene los nombres de columnas
        $data = array_slice($sheetData, 1); // Filas de datos, excluyendo el encabezado
        
        $formattedData = [];
        foreach ($data as $row) {
            $formattedRow = [];
            foreach ($columns as $index => $columnName) {
                $formattedRow[$columnName] = $row[$index] ?? null; // Asegúrate de que $row[$index] exista
            }
            $formattedData[] = $formattedRow;
        }
        $basicInfo = InformacionBasica($sheetData, $file);
        $trendInfo = MedidasDeTendencia($sheetData);
        $tablesInfo = COntingencia($sheetData, 0, 1); // Personaliza índices de columnas
        $correlationInfo = Correlacioness($sheetData);
        $chartsInfo = "<p>Genera gráficos dinámicos aquí (Usar librerías como Chart.js o similar en cliente).</p>";

        echo json_encode([
            'status' => 'success',
            'basicInfo' => $basicInfo,
            'trendInfo' => $trendInfo,
            'tablesInfo' => $tablesInfo,
            'chartsInfo' => "<p>Genera gráficos dinámicos aquí (Usar librerías como Chart.js o similar en cliente).</p>",
            'correlationInfo' => $correlationInfo,
            'columns' => $columns,
            'data' => $formattedData 
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se cargó ningún archivo.']);
    }
}
