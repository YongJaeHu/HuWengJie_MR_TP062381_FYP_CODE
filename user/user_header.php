<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../user/user_login.php");
    exit();
}
?>


<header class="header">
    <section class="upper">
        <div class="icons">
        <div id="menu_btn" class="fas fa-bars"></div>
            <a href="user_page.php">
                <img src="../images/logo.jpg" alt="logo" class="logo"></a>
            </div>
            <nav class="navbar">
            <a href="../user/user_page.php">Homepage</a>
         <a href="kid_view.php">Pet</a>
         <a href="centre_view.php">Centre</a>
         <a href="article_view.php">Article</a>
         <a href="event_view.php">Event</a>
         <a href="post_view.php">Petagram</a>
         <a href="report_add.php">Report</a>
      </nav>
            <div class="icons">
            <?php
                    $contact = $conn->prepare("SELECT * FROM `contact` WHERE receiver_id = ? and status = 'unreply'");
                    $contact->execute([$id]);
                    $total_contact = $contact->rowCount();

                    $order = $conn->prepare("SELECT * FROM `order` WHERE user_id = ? and status = 'waiting for approval from centre' or status = 'waiting for home visit/interview'");
                    $order->execute([$id]);
                    $total_order_process = $order->rowCount();

                    $save = $conn->prepare("SELECT COUNT(k.id) as total_waiting_kids
                    FROM `save` s
                    INNER JOIN `kid` k ON s.kid_id = k.id
                    WHERE s.user_id = ? AND k.status = 'waiting for owner'");
                    $save->execute([$id]);
                    $result = $save->fetch(PDO::FETCH_ASSOC);
                    $total_save = $result['total_waiting_kids'];
                    ?>
                    
                    <div id="notification_btn"  class="fa-solid fa-message"><span><?= $total_contact; ?></div>
                    <div id="save_btn"  class="fa-solid fa-bookmark"><span><?= $total_save; ?></div>
                    <div id="order_btn"  class="fa-solid fa-bell"><span><?= $total_order_process; ?></div>
                    <div id="user_btn" class="fas fa-user"></div>
            </div>
            <div class=notification>
            <a href="../user/contact_view.php" class="btn">view contact</a>
            </div>
            <div class=save>
            <a href="../user/save_view.php" class="btn">view save</a>
            </div>
            <div class=order>
            <a href="../user/order_view.php" class="btn">view order</a>
            </div>
            <div class="profile">
                <?php
                $select_profile = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
                $select_profile->execute([$id]);
                if($select_profile->rowCount() > 0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <a href="../user/user_detail.php">
                        <img src="../images/<?= $fetch_profile['image']; ?>" alt="">
                        <div class="text">
                            <h3><?= $fetch_profile['name']; ?></h3>
                        </a>
                    <span><?= $fetch_profile['email']; ?></span>
                    <a href="../user/user_detail.php" class="btn">view profile</a>
                    <a href="../user/user_updpro.php" class="option-btn">update profile</a>
                    <a href="../user/order_view.php" class="exit-btn">My order</a>
                    <a href="../user/contribution_view.php" class="exit-btn">My contribution</a>
                    <a href="../user/report_view.php" class="exit-btn">My report</a>
                    <a href="../user/user_login.php?logout=true" onclick="return confirm_msg(event);" class="delete-btn">logout</a>
                    <?php
                    }else{
                        ?>
                        <h3>please login or register</h3>
                        <div class="header_btn">
                            <a href="user_login.php" class="profile_btn">login</a>
                            <a href="user_register.php" class="profile_btn">register</a>
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
    $select_profile = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
    $select_profile->execute([$id]);
    if($select_profile->rowCount() > 0){
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <a href="../user/user_detail.php">
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
            <a href="user_login.php" class="profile_btn">login</a>
            <a href="../user/user_register.php" class="profile_btn">register</a>
          </div>
          <?php
             }
          ?>
          </div>
          <div class="sidenav">
          <a href="kid_view.php">Pet</a>
         <a href="centre_view.php">Centre</a>
         <a href="article_view.php">Article</a>
         <a href="event_view.php">Event</a>
         <a href="post_view.php">Petagram</a>
         <a href="report_add.php">Report</a>
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
</script>


