<?php
if(isset($message)){
    if (is_array($message)) { 
        foreach ($message as $msg) {
            echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
}
?>

<header class="header">

    <section class="flex">

        <a href="home.php" class="logo"></a>

        <form action="search_course.php" method="post" class="search-form">
            <input type="text" name="search_course" placeholder="search courses..." required maxlength="100">
            <button type="submit" class="fas fa-search" name="search_course_btn"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="fa-solid fa-list"></div>
            <div id="search-btn" class="fa-solid fa-magnifying-glass"></div>
            <div id="user-btn" class="fa-solid fa-right-to-bracket"></div>

        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
            <h3><?= $fetch_profile['name']; ?></h3>
            <span>student</span>
            <a href="profile.php" class="btn">view profile</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');"
                class="delete-btn">logout</a>
            <?php
            }else{
         ?>
            <h3>please login or register</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
            <?php
            }
         ?>
        </div>

    </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

    <div class="close-side-bar">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
        <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
        <h3><?= $fetch_profile['name']; ?></h3>
        <span>student</span>
        <a href="profile.php" class="btn">view profile</a>
        <?php
            }else{
         ?>
        <h3>please login or register</h3>
        <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
        </div>
        <?php
            }
         ?>
    </div>

    <nav class="navbar">
        <a href="home.php"><i class="fa-solid fa-door-open"></i><span>home</span></a>
        <a href="courses.php"><i class="fa-solid fa-book"></i><span>courses</span></a>
        <a href="teachers.php"><i class="fa-solid fa-user-tie"></i><span>teachers</span></a>

    </nav>

</div>

<!-- side bar section ends -->