<?php 

if (isset($message)) {

   
    if (is_string($message)) {
        $message = [$message];
    }

    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

$user_name    = $_SESSION['user_name'] ?? 'Guest';
$user_email   = $_SESSION['user_email'] ?? '';
$user_profile = $_SESSION['user_profile'] ?? 'image/default-user.png';
?>

<header class="header">

    <div class="flex">

        <a href="home.php" class="logo">
            <img src="image/logo.jpg" alt="DRIPPY" style="height: 80px; width: 130px;">
        </a>

        <nav class="navbar">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="staff.php">Staff</a></li>
                <li><a href="appointment.php">Appointment</a></li>
                <li><a href="message.php">Message</a></li>
            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search.php" class="fas fa-search"></a>

            <div id="user-btn" class="fas fa-user"></div>

            <div class="profile-modal" id="profileBox">

                <img src="<?php echo htmlspecialchars($user_profile); ?>" class="profile-img">
                <p class="profile-name"><?php echo htmlspecialchars($user_name); ?></p>
                <p class="profile-email"><?php echo htmlspecialchars($user_email); ?></p>

                <form action="update_profile.php" method="POST" enctype="multipart/form-data" style="margin-top:10px;">
                    <input type="file" name="profile_picture" accept="image/*" required style="margin-bottom:10px;">
                    <button type="submit" class="profile-btn" style="width:100%;">Update</button>
                </form>

                <a href="#" class="logout-btn" id="openLogoutModal">Logout</a>
            </div>

        </div>

    </div>

</header>


<style>
.profile-modal {
    position: absolute;
    top: 110%;
    right: 0;
    width: 260px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    text-align: center;
    display: none;
    animation: fadeIn .3s ease;
    z-index: 1000;
    color: #333;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}

.profile-modal .profile-img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    margin-bottom: 10px;
}

.profile-name {
    font-size: 20px;
    font-weight: bold;
    margin: 5px 0;
}

.profile-email {
    font-size: 16px;
    color: #8C76B3;
    margin-bottom: 15px;
}

.profile-modal input[type="file"] {
    width: 100%;
    padding: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
}

.profile-btn,
.logout-btn {
    display: block;
    padding: 10px;
    margin: 8px 0;
    border-radius: 8px;
    font-size: 17px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.2s ease;
}

.profile-btn {
    background: #8C76B3;
    color: white;
}

.profile-btn:hover {
    background: #F7BFD8;
}

.logout-btn {
    background: #e74c3c;
    color: white;
}

.logout-btn:hover {
    background: #c0392b;
}

.logout-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(3px);
    justify-content: center;
    align-items: center;
    z-index: 2000;
}

.logout-modal-content {
    background: #fff;
    width: 350px;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    animation: popupFade .3s ease;
}

.logout-modal-content h2 {
    margin-bottom: 10px;
    font-size: 22px;
    font-weight: bold;
}

.logout-modal-content p {
    margin-bottom: 20px;
    font-size: 16px;
    color: #444;
}

.logout-buttons {
    display: flex;
    justify-content: space-between;
}

.cancel-btn,
.confirm-btn {
    width: 48%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
}

.cancel-btn {
    background: #bbb;
    color: #fff;
}

.cancel-btn:hover {
    background: #999;
}

.confirm-btn {
    background: #e74c3c;
    color: #fff;
    text-decoration: none;
}

.confirm-btn:hover {
    background: #c0392b;
}

@keyframes popupFade {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
}
</style>

<div id="logoutModal" class="logout-modal">
    <div class="logout-modal-content">
        <h2>Confirm Logout</h2>
        <p>Are you sure you want to logout?</p>

        <div class="logout-buttons">
            <button id="cancelLogout" class="cancel-btn">Cancel</button>
            <a href="logout.php" class="confirm-btn">Logout</a>
        </div>
    </div>
</div>

<script>
document.getElementById("user-btn").onclick = function() {
    const box = document.getElementById("profileBox");
    box.style.display = box.style.display === "block" ? "none" : "block";
};

document.addEventListener("click", function(e) {
    const modal = document.getElementById("profileBox");
    const btn = document.getElementById("user-btn");

    if (!modal.contains(e.target) && !btn.contains(e.target)) {
        modal.style.display = "none";
    }
});

document.getElementById("openLogoutModal").onclick = function () {
    document.getElementById("logoutModal").style.display = "flex";
};

document.getElementById("cancelLogout").onclick = function () {
    document.getElementById("logoutModal").style.display = "none";
};

document.getElementById("logoutModal").addEventListener("click", function(e) {
    if (e.target === this) {
        this.style.display = "none";
    }
});
</script>
