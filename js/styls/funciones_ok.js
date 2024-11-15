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