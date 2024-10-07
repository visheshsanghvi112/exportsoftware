 <!-- sidebar.php -->
<div class="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-angle-left"></i>
    </button>
    <h2>Export Software</h2>
    <ul>
        <li><a href="input.php"><i class="fas fa-file-invoice-dollar"></i><span>Create Export Bills</span></a></li>
        <li><a href="display_exportbills.php"><i class="fas fa-eye"></i><span>View Bills</span></a></li>
        <li><a href="create_buyer.php"><i class="fas fa-user-plus"></i><span>Create Buyer</span></a></li>
        <li><a href="manage_buyers.php"><i class="fas fa-users-cog"></i><span>Manage Buyers</span></a></li>
    </ul>
    <div class="logout">
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>
</div>
