//INFORMACIÓN BASICA
document.addEventListener("DOMContentLoaded", function () {
    const basicInfoSelect = document.getElementById("basicInfoSelect");
    const basicInfoContent = document.getElementById("basicInfoContent");

    let dataTableInstance;

    basicInfoSelect.addEventListener("change", async function () {
        const selectedOption = this.value;

        const response = await fetch("procesarinfobasica.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ option: selectedOption })
        });

        const result = await response.json();
        if (result.status === "success") {
            basicInfoContent.innerHTML = result.content;

            if (selectedOption === "showAll" || selectedOption === "head") {
                if (dataTableInstance) {
                    dataTableInstance.destroy();
                }

                dataTableInstance = $('#dataTable').DataTable();
            }
        } else {
            basicInfoContent.innerHTML = `<p class="text-danger">${result.message}</p>`;
        }
    });

    // Disparar el cambio inicial
    basicInfoSelect.dispatchEvent(new Event("change"));
});



//GRAFICO
document.addEventListener("DOMContentLoaded", function() {
    let chart;
    const chartTypeSelect = document.getElementById("chartType");
    const xAxisSelect = document.getElementById("xAxis");
    const yAxisSelect = document.getElementById("yAxis");
    const chartCanvas = document.getElementById("chartCanvas").getContext("2d");

    const columnData = {
        columns: [],
        data: []
    };

    const colors = [
        "rgba(255, 99, 132, 0.2)", // Rojo
        "rgba(54, 162, 235, 0.2)", // Azul
        "rgba(255, 206, 86, 0.2)", // Amarillo
        "rgba(75, 192, 192, 0.2)", // Verde
        "rgba(153, 102, 255, 0.2)", // Púrpura
        "rgba(255, 159, 64, 0.2)" // Naranja
    ];
    const borderColors = [
        "rgba(255, 99, 132, 1)",
        "rgba(54, 162, 235, 1)",
        "rgba(255, 206, 86, 1)",
        "rgba(75, 192, 192, 1)",
        "rgba(153, 102, 255, 1)",
        "rgba(255, 159, 64, 1)"
    ];

    function generateChart() {
        if (chart) chart.destroy(); // Destruye el gráfico anterior si existe

        const xAxis = xAxisSelect.value;
        const yAxis = yAxisSelect.value;
        const chartType = chartTypeSelect.value;

        const labels = columnData.data.map(row => row[xAxis]);
        const data = columnData.data.map(row => parseFloat(row[yAxis]) || row[yAxis]);

        console.log("Datos del eje X:", labels);
        console.log("Datos del eje Y:", data);

        const backgroundColor = data.map((_, index) => colors[index % colors.length]);
        const borderColor = data.map((_, index) => borderColors[index % borderColors.length]);

        chart = new Chart(chartCanvas, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: yAxis,
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    chartTypeSelect.addEventListener("change", generateChart);
    xAxisSelect.addEventListener("change", generateChart);
    yAxisSelect.addEventListener("change", generateChart);

    document.getElementById("uploadForm").addEventListener("submit", async function(event) {
        event.preventDefault();
    
        // Mostrar mensaje de cargando
        const loadingMessage = document.getElementById("loadingMessage");
        loadingMessage.style.display = "block";
    
        // Deshabilitar el botón para evitar múltiples envíos
        const submitButton = this.querySelector("button[type='submit']");
        submitButton.disabled = true;
    
        // Limpiar temporales antes de cargar el nuevo archivo
        clearTemporaryData();
    
        try {
            // Enviar el formulario con AJAX
            const formData = new FormData(this);
            const response = await fetch("upload.php", {
                method: "POST",
                body: formData
            });
            const result = await response.json();
    
            // Procesar la respuesta
            if (result.columns && result.data) {
                // Aquí puedes manejar los datos resultantes, como actualizar tus selectores o variables
                updateInterfaceWithNewData(result);
                alert("Archivo cargado exitosamente.");
            } else {
                alert(result.message || "Error al cargar el archivo.");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Error al procesar la solicitud.");
        } finally {
            loadingMessage.style.display = "none";
            submitButton.disabled = false;
        }
    });
    
});
function clearTemporaryData() {
    const basicInfoContent = document.getElementById("basicInfoContent");
    if (basicInfoContent) basicInfoContent.innerHTML = "";

    const xAxisSelect = document.getElementById("xAxisSelect");
    const yAxisSelect = document.getElementById("yAxisSelect");
    if (xAxisSelect) xAxisSelect.innerHTML = "";
    if (yAxisSelect) yAxisSelect.innerHTML = "";

}


function updateInterfaceWithNewData(result) {
    const xAxisSelect = document.getElementById("xAxisSelect");
    const yAxisSelect = document.getElementById("yAxisSelect");

    result.columns.forEach(col => {
        if (xAxisSelect) xAxisSelect.add(new Option(col, col));
        if (yAxisSelect) yAxisSelect.add(new Option(col, col));
    });

   
}

// MEDIDAS DE TENDENCIA 
document.addEventListener("DOMContentLoaded", function() {
    const trendSelect = document.getElementById("trendSelect");
    const trendContent = document.getElementById("trendContent");
    trendSelect.addEventListener("change", async function() {
        const selectedColumn = this.value;

        if (selectedColumn) {
            const response = await fetch("medidadetendenciadata.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    column: selectedColumn
                })
            });

            const result = await response.json();
            if (result.status === "success") {
                trendContent.innerHTML = result.content;
            } else {
                trendContent.innerHTML = `<p class="text-danger">${result.message}</p>`;
            }
        }
    });

    async function loadColumns() {
        const response = await fetch("fetch_columns.php");
        const result = await response.json();

        if (result.status === "success" && result.columns) {
            trendSelect.innerHTML = `<option value="">Seleccione una columna</option>`;
            result.columns.forEach(column => {
                trendSelect.add(new Option(column, column));
            });
        } else {
            trendSelect.innerHTML = `<option value="">No hay columnas disponibles</option>`;
        }
    }

    loadColumns();
});

