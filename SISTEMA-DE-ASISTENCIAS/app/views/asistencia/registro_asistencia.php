<!-- FONDO CORPORATIVO -->
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 flex items-center justify-center p-6 relative">
    
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="relative z-10 w-full max-w-md">
        
        <!-- CABECERA -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-qrcode text-white text-lg"></i>
                </div>
                <h1 class="text-white font-bold text-2xl tracking-tight">REGISTRO DE ASISTENCIA</h1>
            </div>
            <div class="w-20 h-0.5 bg-blue-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- RELOJ DIGITAL -->
        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 mb-6 border border-gray-700/50 shadow-xl">
            <p id="date" class="text-gray-400 text-sm font-medium text-center mb-3 tracking-wide uppercase">--/--/----</p>
          <p id="clock" class="text-7xl font-bold text-white text-center tracking-wider" style="font-family: 'Poppins', monospace;">--:--:--</p>
            <div class="flex items-center justify-center gap-2 mt-5">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-green-500 text-xs font-medium uppercase tracking-wider">Sistema activo</span>
            </div>
        </div>

        <!-- CAMPO PARA LECTOR -->
        <input type="password" 
               id="lectorDni" 
               class="absolute opacity-0" 
               style="top:0;left:0;width:1px;height:1px;"
               autofocus 
               autocomplete="off">

        <!-- MENSAJE DE ESPERA -->
        <div class="text-center mb-6">
            <p class="text-gray-500 text-sm font-medium">
                <i class="far fa-hand-point-up mr-2"></i>Acerque su DNI al lector
            </p>
        </div>

        <!-- RESULTADO -->
        <div id="resultCard" class="hidden mb-6"></div>

        <!-- ÚLTIMOS REGISTROS -->
        <div class="bg-gray-800/60 backdrop-blur-md rounded-2xl p-6 border border-gray-700/50">
            <h3 class="text-gray-300 text-sm font-semibold uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fas fa-list text-blue-400"></i> Últimos registros
            </h3>
            <div id="listaAsistencias" class="text-gray-400 text-sm space-y-2">
                <p class="text-center">Sin registros recientes</p>
            </div>
        </div>

        <!-- BOTÓN REGRESAR -->
        <div class="mt-6 text-center">
            <a href="<?php echo BASE_URL; ?>" 
               class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-300 text-sm font-medium transition-colors duration-200">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ========== RELOJ ==========
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = 
            `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}:${now.getSeconds().toString().padStart(2,'0')}`;
        document.getElementById('date').textContent = now.toLocaleDateString('es-PE', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }
    updateClock();
    setInterval(updateClock, 1000);
    
    // ========== FOCO SIEMPRE EN EL INPUT ==========
    const lectorInput = document.getElementById('lectorDni');
    lectorInput.focus();
    document.addEventListener('click', function() {
        lectorInput.focus();
    });
    
    // ========== SONIDOS ==========
    function crearSonido(tipo) {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain); gain.connect(ctx.destination);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        
        if (tipo === 'exito') {
            osc.type = 'sine';
            osc.frequency.setValueAtTime(523, ctx.currentTime);
            osc.frequency.setValueAtTime(659, ctx.currentTime + 0.1);
            osc.frequency.setValueAtTime(784, ctx.currentTime + 0.2);
        } else {
            osc.type = 'square';
            osc.frequency.setValueAtTime(200, ctx.currentTime);
            osc.frequency.setValueAtTime(150, ctx.currentTime + 0.15);
        }
        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.4);
    }
    
    // ========== MARCAR ASISTENCIA ==========
    function marcarAsistencia(dni) {
        const card = document.getElementById('resultCard');
        card.classList.remove('hidden');
        card.innerHTML = `
            <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-6 text-center border border-gray-700/50">
                <div class="flex items-center justify-center gap-3 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-blue-400"></i>
                    <span>Procesando...</span>
                </div>
            </div>`;
        
        fetch('<?php echo BASE_URL; ?>/asistencia/marcar', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'dni=' + encodeURIComponent(dni)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                crearSonido('exito');
                
                if (data.tipo === 'salida') {
                    card.innerHTML = `
                        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 text-center border border-blue-500/30">
                            <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-door-open text-blue-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">Salida registrada</p>
                            <h2 class="text-white text-2xl font-bold">${data.empleado.nombre} ${data.empleado.apellido}</h2>
                            <p class="text-gray-400 text-sm mt-1">${data.empleado.cargo}</p>
                            <p class="text-blue-400 text-3xl font-bold mt-4" style="font-family: 'SF Mono', 'Courier New', monospace;">${data.empleado.hora}</p>
                        </div>`;
                } else {
                    const isTarde = data.empleado.estado === 'tardanza';
                    card.innerHTML = `
                        <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 text-center border ${isTarde ? 'border-amber-500/30' : 'border-emerald-500/30'}">
                            <div class="w-16 h-16 ${isTarde ? 'bg-amber-500/20' : 'bg-emerald-500/20'} rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas ${isTarde ? 'fa-exclamation-triangle text-amber-400' : 'fa-check-circle text-emerald-400'} text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">Entrada registrada</p>
                            <h2 class="text-white text-2xl font-bold">${data.empleado.nombre} ${data.empleado.apellido}</h2>
                            <p class="text-gray-400 text-sm mt-1">${data.empleado.cargo}</p>
                            <p class="${isTarde ? 'text-amber-400' : 'text-emerald-400'} text-3xl font-bold mt-4" style="font-family: 'SF Mono', 'Courier New', monospace;">${data.empleado.hora}</p>
                            <span class="inline-block mt-4 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider ${isTarde ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400'}">
                                ${isTarde ? 'Tardanza' : 'A tiempo'}
                            </span>
                        </div>`;
                }
            } else {
                crearSonido('error');
                card.innerHTML = `
                    <div class="bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 text-center border border-red-500/30">
                        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-400 text-xs uppercase tracking-widest mb-2">Error</p>
                        <h3 class="text-white text-lg font-bold">${data.mensaje}</h3>
                    </div>`;
            }
            setTimeout(() => card.classList.add('hidden'), 5000);
            lectorInput.focus();
        });
    }
    
    // ========== LECTOR - DETECTAR ENTER ==========
    lectorInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const dni = this.value.trim();
            if (dni.length >= 8) {
                marcarAsistencia(dni);
                this.value = '';
            }
        }
    });

    // ========== CARGAR ÚLTIMAS ASISTENCIAS ==========
    fetch('<?php echo BASE_URL; ?>/asistencia/ultimas')
        .then(r => r.text())
        .then(html => document.getElementById('listaAsistencias').innerHTML = html);
});
</script>