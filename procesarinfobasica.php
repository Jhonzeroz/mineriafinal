<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $option = $input['option'] ?? null;

    session_start();
    $fileData = $_SESSION['fileData'] ?? null;

    if (!$fileData) {
        echo json_encode(['status' => 'error', 'message' => 'No hay datos cargados.']);
        exit;
    }

    switch ($option) {
        case 'info':
            $info = "<div class='card shadow mb-3'>
                        <div class='card-body'>
                            <h6 class='card-title'>Información del Archivo</h6>
                            <p style='font-size: 22px' class='card-text'><strong>Total Filas:</strong> " . count($fileData) . "</p>
                            <p style='font-size: 22px' class='card-text'><strong>Total Columnas:</strong> " . count($fileData[0]) . "</p>
                        </div>
                    </div>";
            echo json_encode(['status' => 'success', 'content' => $info]);
            break;

            case 'head':
                $head = "<div class='card shadow mb-3'>
                            <div class='card-body'>
                                <h5 class='card-title'>Primeras Filas del Archivo</h5>
                                <div class='table-responsive'> <!-- Hacemos la tabla responsive -->
                                    <table id='dataTable' class='table table-striped table-hover table-bordered'>
                                        <thead class='table-dark'>
                                            <tr>";
                foreach ($fileData[0] as $col) {
                    $head .= "<th style='color: white;'>$col</th>";
                }
                $head .= "       </tr>
                                </thead>
                                <tbody>";
                for ($i = 1; $i <= 5 && $i < count($fileData); $i++) {
                    $head .= "<tr>";
                    foreach ($fileData[$i] as $val) {
                        $head .= "<td>$val</td>";
                    }
                    $head .= "</tr>";
                }
                $head .= "       </tbody>
                                    </table>
                                </div> <!-- Cierre de .table-responsive -->
                            </div>
                        </div>";
                echo json_encode(['status' => 'success', 'content' => $head]);
                break;
            

        case 'size':
            $fileSizeKB = round($_SESSION['fileSize'] / 1024, 2);
            $sizeInfo = "<div class='card shadow mb-3'>
                            <div class='card-body'>
                                <h6 class='card-title'>Tamaño del Archivo</h6>
                                <p style='font-size: 22px' class='card-text'>Tamaño del archivo: $fileSizeKB KB</p>
                            </div>
                        </div>";
            echo json_encode(['status' => 'success', 'content' => $sizeInfo]);
            break;

        case 'nullValues':
            $nullValues = "<div class='card shadow mb-3'>
                            <div class='card-body'>
                                <h6 class='card-title'>Valores Nulos/Vacíos</h6>
                                <ul>";
            foreach ($fileData[0] as $index => $col) {
                $nullCount = count(array_filter(array_column($fileData, $index), function ($value) {
                    return is_null($value) || $value === '';
                }));
                $nullValues .= "<li >$col: $nullCount valores nulos/vacíos</li>";
            }
            $nullValues .= "</ul>
                            </div>
                        </div>";
            echo json_encode(['status' => 'success', 'content' => $nullValues]);
            break;

        case 'columnsCount':
            $columnsCount = count($fileData[0]);
            $columnsInfo = "<div class='card shadow mb-3'>
                                <div class='card-body'>
                                    <h6 class='card-title'>Cantidad de Columnas</h6>
                                    <p style='font-size: 22px' class='card-text'>Cantidad de columnas: $columnsCount</p>
                                </div>
                            </div>";
            echo json_encode(['status' => 'success', 'content' => $columnsInfo]);
            break;

            case 'showAll':
                $table = "<div class='card shadow mb-3'>
                            <div class='card-body'>
                                <h5 class='card-title'>Datos Completos</h5>
                                <div class='table-responsive'>
                                    <table id='dataTable' class='table table-striped table-hover table-bordered'>
                                        <thead class='table-dark'><tr>";
                foreach ($fileData[0] as $col) {
                    $table .= "<th style='color: white;'>$col</th>";
                }
                $table .= "</tr></thead><tbody>";
                for ($i = 1; $i < count($fileData); $i++) {
                    $table .= "<tr>";
                    foreach ($fileData[$i] as $val) {
                        $table .= "<td style='color: black;'>$val</td>";
                    }
                    $table .= "</tr>";
                }
                $table .= "</tbody></table>
                            </div>
                        </div>
                    </div>";
                echo json_encode(['status' => 'success', 'content' => $table]);
                break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Opción no válida.']);
            break;
    }
}
?>
