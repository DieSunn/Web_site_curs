<!-- navbar.php -->
<div id="sidebar" class="sidebar">
    <ul class="nav-links">
        <li class="<?php echo $activePage == 'dashboard' ? 'active' : ''; ?>"><a href="dashboard.php">База данных</a></li>
        <li class="<?php echo $activePage == 'logout' ? 'active' : ''; ?>"><a href="logout.php">Выход из системы</a></li>
    </ul>
</div>

<button id="sidebarToggle" class="sidebar-toggle">⋮⋮⋮</button>
