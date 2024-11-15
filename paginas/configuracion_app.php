<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="js/styls/dise.css">

<style type="text/css">
    .sinborde {
        border: 0;
    }
</style>
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                       <!-- Medal Card -->
<div class="col-md-12 col-12">
    <div class="card card-congratulation-medal">
        <div class="card-body">
            <input style="background-color:transparent; display:none;" type="date" size="5" class="form-control form-control-sm invoice-edit-input date-picker" name="cmbhasta6" id="cmbhasta6" value="<?php echo date("Y-m-d"); ?>">
            <input style="background-color:transparent; display:none;" type="date" size="5" class="form-control form-control-sm invoice-edit-input date-picker" name="cmbdesde6" id="cmbdesde6" value="2022-08-01">
            <p class="card-text font-small-3">Facturación del Día</p>
            <!-- Formulario para cargar archivo -->
            <form id="uploadForm" method="POST" action="upload.php" enctype="multipart/form-data" class="mb-4">
                <div class="input-group">
                    <input type="file" name="datafile" class="form-control" accept=".csv, .xls, .xlsx" required>
                    <button class="btn btn-primary" type="submit">Cargar Archivo</button>
                </div>
            </form>
            <!-- Mensaje de Cargando -->
            <div id="loadingMessage" style="display: none; text-align: center; margin-top: 10px;">
    <img src="img_icons/running_loader.gif" alt="Cargando..." style="width: 150px; height: 150px;">
    <p style="margin: 5px 0 0 0;">Cargando, por favor espera...</p>
</div>
            </div>
            <img src="app-assets/images/illustration/badge.svg" class="congratulation-medal" style="height: 52px;" />
        </div>
    </div>
