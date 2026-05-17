<?php
// app/controllers/DashboardController.php

class DashboardController extends Controller 
{
    public function index(): void 
    {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Instanciar modelos directamente
        require_once __DIR__ . '/../models/Empleado.php';
        require_once __DIR__ . '/../models/Asistencia.php';
        
        $empleadoModel = new Empleado();
        $asistenciaModel = new Asistencia();
        
        // Obtener datos reales de la base de datos
        $data = [
            'totalEmpleados' => $empleadoModel->contarTodos(),
            'asistenciasHoy' => $asistenciaModel->contarAsistenciasHoy(),
            'ausentesHoy' => $asistenciaModel->contarAusentesHoy(),
            'tardanzasHoy' => $asistenciaModel->contarTardanzasHoy(),
            'porcentajeAsistencia' => $asistenciaModel->calcularPorcentajeAsistenciaHoy(),
            'asistenciasPorSemana' => $asistenciaModel->obtenerAsistenciasPorSemana(),
            'ultimosRegistros' => $asistenciaModel->obtenerUltimosRegistros(5)
        ];
        
        // Cargar vista del dashboard CON layout dashboard
        $this->view('dashboard/index', $data, 'dashboard');
    }
}