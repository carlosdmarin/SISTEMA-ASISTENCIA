// public/js/dashboard.js

document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    
    // Abrir/cerrar sidebar en móvil
    if (menuToggle && sidebar && overlay) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
    
    // Submenús (funciona en todas las pantallas)
    const submenuHeaders = document.querySelectorAll('.menu-header');
    
    submenuHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            e.stopPropagation();
            const parent = this.parentElement;
            
            // Cerrar otros submenús abiertos
            document.querySelectorAll('.menu-item.has-submenu').forEach(item => {
                if (item !== parent) {
                    item.classList.remove('active');
                }
            });
            
            // Toggle el actual
            parent.classList.toggle('active');
        });
    });
    
    // Cerrar sidebar al hacer clic en un enlace (solo en móvil)
    document.querySelectorAll('.menu a, .submenu a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });
    
    // Cerrar sidebar al redimensionar a desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
    
    console.log('Dashboard JS cargado correctamente');
});