<?php
// app/controllers/AsistenciaController.php

class AsistenciaController extends Controller 
{
    private $pdo;
    
    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }
    
    // Mostrar la pantalla del lector (PÚBLICO - no requiere sesión)
    public function registro(): void 
    {
        $this->view('asistencia/registro_asistencia', [], 'lector');
    }
    
    // Procesar marcación (AJAX) (PÚBLICO - no requiere sesión)
    public function marcar(): void 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
            exit;
        }
        
        $dni = $_POST['dni'] ?? '';
        
        if (empty($dni)) {
            echo json_encode(['ok' => false, 'mensaje' => 'DNI requerido']);
            exit;
        }
        
        $stmt = $this->pdo->prepare("
            SELECT e.*, c.nombre_cargo, t.nombre_turno, t.hora_inicio, t.hora_salida, t.tolerancia_minutos
            FROM EMPLEADO e 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            INNER JOIN TURNO t ON e.id_turno = t.id_turno 
            WHERE e.dni = :dni 
            LIMIT 1
        ");
        $stmt->execute(['dni' => $dni]);
        $empleado = $stmt->fetch();
        
        if (!$empleado) {
            echo json_encode([
                'ok' => false, 
                'mensaje' => 'Empleado no encontrado',
                'tipo' => 'no_encontrado'
            ]);
            exit;
        }
        
        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');
        
        $stmt = $this->pdo->prepare("
            SELECT * FROM ASISTENCIA 
            WHERE id_empleado = :id AND fecha = :fecha 
            LIMIT 1
        ");
        $stmt->execute(['id' => $empleado['id_empleado'], 'fecha' => $hoy]);
        $asistenciaHoy = $stmt->fetch();
        
        if ($asistenciaHoy) {
            if (empty($asistenciaHoy['hora_salida'])) {
                $stmt = $this->pdo->prepare("
                    UPDATE ASISTENCIA 
                    SET hora_salida = :hora 
                    WHERE id_asistencia = :id
                ");
                $stmt->execute(['hora' => $ahora, 'id' => $asistenciaHoy['id_asistencia']]);
                
                echo json_encode([
                    'ok' => true,
                    'tipo' => 'salida',
                    'mensaje' => '¡Hasta luego!',
                    'empleado' => [
                        'nombre' => $empleado['nombre'],
                        'apellido' => $empleado['apellido'],
                        'cargo' => $empleado['nombre_cargo'],
                        'hora' => $ahora
                    ]
                ]);
                exit;
            } else {
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'Ya registraste entrada y salida hoy',
                    'tipo' => 'duplicado'
                ]);
                exit;
            }
        }
        
        $estado = $this->determinarEstado($empleado['hora_inicio'], $empleado['tolerancia_minutos'], $ahora);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO ASISTENCIA (id_empleado, fecha, hora_entrada, estado) 
            VALUES (:id, :fecha, :hora, :estado) 
        ");
        $stmt->execute([
            'id'     => $empleado['id_empleado'],
            'fecha'  => $hoy,
            'hora'   => $ahora,
            'estado' => $estado
        ]);
        
        $mensaje = match($estado) {
            'tardanza' => '⚠ Llegaste tarde',
            'asistio' => 'Asistencia registrada',
            default => 'Registro exitoso'
        };
        
        echo json_encode([
            'ok' => true,
            'tipo' => 'entrada',
            'mensaje' => $mensaje,
            'empleado' => [
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'cargo' => $empleado['nombre_cargo'],
                'hora' => $ahora,
                'estado' => $estado
            ]
        ]);
        exit;
    }
    
    // Ver últimas asistencias (AJAX)
    public function ultimas(): void
    {
        $hoy = date('Y-m-d');
        $stmt = $this->pdo->prepare("
            SELECT a.*, e.nombre, e.apellido, c.nombre_cargo 
            FROM ASISTENCIA a 
            INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado 
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
            WHERE a.fecha = :hoy
            ORDER BY a.id_asistencia DESC 
            LIMIT 10
        ");
        $stmt->execute(['hoy' => $hoy]);
        $asistencias = $stmt->fetchAll();
        
        foreach ($asistencias as $a) {
            $estadoClass = match($a['estado']) {
                'asistio' => 'badge-success',
                'tardanza' => 'badge-warning',
                'falto' => 'badge-danger',
                default => ''
            };
            $estadoTexto = match($a['estado']) {
                'asistio' => 'Asistió',
                'tardanza' => 'Tardanza',
                'falto' => 'Faltó',
                default => $a['estado']
            };
            
            echo '<div class="asistencia-item">';
            echo '<span>' . htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) . '</span>';
            echo '<span>' . htmlspecialchars($a['nombre_cargo']) . '</span>';
            echo '<span>' . ($a['hora_entrada'] ?? '—') . '</span>';
            echo '<span class="' . $estadoClass . '">' . $estadoTexto . '</span>';
            echo '</div>';
        }
        exit;
    }
    

    // Determinar estado
    private function determinarEstado(string $horaInicio, int $toleranciaMinutos, string $horaLlegada): string
    {
        $horaLimite = date('H:i:s', strtotime($horaInicio . ' + ' . $toleranciaMinutos . ' minutes'));
        
        if ($horaLlegada <= $horaLimite) {
            return 'asistio';
        } else {
            return 'tardanza';
        }
    }
    

