$(document).ready(function () {
    // Formulario de carga de archivo
    $('#uploadForm').submit(function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#analysis').removeClass('d-none');
                    $('#trendInfo').html(data.trendInfo);
                    $('#tablesInfo').html(data.tablesInfo);
                    $('#chartsInfo').html(data.chartsInfo);
                    $('#correlationInfo').html(data.correlationInfo);

                    // Inicializar DataTables en la tabla de contingencia
                    $('#tables-tab').on('shown.bs.tab', function () {
                        if (!$.fn.DataTable.isDataTable('#TabladeContingencia')) {
                            $('#TabladeContingencia').DataTable({
                                paging: true,
                                searching: true,
                                ordering: true,
                                language: {
                                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                                }
                            });
                        }
                    });
                } else {
                    alert(data.message);
                }
            },
            error: function () {
                alert('Error al procesar el archivo.');
            }
        });
    });
});



$(document).ready(function () {
    // Formulario de carga de archivo
    $('#uploadForm').submit(function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#analysis').removeClass('d-none');
                    $('#basicInfo').html(data.basicInfo);
                    $('#trendInfo').html(data.trendInfo);
                    $('#tablesInfo').html(data.tablesInfo);
                    $('#chartsInfo').html(data.chartsInfo);
                    $('#correlationInfo').html(data.correlationInfo);

                    // Inicializar DataTables en la tabla de contingencia
                    $('#tables-tab').on('shown.bs.tab', function () {
                        if (!$.fn.DataTable.isDataTable('#TabladeContingencia')) {
                            $('#TabladeContingencia').DataTable({
                                paging: true,
                                searching: true,
                                ordering: true,
                                language: {
                                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                                }
                            });
                        }
                    });

                    // Inicializar DataTables en la tabla de correlaciones
                    $('#correlation-tab').on('shown.bs.tab', function () {
                        if (!$.fn.DataTable.isDataTable('#correlationTable')) {
                            $('#correlationTable').DataTable({
                                paging: true,
                                searching: true,
                                ordering: true,
                                language: {
                                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                                }
                            });
                        }
                    });
                } else {
                    alert(data.message);
                }
            },
            error: function () {
                alert('Error al procesar el archivo.');
            }
        });
    });
});