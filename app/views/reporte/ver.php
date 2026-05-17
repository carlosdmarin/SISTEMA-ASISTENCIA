<div class="reportes-container">
    <h1><i class="fa-solid fa-chart-line"></i> Reporte de Asistencias</h1>
    
    <!-- Filtros -->
    <div class="filtros-card">
        <div class="filtros-header">
            <i class="fa-solid fa-filter"></i>
            <span>Filtros</span>
        </div>
        <div class="filtros-body">
            <div class="filtro-group">
                <label for="fecha_inicio">Fecha Inicio</label>
                <input type="date" id="fecha_inicio" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="filtro-group">
                <label for="fecha_fin">Fecha Fin</label>
                <input type="date" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="filtro-group">
                <label>&nbsp;</label>
                <button id="btn-filtrar" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                </button>
            </div>
        </div>
    </div>
    
    <!-- Botones de exportación -->
    <div class="export-buttons">
        <button id="btn-pdf" class="btn btn-pdf">
            <i class="fa-solid fa-file-pdf"></i> Exportar PDF
        </button>
        <button id="btn-excel" class="btn btn-excel">
            <i class="fa-solid fa-file-excel"></i> Exportar Excel
        </button>
        <button id="btn-imprimir" class="btn btn-print">
            <i class="fa-solid fa-print"></i> Imprimir
        </button>
    </div>
    
    <!-- Tabla de resultados -->
    <div class="table-responsive">
        <table class="table-reportes" id="tabla-reportes">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Empleado</th>
                    <th>DNI</th>
                    <th>Cargo</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="reporte-tbody">
                <tr>
                    <td colspan="7" style="text-align: center;">Seleccione un rango de fechas</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Función para cargar el reporte
function cargarReporte() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    
    fetch(`<?php echo BASE_URL; ?>/reporte/obtenerPorRango?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('reporte-tbody');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No hay registros en este rango</td></tr>';
                return;
            }
            
            let html = '';
            data.forEach(reg => {
                let estadoHtml = '';
                if (reg.estado === 'asistio') {
                    estadoHtml = '<span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Asistió</span>';
                } else if (reg.estado === 'tardanza') {
                    estadoHtml = '<span class="badge badge-warning"> <i class="fa-solid fa-hourglass-half"></i> Tardanza</span>';
                } else if (reg.estado === 'falto') {
                    estadoHtml = '<span class="badge badge-danger">❌ Faltó</span>';
                } else {
                    estadoHtml = '<span class="badge badge-sinmarcar">⬜ Sin marcar</span>';
                }
                
                html += `
                    <tr>
                        <td>${reg.fecha}</td>
                        <td>${reg.nombre} ${reg.apellido}</td>
                        <td>${reg.dni}</td>
                        <td>${reg.nombre_cargo}</td>
                        <td>${reg.hora_entrada || '—'}</td>
                        <td>${reg.hora_salida || '—'}</td>
                        <td>${estadoHtml}</td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        })
        .catch(error => console.log('Error:', error));
}

// Inicializar cuando la página esté lista
document.addEventListener('DOMContentLoaded', function() {
    cargarReporte();
    
    const btnFiltrar = document.getElementById('btn-filtrar');
    const btnImprimir = document.getElementById('btn-imprimir');
    
    if (btnFiltrar) btnFiltrar.addEventListener('click', cargarReporte);
    if (btnImprimir) btnImprimir.addEventListener('click', function() {
        window.print();
    });
});
</script>