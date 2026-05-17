<?php
// app/views/layouts/dashboard_footer.php
?>
    </main>

    <script>
    const preloader = document.getElementById('preloader');
    
    if (preloader) {
        // Verificar si es la primera carga después del login
        // Usamos sessionStorage para saber si ya pasó por el dashboard
        if (!sessionStorage.getItem('dashboardLoaded')) {
            // Mostrar loader solo la primera vez
            preloader.style.display = 'flex';
            preloader.style.opacity = '1';
            
            window.addEventListener('load', function() {
                setTimeout(function() {
                    preloader.style.opacity = '0';
                    setTimeout(function() {
                        preloader.style.display = 'none';
                        sessionStorage.setItem('dashboardLoaded', 'true');
                    }, 500);
                }, 2000);
            });
        } else {
            // Ya cargó el dashboard antes, ocultar loader
            preloader.style.display = 'none';
        }
    }
</script>
</script>
    <!-- Fin del contenido principal -->
    <script src="<?php echo BASE_URL; ?>/public/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarCerrarSesion(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Vas a cerrar tu sesión actual.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo BASE_URL; ?>/auth/logout';
                }
            });
        }
    </script>

    <script>
    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Eliminar empleado?',
            text: 'Estás por eliminar a ' + nombre,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo BASE_URL; ?>/empleado/eliminar/' + id;
            }
        });
    }
    

</script>

   
</body>
</html>