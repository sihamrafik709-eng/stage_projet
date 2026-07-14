<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPath = $_SERVER['REQUEST_URI'] ?? '';
function isActive($needle, $path) {
    return strpos($path, $needle) !== false ? 'active' : '';
}
$role = $_SESSION['role'] ?? 'admin';

$allMenuItems = [
    ['key' => 'dashboard',  'href' => '../dashboard/index.php',  'icon' => '📊', 'label' => 'Tableau de bord', 'roles' => ['admin', 'teacher']],
    ['key' => 'students',   'href' => '../students/list.php',    'icon' => '🎓', 'label' => 'Étudiants',       'roles' => ['admin', 'teacher']],
    ['key' => 'teachers',   'href' => '../teachers/list.php',    'icon' => '👨‍🏫', 'label' => 'Enseignants',     'roles' => ['admin']],
    ['key' => 'classes',    'href' => '../classes/list.php',     'icon' => '🏫', 'label' => 'Classes',         'roles' => ['admin']],
    ['key' => 'attendance', 'href' => '../attendance/list.php',  'icon' => '📅', 'label' => 'Présences',       'roles' => ['admin', 'teacher']],
    ['key' => 'grades',     'href' => '../grades/list.php',      'icon' => '📝', 'label' => 'Notes',           'roles' => ['admin', 'teacher']],
    ['key' => 'users',      'href' => '../users/list.php',       'icon' => '👤', 'label' => 'Utilisateurs',    'roles' => ['admin']],
];

$menuItems = array_filter($allMenuItems, fn($item) => in_array($role, $item['roles']));
?>
<div class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <div class="brand">
            <span class="brand-mark">S</span>
            <span class="logo-text">SMS <em><?= htmlspecialchars(ucfirst($role)) ?></em></span>
        </div>
        <button id="toggle-btn" aria-label="Réduire le menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="sidebar-divider"></div>

    <ul class="menu">
        <?php foreach ($menuItems as $item): ?>
        <li class="<?= isActive($item['key'], $currentPath) ?>">
            <a href="<?= $item['href'] ?>" data-tip="<?= htmlspecialchars($item['label']) ?>">
                <span class="ic"><?= $item['icon'] ?></span><span class="label"><?= htmlspecialchars($item['label']) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="sidebar-footer">
        <div class="footer-glow"></div>
        <span class="footer-text">© <?= date('Y') ?> SMS</span>
    </div>

    <div class="active-indicator" id="activeIndicator"></div>
</div>
<style>
.sidebar {
    width: 260px;
    height: 100vh;
    background: linear-gradient(180deg, #0f172a 0%, #0a1120 100%);
    color: #ffffff;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    z-index: 100;
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.35);
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.sidebar.collapsed {
    width: 78px;
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 18px;
    gap: 10px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    overflow: hidden;
}

.brand-mark {
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #d4af37 0%, #a8842b 100%);
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 18px;
    box-shadow: 0 0 18px rgba(212, 175, 55, 0.35);
}

.logo-text {
    font-size: 17px;
    font-weight: 700;
    letter-spacing: 0.3px;
    white-space: nowrap;
    color: #ffffff;
}

.logo-text em {
    font-style: normal;
    color: #f0d789;
    font-weight: 300;
}

.sidebar.collapsed .logo-text {
    opacity: 0;
    width: 0;
}

#toggle-btn {
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(212, 175, 55, 0.25);
    color: #f0d789;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: 0.25s;
}

#toggle-btn span {
    width: 14px;
    height: 2px;
    background: #f0d789;
    border-radius: 2px;
    transition: 0.25s;
}

#toggle-btn:hover {
    background: rgba(212, 175, 55, 0.15);
    box-shadow: 0 0 12px rgba(212, 175, 55, 0.35);
}

.sidebar.collapsed #toggle-btn span:nth-child(1) { transform: translateY(6px) rotate(45deg); }
.sidebar.collapsed #toggle-btn span:nth-child(2) { opacity: 0; }
.sidebar.collapsed #toggle-btn span:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }

.sidebar-divider {
    height: 1px;
    margin: 0 20px 12px;
    background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.4), transparent);
}

.menu {
    list-style: none;
    padding: 0 14px;
    margin: 6px 0 0;
    flex: 1;
    position: relative;
}

.menu li {
    margin: 4px 0;
    position: relative;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #c7cede;
    text-decoration: none;
    padding: 13px 16px;
    border-radius: 10px;
    position: relative;
    transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
    white-space: nowrap;
}

.menu a .ic {
    font-size: 19px;
    flex-shrink: 0;
    filter: grayscale(0.3);
    transition: 0.2s;
}

.menu a:hover {
    background: #1c2a4d;
    color: #ffffff;
    transform: translateX(3px);
}

.menu a:hover .ic {
    filter: grayscale(0);
    transform: scale(1.08);
}

.menu li.active a {
    background: linear-gradient(90deg, rgba(212, 175, 55, 0.16), rgba(212, 175, 55, 0.03));
    color: #ffffff;
    font-weight: 600;
}

.menu li.active a::before {
    content: "";
    position: absolute;
    left: -14px;
    top: 8px;
    bottom: 8px;
    width: 4px;
    border-radius: 0 4px 4px 0;
    background: #d4af37;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.35);
}

.menu li.active .ic {
    filter: grayscale(0);
}

.sidebar.collapsed .menu .label {
    display: none;
}

.sidebar.collapsed .menu a {
    justify-content: center;
    padding: 13px 0;
}

.sidebar.collapsed .menu li.active a::before {
    left: 0;
}

.sidebar.collapsed .menu a::after {
    content: attr(data-tip);
    position: absolute;
    left: calc(100% + 14px);
    top: 50%;
    transform: translateY(-50%) scale(0.9);
    background: #16213f;
    color: #ffffff;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.4), 0 0 0 1px rgba(212, 175, 55, 0.25);
    transition: 0.18s;
    z-index: 200;
}

.sidebar.collapsed .menu a:hover::after {
    opacity: 1;
    transform: translateY(-50%) scale(1);
}

.sidebar-footer {
    padding: 16px 22px 20px;
    position: relative;
}

.footer-glow {
    position: absolute;
    top: 0;
    left: 20px;
    right: 20px;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
}

.footer-text {
    font-size: 11px;
    color: rgba(199, 206, 222, 0.5);
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.sidebar.collapsed .footer-text {
    opacity: 0;
}

.main-content {
    margin-left: 260px;
    transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.main-content.expanded {
    margin-left: 78px;
}

.menu::-webkit-scrollbar { width: 4px; }
.menu::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.3); border-radius: 4px; }
</style>

<script>
const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggle-btn");
const mainContent = document.querySelector(".main-content");

toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    if (mainContent) mainContent.classList.toggle("expanded");
    localStorage.setItem("sms_sidebar_collapsed", sidebar.classList.contains("collapsed"));
});

if (localStorage.getItem("sms_sidebar_collapsed") === "true") {
    sidebar.classList.add("collapsed");
    if (mainContent) mainContent.classList.add("expanded");
}
</script>