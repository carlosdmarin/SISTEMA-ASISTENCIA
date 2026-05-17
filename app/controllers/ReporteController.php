<?php
// app/controllers/ReporteController.php

class ReporteController extends Controller 
{
    private $pdo;
    
    public function __construct() 
    {
        $this->pdo = Database::getConnection();
    }
    
    // Mostrar la página de reportes
    public function ver(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->view('reporte/ver', [], 'dashboard');
    }
    
    // Obtener asistencias por rango de fechas (AJAX)
    public function obtenerPorRango(): void 
    {
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            exit;
        }
        
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        $stmt = $this->pdo->prepare("
            SELECT 
                a.fecha,
                e.nombre,
                e.apellido,
                e.dni,
                c.nombre_cargo,
                a.hora_entrada,
                a.hora_salida,
                a.estado
            FROM ASISTENCIA a
            INNER JOIN EMPLEADO e ON a.id_empleado = e.id_empleado
            INNER JOIN CARGO c ON e.id_cargo = c.id_cargo
            WHERE a.fecha BETWEEN :fecha_inicio AND :fecha_fin
            ORDER BY a.fecha DESC, a.hora_entrada DESC
        ");
        
        $stmt->execute([
            ':fecha_inicio' => $fechaInicio,
            ':fecha_fin' => $fechaFin
        ]);
        
        $asistencias = $stmt->fetchAll();
        
        header('Content-Type: application/json');
        echo json_encode($asistencias);
        exit;
    }
}