public function ver(): void 
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
    
    $hoy = date('Y-m-d');
    $ahora = date('H:i:s');
    
    // =============================================
    // 1. PRIMERO: Actualizar estados (ANTES de consultar)
    // =============================================
    
    // Marcar faltos (solo después de las 5 PM)
    if ($ahora >= '17:00:00') {
        $this->marcarFaltasSilencioso();
    }
    
    // Marcar salidas automáticas (solo después de las 6 PM)
    if ($ahora >= '18:00:00') {
        $this->marcarSalidasAutomaticasSilencioso();
    }
    
    // =============================================
    // 2. SEGUNDO: Consultar los datos YA actualizados
    // =============================================
    
    $stmt = $this->pdo->prepare("
        SELECT 
            e.id_empleado, e.nombre, e.apellido, e.dni, e.telefono,
            c.nombre_cargo, t.nombre_turno,
            a.id_asistencia, a.hora_entrada, a.hora_salida, a.estado, a.fecha
        FROM EMPLEADO e 
        INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
        INNER JOIN TURNO t ON e.id_turno = t.id_turno 
        LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :hoy
        ORDER BY 
    CASE WHEN a.hora_entrada IS NULL THEN 1 ELSE 0 END,
    a.hora_entrada DESC    
    ");
    $stmt->execute(['hoy' => $hoy]);
    $empleados = $stmt->fetchAll();
    
    // 3. Mostrar vista
    $this->view('asistencia/ver', [
        'empleados' => $empleados
    ], 'dashboard');
}

public function marcarFaltasSilencioso(): void
{
    $hoy = date('Y-m-d');
    
    // Buscar empleados que NO tienen registro HOY
    $stmt = $this->pdo->prepare("
        SELECT e.id_empleado 
        FROM EMPLEADO e 
        WHERE e.id_empleado NOT IN (
            SELECT id_empleado FROM ASISTENCIA WHERE fecha = :hoy
        )
    ");
    $stmt->execute(['hoy' => $hoy]);
    $faltantes = $stmt->fetchAll();
    
    // Insertar "falto" para cada uno (SIN hora_entrada)
    foreach ($faltantes as $emp) {
        $insert = $this->pdo->prepare("
            INSERT INTO ASISTENCIA (id_empleado, fecha, hora_entrada, estado) 
            VALUES (:id, :fecha, NULL, 'falto')
        ");
        $insert->execute([
            'id' => $emp['id_empleado'],
            'fecha' => $hoy
        ]);
    }
}

public function marcarSalidasAutomaticasSilencioso(): void
{
    $hoy = date('Y-m-d');
    $horaFinLaboral = '18:00:00';
    
    // Actualizar empleados que marcaron entrada pero NO salida
    $stmt = $this->pdo->prepare("
        UPDATE ASISTENCIA a
        INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
        INNER JOIN TURNO t ON e.id_turno = t.id_turno
        SET a.hora_salida = :hora_salida
        WHERE a.fecha = :fecha 
          AND a.hora_entrada IS NOT NULL 
          AND a.hora_salida IS NULL
          AND a.estado IN ('asistio', 'tardanza')
    ");
    $stmt->execute([
        ':hora_salida' => $horaFinLaboral,
        ':fecha' => $hoy
    ]);
}

    // Uso esta funcion para las pruenas manuales
    // Marcar salida automática a los que no registraron
public function marcarSalidasAutomaticas(): void
{
    $hoy = date('Y-m-d');
    $ahora = date('H:i:s');
    
    // Solo después de las 6 PM
    if ($ahora < '18:00:00') {
        echo "Aún no es hora de salida.";
        return;
    }
    
    // Empleados que marcaron entrada pero NO salida
    $stmt = $this->pdo->prepare("
        SELECT a.id_asistencia, t.hora_salida
        FROM ASISTENCIA a
        INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
        INNER JOIN TURNO t ON e.id_turno = t.id_turno
        WHERE a.fecha = :hoy 
          AND a.hora_entrada IS NOT NULL 
          AND a.hora_salida IS NULL
    ");
    $stmt->execute(['hoy' => $hoy]);
    $pendientes = $stmt->fetchAll();
    
    foreach ($pendientes as $p) {
        $update = $this->pdo->prepare("
            UPDATE ASISTENCIA 
            SET hora_salida = :hora 
            WHERE id_asistencia = :id
        ");
        $update->execute([
            'hora' => $p['hora_salida'], // Hora fin del turno
            'id' => $p['id_asistencia']
        ]);
    }
    
    echo "Salidas procesadas: " . count($pendientes);

}



// Metodo para actualizar la tabla automaticamente

public function obtenerDatos(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(401);
        exit;
    }
    
    $hoy = date('Y-m-d');
    $ahora = date('H:i:s');
    
    // Actualizar estados
    if ($ahora >= '17:00:00') {
        $this->marcarFaltasSilencioso();
    }
    
    if ($ahora >= '18:00:00') {
        $this->marcarSalidasAutomaticasSilencioso();
    }
    
    // Consultar datos
    $stmt = $this->pdo->prepare("
        SELECT 
            e.id_empleado, e.nombre, e.apellido, e.dni,
            c.nombre_cargo, t.nombre_turno,
            a.hora_entrada, a.hora_salida, a.estado
        FROM EMPLEADO e 
        INNER JOIN CARGO c ON e.id_cargo = c.id_cargo 
        INNER JOIN TURNO t ON e.id_turno = t.id_turno 
        LEFT JOIN ASISTENCIA a ON e.id_empleado = a.id_empleado AND a.fecha = :hoy
        CASE WHEN a.hora_entrada IS NULL THEN 1 ELSE 0 END,
    a.hora_entrada DESC 
    ");
    
    $stmt->execute(['hoy' => $hoy]);
    $empleados = $stmt->fetchAll();

    // Devolver SOLO datos (JSON)
    header('Content-Type: application/json');
    echo json_encode($empleados);
    exit;
    
}

}