//CORRELACIONES
document.addEventListener("DOMContentLoaded", function() {
    const correlationVariable1 = document.getElementById("correlationVariable1");
    const correlationVariable2 = document.getElementById("correlationVariable2");
    const calculateCorrelation = document.getElementById("calculateCorrelation");
    const correlationInfo = document.getElementById("correlationInfo");
    async function loadQuantitativeColumns() {
        const response = await fetch("columnascorrelaciones.php");
        const result = await response.json();

        if (result.status === "success" && result.columns) {
            correlationVariable1.innerHTML = `<option value="">Seleccione una variable</option>`;
            correlationVariable2.innerHTML = `<option value="">Seleccione una variable</option>`;
            result.columns.forEach(column => {
                correlationVariable1.add(new Option(column, column));
                correlationVariable2.add(new Option(column, column));
            });
        } else {
            correlationVariable1.innerHTML = `<option value="">No hay variables cuantitativas disponibles</option>`;
            correlationVariable2.innerHTML = `<option value="">No hay variables cuantitativas disponibles</option>`;
        }
    }

    calculateCorrelation.addEventListener("click", async function() {
        const variable1 = correlationVariable1.value;
        const variable2 = correlationVariable2.value;

        if (!variable1 || !variable2) {
            correlationInfo.innerHTML = `<p class="text-danger">Debe seleccionar ambas variables para calcular la correlación.</p>`;
            return;
        }

        const response = await fetch("calcularcorrelaciones.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                variable1,
                variable2
            })
        });

        const result = await response.json();
        if (result.status === "success") {
            correlationInfo.innerHTML = `<p class="text-success">Correlación (${variable1} vs ${variable2}): ${result.correlation.toFixed(4)}</p>`;
        } else {
            correlationInfo.innerHTML = `<p class="text-danger">${result.message}</p>`;
        }
    });

    loadQuantitativeColumns();
});