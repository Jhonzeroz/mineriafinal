
<?php
// Habilitar visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Archivo analysis.php original
try {
    echo "Inicio del script analysis.php<br>";
    <?php
require 'vendor/autoload.php'; // Para PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

// Ruta del archivo subido
$file = "uploads/" . urldecode($_GET['file']);
$fileType = pathinfo($file, PATHINFO_EXTENSION);

// Leer el archivo
$data = [];
if ($fileType === 'csv') {
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
    }
} else {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();
}

// Separar columnas numéricas y texto
$headers = $data[0]; // Encabezados
$rows = array_slice($data, 1); // Filas de datos
$numericColumns = [];
$textColumns = [];
foreach ($headers as $index => $header) {
    if (is_numeric($rows[0][$index])) {
        $numericColumns[$header] = array_column($rows, $index);
    } else {
        $textColumns[$header] = array_column($rows, $index);
    }
}

// Incluir encabezado del diseño
include 'templates/header.php';
?>

<!-- Tabs para el análisis -->
<ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">Información Básica</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="trend-tab" data-bs-toggle="tab" data-bs-target="#trend" type="button">Medidas de Tendencia</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="tables-tab" data-bs-toggle="tab" data-bs-target="#tables" type="button">Tablas de Contingencia</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="charts-tab" data-bs-toggle="tab" data-bs-target="#charts" type="button">Gráficos</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="correlations-tab" data-bs-toggle="tab" data-bs-target="#correlations" type="button">Correlaciones</button>
    </li>
</ul>

<div class="tab-content mt-3">
    <!-- Información Básica -->
    <div class="tab-pane fade show active" id="info">
        <h2>Información Básica</h2>
        <p>Total de filas: <?php echo count($rows); ?></p>
        <p>Total de columnas: <?php echo count($headers); ?></p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th><?php echo htmlspecialchars($header); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?php echo htmlspecialchars($cell); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Medidas de Tendencia -->
    <div class="tab-pane fade" id="trend">
        <h2>Medidas de Tendencia</h2>
        <?php foreach ($numericColumns as $header => $values): ?>
            <?php
            $mean = array_sum($values) / count($values);
            sort($values);
            $median = $values[intval(count($values) / 2)];
            $mode = array_count_values($values);
            $mode = array_search(max($mode), $mode);
            ?>
            <h3><?php echo $header; ?></h3>
            <p>Media: <?php echo $mean; ?></p>
            <p>Mediana: <?php echo $median; ?></p>
            <p>Moda: <?php echo $mode; ?></p>
        <?php endforeach; ?>
    </div>

    <!-- Tablas de Contingencia -->
    <div class="tab-pane fade" id="tables">
        <h2>Tablas de Contingencia</h2>
        <form method="POST">
            <select name="col1">
                <?php foreach ($headers as $header): ?>
                    <option value="<?php echo $header; ?>"><?php echo $header; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="col2">
                <?php foreach ($headers as $header): ?>
                    <option value="<?php echo $header; ?>"><?php echo $header; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Generar Tabla</button>
        </form>
        <?php
        if ($_POST) {
            $col1 = $_POST['col1'];
            $col2 = $_POST['col2'];
            $contingencyTable = [];
            foreach ($rows as $row) {
                $contingencyTable[$row[array_search($col1, $headers)]][$row[array_search($col2, $headers)]] = 
                    ($contingencyTable[$row[array_search($col1, $headers)]][$row[array_search($col2, $headers)]] ?? 0) + 1;
            }
            echo "<table class='table table-striped'>";
            foreach ($contingencyTable as $key1 => $values) {
                echo "<tr><td>$key1</td>";
                foreach ($values as $key2 => $count) {
                    echo "<td>$key2: $count</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
    </div>

    <!-- Gráficos -->
    <div class="tab-pane fade" id="charts">
        <h2>Gráficos</h2>
        <canvas id="chartCanvas"></canvas>
        <script>
            const ctx = document.getElementById('chartCanvas').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($rows, 0)); ?>,
                    datasets: [{
                        label: 'Ejemplo de Gráfico',
                        data: <?php echo json_encode(array_column($rows, 1)); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                }
            });
        </script>
    </div>

    <!-- Correlaciones -->
    <div class="tab-pane fade" id="correlations">
        <h2>Correlaciones</h2>
        <table class="table table-striped">
           
        </table>
    </div>
</div>




    echo "Fin del script analysis.php<br>";
} catch (Exception $e) {
    echo "Error en analysis.php: " . $e->getMessage();
}
