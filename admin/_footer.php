 </div> 
</div> 

<!-- Sidebar Overlay for Mobile -->
<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;background:rgba(0,0,0,0.6);
            z-index:499;"></div>

<script>
// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar  = document.querySelector('.admin-sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const isOpen   = sidebar.classList.contains('sidebar-open');
    sidebar.classList.toggle('sidebar-open', !isOpen);
    overlay.style.display = isOpen ? 'none' : 'block';
}

// Show toggle button on mobile
function checkMobile() {
    const btn = document.getElementById('sidebarToggle');
    if (btn) btn.style.display = window.innerWidth <= 860 ? 'block' : 'none';
}
checkMobile();
window.addEventListener('resize', checkMobile);

// Close sidebar when nav link clicked on mobile
document.querySelectorAll('.admin-sidebar a').forEach(a => {
    a.addEventListener('click', () => {
        if (window.innerWidth <= 860) toggleSidebar();
    });
});
</script>

<script src="<?= $base ?>/assets/js/main.js"></script>
</body>
</html>