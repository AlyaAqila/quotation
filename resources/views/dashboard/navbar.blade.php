<nav class="navbar">

    <!-- Logo -->
    <div class="logo">
        <i class="bi bi-file-spreadsheet"></i>
    </div>

    <!-- Navigation -->
    <ul class="nav-links">

        <li>
            <a href="{{ url('/home') }}" class="{{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                HOME
            </a>
        </li>

        <li>
            <a href="{{ url('/history') }}" class="{{ request()->is('history') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                HISTORY
            </a>
        </li>

        <li>
            <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                DATA MANAGEMENT
            </a>
        </li>

        <li>
            <a href="{{ url('/modelling') }}" class="{{ request()->is('modelling') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                MODELLING
            </a>
        </li>

    </ul>

    <!-- Right Side -->
    <div class="nav-right">

        <div class="profile" id="profileBtn">
            <i class="fa-regular fa-user"></i>
            {{-- Dynamically shows user name if logged in, otherwise 'GUEST' --}}
            <span>{{ Auth::check() ? Auth::user()->name : 'GUEST' }}</span>
            <i class="fa-solid fa-chevron-down"></i>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu" id="dropdownMenu">
                <button type="button" id="openDrawerBtn">
                    <i class="fa-regular fa-id-card"></i> See Profile
                </button>
                <button type="button" id="openModalBtn">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </div>
        </div>

    </div>

</nav>

<!-- Side Drawer (Profile Info) -->
<div class="drawer-overlay" id="drawerOverlay"></div>
<aside class="side-drawer" id="sideDrawer">
    <div class="drawer-header">
        <h3>User Profile</h3>
        <button type="button" id="closeDrawerBtn"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="drawer-body">
        <i class="fa-solid fa-circle-user avatar"></i>
        <p><strong>Name:</strong> {{ Auth::check() ? Auth::user()->name : 'N/A' }}</p>
        <p><strong>Email:</strong> {{ Auth::check() ? Auth::user()->email : 'N/A' }}</p>
    </div>
</aside>

<!-- Confirmation Modal (Sign Out) -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-card">
        <h3>Sign Out</h3>
        <p>Are you sure you want to sign out?</p>
        
        <div class="modal-actions">
            <button type="button" class="btn-cancel" id="cancelSignOutBtn">Cancel</button>
            
            <!-- Official Laravel POST Logout Route -->
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-confirm">Sign Out</button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Interactivity -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileBtn = document.getElementById('profileBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    const openDrawerBtn = document.getElementById('openDrawerBtn');
    const sideDrawer = document.getElementById('sideDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');

    const openModalBtn = document.getElementById('openModalBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const cancelSignOutBtn = document.getElementById('cancelSignOutBtn');

    // 1. Toggle Dropdown
    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
        profileBtn.classList.toggle('active');
    });

    // Close Dropdown when clicking anywhere outside
    document.addEventListener('click', () => {
        dropdownMenu.classList.remove('show');
        profileBtn.classList.remove('active');
    });

    // 2. Open Side Drawer
    openDrawerBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.remove('show');
        sideDrawer.classList.add('open');
        drawerOverlay.classList.add('active');
    });

    // Close Side Drawer
    const closeDrawer = () => {
        sideDrawer.classList.remove('open');
        drawerOverlay.classList.remove('active');
    };
    closeDrawerBtn.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);

    // 3. Open Sign Out Modal
    openModalBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.remove('show');
        modalOverlay.classList.add('active');
    });

    // Close Sign Out Modal
    cancelSignOutBtn.addEventListener('click', () => {
        modalOverlay.classList.remove('active');
    });
});
</script>