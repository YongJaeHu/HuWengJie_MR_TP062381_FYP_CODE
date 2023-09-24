<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../admin/admin_login.php");
    exit();
}
?>
<header class="header">
    <section class="upper">
        <div class="icons">
            <div id="menu_btn" class="fas fa-bars"></div>
            <a href="admin_page.php">
                <img src="../images/logo.jpg" alt="logo" class="logo"></a>
            </div>
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            $role = $fetch_profile['role'];
            ?>
                <?php if ($role == 1) { ?>
                    <a href="admin_page.php" class="title">Admin Page</a>
                    <?php } else { ?>
                        <a href="admin_page.php" class="title">Centre Page</a>
                        <?php } ?>
            <!-- <a href="admin_page.php" class="title">Admin Page</a> -->
            <div class="icons">
                <!-- <div id="message" class="fa-solid fa-comment"></div> -->
                <!-- <div id="notification"  class="fa-solid fa-bell"></div> -->
                <!-- <div id="user_btn" class="fas fa-user"></div>         -->
            <?php
                    $contact = $conn->prepare("SELECT * FROM `contact` WHERE receiver_id = ? and status = 'unreply'");
                    $contact->execute([$id]);
                    $total_contact = $contact->rowCount();

                    $order = $conn->prepare("SELECT * FROM `order` WHERE centre_id = ? and status = 'waiting for approval from centre'");
                    $order->execute([$id]);
                    $total_order_process = $order->rowCount();
                    ?>
                    <div id="notification_btn"  class="fa-solid fa-message"><span><?= $total_contact; ?></div>
                    <div id="order_btn"  class="fa-solid fa-bell"><span><?= $total_order_process; ?></div>
                    <div id="user_btn" class="fas fa-user"></div> 
            </div>
            <div class=notification>
            <a href="../admin/contact_view.php" class="btn">view contact</a>
            </div>
            <div class=order>
            <a href="../admin/order_view.php" class="btn">view order</a>
            </div>
            <div class="profile">
                <?php
                $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
                $select_profile->execute([$id]);
                if($select_profile->rowCount() > 0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <a href="../admin/admin_detail.php">
                        <img src="../images/<?= $fetch_profile['image']; ?>" alt="">
                        <div class="text">
                            <h3><?= $fetch_profile['name']; ?></h3>
                        </a>
                    <span><?= $fetch_profile['email']; ?></span>
                    <a href="../admin/admin_detail.php" class="btn">view profile</a>
                    <a href="../admin/admin_updpro.php" class="option-btn">update profile</a>
                    <a href="../admin/admin_login.php?logout=true" onclick="return confirm_msg(event);" class="delete-btn">logout</a>
                    <?php
                    }else{
                        ?>
                        <h3>please login or register</h3>
                        <div class="header_btn">
                            <a href="admin_login.php" class="profile_btn">login</a>
                            <a href="admin_register.php" class="profile_btn">register</a>
                        </div>
                        <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</header>

<div class="side">
    <div class="close_side">
        <i class="fa-sharp fa-solid fa-xmark"></i>
    </div>
    <div class="profile">
    <?php
    $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
    $select_profile->execute([$id]);
    if($select_profile->rowCount() > 0){
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <a href="../admin/admin_detail.php">
            <img src="../images/<?= $fetch_profile['image']; ?>" alt="">
            <div class="text">
                <h3><?= $fetch_profile['name']; ?></h3>
            </a>
            <span><?= $fetch_profile['email']; ?></span>
        </div>
        <?php
             }else{
          ?>
          <h3>please login or register</h3>
          <div class="header_btn">
            <a href="admin_login.php" class="profile_btn">login</a>
            <a href="../admin/admin_register.php" class="profile_btn">register</a>
          </div>
          <?php
             }
          ?>
          </div>
          <div class="sidenav">
            <a href="admin_page.php"><i class="fas fa-home"></i><span>Dashboard</span></a>
            <a href="kid_view.php"><i class="fas fa-paw"></i><span>Kids</span></a>
            <a href="order_view.php"><i class="fa-solid fa-folder-open"></i><span>Order</span></a>
            <a href="article_view.php"><i class="fas fa-graduation-cap"></i><span>Article</span></a>
            <a href="event_view.php"><i class="fas fa-calendar"></i><span>Event</span></a>
            <a href="post_view.php"><i class="fa-brands fa-instagram"></i><span>Petagram</span></a>
            <a href="../admin/contribution_view.php"><i class="fas fa-circle-dollar-to-slot"></i><span>Contribution</span></a>
            <a href="report_view.php"><i class="fa-solid fa-circle-exclamation"></i><span>Report</span></a>
            <a href="contact_view.php"><i class="fa-solid fa-message"></i><span>Contact</span></a>       
            <?php if ($fetch_profile['role'] == 1) { ?>
                <button class="dropdown-btn"><i class="fa-solid fa-people-group"></i>Centre<i class="fa fa-caret-down"></i></button>
                <div class="dropdown-container">
                <a href="../admin/centre_view.php"><i class="fa-solid fa-building"></i>Centre List</a>
                <a href="approval_view.php"><i class="fa-solid fa-building-circle-check"></i>Centre Approval</a>
                <a href="../admin/admin_register_centre.php"><i class="fa-solid fa-building-circle-arrow-right"></i>Register New Centre</a>
                <a href="../admin/admin_register.php"><i class="fa-solid fa-user-plus"></i>Register New Admin</a>
            </div>
            <?php } ?>
            <a href="../admin/admin_repass.php"onclick="return pass_msg(event);"><i class="fa-solid fa-unlock"></i>Reset Password</a>
            <a href="../admin/admin_login.php?logout=true" onclick="return confirm_msg(event);"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a>
        </div>
    </div>
</div>
<script>
function confirm_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
        title: "Are you sure to Log Out?",
        text: "We will miss you.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willCancel) => {
            if (willCancel) { 
                window.location.href = urlToRedirect;   
            }  
        });
    }

    function pass_msg(event) {
    event.preventDefault();
    var urlToRedirect = event.currentTarget.getAttribute('href');  
    console.log(urlToRedirect); 
    swal({
        title: "Are you sure to Reset Password?",
        text: "You will be logout.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willCancel) => {
            if (willCancel) { 
                window.location.href = urlToRedirect;   
            }  
        });
    }
</script>


