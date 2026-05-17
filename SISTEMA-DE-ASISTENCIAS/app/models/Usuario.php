<?php
// app/models/Usuario.php

class Usuario extends Model 
{
    public function registrar(string $usuario, string $clave): array 
    {
        // Validaciones
        if (empty($usuario) || empty($clave)) {
            return ['ok' => false, 'mensaje' => 'Todos los campos son obligatorios.'];
        }
        
        if (strlen($usuario) <= 3) {
            return ['ok' => false, 'mensaje' => 'El usuario debe tener al menos 3 caracteres.'];
        }
        
        if (strlen($clave) <= 3) {
            return ['ok' => false, 'mensaje' => 'La contraseña debe tener al menos 3 caracteres.'];
        }
        
        // Verificar si existe
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM USUARIO WHERE nombre = :nombre LIMIT 1");
        $stmt->execute(['nombre' => $usuario]);
        
        if ($stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'El usuario ya existe.'];
        }
        
        // Insertar
        $claveHash = password_hash($clave, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO USUARIO (nombre, clave) VALUES (:nombre, :clave)");
        $stmt->execute(['nombre' => $usuario, 'clave' => $claveHash]);
        
        return ['ok' => true, 'mensaje' => 'Usuario registrado correctamente.'];
    }
    
    public function login(string $usuario, string $clave): array 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM USUARIO WHERE nombre = :nombre LIMIT 1");
        $stmt->execute(['nombre' => $usuario]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['ok' => false, 'mensaje' => 'Usuario no encontrado.'];
        }
        
        if (!password_verify($clave, $user['clave'])) {
            return ['ok' => false, 'mensaje' => 'Contraseña incorrecta.'];
        }
        
        return ['ok' => true, 'mensaje' => 'Inicio de sesión exitoso.', 'usuario' => $user];
    }
}