</div>


                        <div style="margin-left: 100px;" class="col-xl-10 col-md-6 col-12">
                            <div class="card card-statistics">
                                <div id="analysis" class="d-none">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" style="color: teal"> Información Básica</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="trend-tab" data-bs-toggle="tab" data-bs-target="#trend" type="button" role="tab" style="color: teal">Medidas de Tendencia</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tables-tab" data-bs-toggle="tab" data-bs-target="#tables" type="button" role="tab" style="color: teal">Tablas de Contingencia</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="charts-tab" data-bs-toggle="tab" data-bs-target="#charts" type="button" role="tab" style="color: teal">Gráficos</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="correlation-tab" data-bs-toggle="tab" data-bs-target="#correlation" type="button" role="tab" style="color: teal">Correlaciones</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">

                                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                            <h4 class="mt-3" style="margin-left: 120px;">Información Básica</h4>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label style="margin-left: 120px;" for="basicInfoSelect" class="form-label">Seleccione la información a mostrar:</label>
                                                    <select style="margin-left: 120px;" id="basicInfoSelect" class="form-select">
                                                        <option value="showAll">Mostrar Todos los Datos</option>
                                                        <option value="info">Información General</option>
                                                        <option value="size">Tamaño del Archivo</option>
                                                        <option value="nullValues">Valores Nulos/Vacíos</option>
                                                        <option value="columnsCount">Cantidad de Columnas</option>
                                                        <option value="head">Primeras Filas</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="basicInfoContent" class="mt-3"></div>
                                        </div>

                                        <div class="tab-pane fade" id="trend" role="tabpanel">
                                            <h4 class="mt-3" style="margin-left: 120px;">Medidas de Tendencia</h4>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label style="margin-left: 120px;" for="trendSelect" class="form-label">Seleccione la columna para analizar:</label>
                                                    <select style="margin-left: 120px;" id="trendSelect" class="form-select"></select>
                                                </div>
                                            </div>
                                            <div style="margin-left: 120px;" id="trendContent" class="mt-3"></div>
                                        </div>

                                        <div class="tab-pane fade" id="tables" role="tabpanel">
                                            <h4 style="margin-left: 10px;" class="mt-3">Tablas de Contingencia</h4>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label style="margin-left: 30px;" for="field1" class="form-label">Seleccione la primera columna:</label>
                                                    <select style="margin-left: 30px; width:412px" id="field1" class="form-select"></select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="field2" class="form-label">Seleccione la segunda columna:</label>
                                                    <select id="field2" style=" width:412px" class="form-select"></select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-8">
                                                    <button id="generateTable" class="btn btn-primary">Generar Tabla de Contingencia</button>
                                                </div>
                                            </div>
                                            <div id="tablesInfo" class="mt-3"></div>
                                        </div>

                                        <div class="tab-pane fade" id="charts" role="tabpanel">
                                            <h4 class="mt-3" style="margin-left: 20px;">Gráficos</h4>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label style="margin-left: 20px;" for="chartType" class="form-label">Tipo de gráfico:</label>
                                                    <select style="margin-left: 20px;" id="chartType" class="form-select">
                                                        <option value="bar">Barra</option>
                                                        <option value="line">Línea</option>
                                                        <option value="pie">Circular</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="xAxis" class="form-label">Eje X:</label>
                                                    <select id="xAxis" class="form-select"></select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="yAxis" class="form-label">Eje Y:</label>
                                                    <select id="yAxis" class="form-select"></select>
                                                </div>
                                            </div>
                                            <canvas id="chartCanvas" width="500" height="300"></canvas>

                                        </div>


                                        <div class="tab-pane fade" id="correlation" role="tabpanel">
                                            <h4 style="margin-left: 20px;" class="mt-3">Correlaciones</h4>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label style="margin-left: 20px;" for="correlationVariable1" class="form-label">Seleccione la primera variable:</label>
                                                    <select id="correlationVariable1" class="form-select"></select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="correlationVariable2" class="form-label">Seleccione la segunda variable:</label>
                                                    <select id="correlationVariable2" class="form-select"></select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <button id="calculateCorrelation" class="btn btn-primary">Calcular Correlación</button>
                                                </div>
                                            </div>
                                            <div style="margin-left: 20px; font-size: 22px" id="correlationInfo"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
            </div>
            </section>
        </div>
    </div>
    </div>


    <script src="js/styls/funciones_ok.js"></script>
    <script src="js/styls/funciones.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTableHead').DataTable();
            $('#dataTableFull').DataTable();
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const field1 = document.getElementById("field1");
            const field2 = document.getElementById("field2");
            const generateTable = document.getElementById("generateTable");
            const tablesInfo = document.getElementById("tablesInfo");
            async function loadColumns() {
                const response = await fetch("columnastablacontingencia.php");
                const result = await response.json();

                if (result.status === "success" && result.columns) {
                    field1.innerHTML = `<option value="">Seleccione una columna</option>`;
                    field2.innerHTML = `<option value="">Seleccione una columna</option>`;
                    result.columns.forEach(column => {
                        field1.add(new Option(column, column));
                        field2.add(new Option(column, column));
                    });
                } else {
                    field1.innerHTML = `<option value="">No hay columnas disponibles</option>`;
                    field2.innerHTML = `<option value="">No hay columnas disponibles</option>`;
                }
            }
            generateTable.addEventListener("click", async function() {
                const col1 = field1.value;
                const col2 = field2.value;

                if (!col1 || !col2) {
                    tablesInfo.innerHTML = `<p class="text-danger">Debe seleccionar ambas columnas para generar la tabla de contingencia.</p>`;
                    return;
                }

                const response = await fetch("contingenciatabla.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        col1,
                        col2
                    })
                });

                const result = await response.json();
                if (result.status === "success") {
                    tablesInfo.innerHTML = result.content;
                    $('#TabladeContingencia').DataTable();
                } else {
                    tablesInfo.innerHTML = `<p class="text-danger">${result.message}</p>`;
                }
            });

            loadColumns();
        });
    </script>