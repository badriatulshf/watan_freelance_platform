<?php
session_start();
include_once 'database.php';


$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    if ($search) {
        $stmt = $conn->prepare("SELECT id, name, skills, bio, availability, profile_picture 
                                FROM freelancers 
                                WHERE name LIKE :s OR skills LIKE :s OR bio LIKE :s
                                ORDER BY id DESC");
        $stmt->execute([':s' => "%$search%"]);
    } else {
        $stmt = $conn->query("SELECT id, name, skills, bio, availability, profile_picture 
                              FROM freelancers 
                              ORDER BY id DESC");
    }
    $freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Browse Freelancer Services</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #ffffff;
      font-family: 'Inter', sans-serif;
    }

    /* ================= HERO BANNER ================= */
    .hero-banner {
      width: 100%;
      height: 320px;
      background: url('browse_service.jpg') center center/cover no-repeat;
      border-radius: 0 0 20px 20px;
      position: relative;
      margin-bottom: 40px;
    }

    .hero-banner .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.45);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 0 15px;
    }

    .hero-text {
      font-size: 38px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 25px;
    }

    .search-box {
      max-width: 550px;
      width: 100%;
      display: flex;
      gap: 10px;
    }

    .search-box input {
      flex: 1;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .search-box button {
  padding: 12px 20px;
  border-radius: 8px;
  background: #7a5af8;   /* purple */
  color: #fff;
  border: none;
  font-weight: 600;
}

    /* ================= CARDS ================= */
    .freelancer-card {
      border: 1px solid #e4e4e4;
      border-radius: 12px;
      padding: 18px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: 0.2s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      text-align: center;
    }

    .freelancer-card:hover {
      box-shadow: 0 4px 14px rgba(0,0,0,0.10);
      transform: translateY(-5px);
    }

    .avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 10px;
  border: 2px solid #7a5af8; /* purple */
}

    .name {
      font-size: 17px;
      font-weight: 700;
      margin-bottom: 6px;
      color: #1f1f1f;
    }

    
.speciality {
  font-size: 14px;
  font-weight: 600;
  color: #7a5af8;     /* purple */
  margin-bottom: 10px;
}


    .skill-tag {
      display: inline-block;
      background: #f1f1f1;
      padding: 6px 10px;
      border-radius: 20px;
      font-size: 12px;
      margin: 3px;
      color: #444;
    }

    .availability {
      font-size: 13px;
      font-weight: 600;
      margin-top: 10px;
      color: #555;
    }

   .upwork-btn {
  border: 1px solid #7a5af8;   /* purple */
  color: #7a5af8;               /* purple */
  padding: 7px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  margin: 4px;
}

.upwork-btn:hover {
  background: #7a5af8;   /* purple */
  color: #fff;
}

    /* Navigation Bar */
    nav {
      background-color: rgba(255,255,255,0.9);
      backdrop-filter: saturate(180%) blur(6px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.08);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 60px;
      position: sticky;
      top: 0;
      z-index: 10;
      transition: box-shadow .2s ease, background-color .2s ease;
    }

    nav .logo {
      font-size: 1.6rem;
      font-weight: 700;
      color: #7a5af8; /* soft purple */
    }

    nav ul {
      display: flex;
      list-style: none;
      gap: 25px;
    }

    nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      position: relative;
      padding-bottom: 4px;
      transition: color .2s ease;
    }

    nav ul li a:hover { color: #7a5af8; }

    nav ul li a::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 0;
      height: 2px;
      background: #7a5af8;
      transition: width .2s ease;
    }

    nav ul li a:hover::after { width: 100%; }

    .nav-actions { display: flex; align-items: center; gap: 10px; }

    .btn-join-nav {
      background: #7a5af8;
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 999px;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s ease;
    }

    .btn-join-nav:hover { background: #6948f0; }

    .link-signin {
      color: #333;
      text-decoration: none;
      font-weight: 500;
      padding: 6px 10px;
      border-radius: 8px;
      transition: color .2s ease, background-color .2s ease;
    }
    .link-signin:hover { color: #7a5af8; background-color: #f3f1ff; }

  </style>
</head>



<body>

  <body>

<!-- NAVIGATION BAR -->
<nav>
  <div class="logo">Watan Freelance</div>

  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="browse_services.php">Browse Services</a></li>
    <li><a href="about.php">About</a></li>
  </ul>

  <div class="nav-actions">
    <a href="login.php" class="link-signin">Sign In</a>
    <button class="btn-join-nav" onclick="window.location='register.php'">Join Now</button>
  </div>
</nav>


<!-- ✅ HERO BANNER -->
<div class="hero-banner">
  <div class="overlay"></div>

  <div class="hero-content">
    <h1 class="hero-text">Find Our Freelancers Now!</h1>

    <form class="search-box" method="GET">
      <input type="text" name="search" placeholder="Search freelancers or skills..." 
             value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>
  </div>
</div>

<!-- ✅ FREELANCER LIST -->
<div class="container pb-5">
  <div class="row g-4">

    <?php foreach ($freelancers as $f): 
        $bio = htmlspecialchars($f['bio']);
        $speciality = explode('.', $bio)[0];
    ?>
      <div class="col-md-4">
        <div class="freelancer-card">

          <!-- Photo -->
          <?php if (!empty($f['profile_picture'])): ?>
            <img src="uploads/<?= htmlspecialchars($f['profile_picture']) ?>" class="avatar">
          <?php else: ?>
            <div class="avatar bg-light d-flex justify-content-center align-items-center text-muted">?</div>
          <?php endif; ?>

          <!-- Name -->
          <div class="name"><?= htmlspecialchars($f['name']) ?></div>

          <!-- Speciality -->
          <div class="speciality"><?= $speciality ?></div>

          <!-- Skills -->
          <div>
            <?php 
              $skills = explode(',', $f['skills']);
              foreach ($skills as $s): ?>
                <span class="skill-tag"><?= htmlspecialchars(trim($s)) ?></span>
            <?php endforeach; ?>
          </div>

          <!-- Availability -->
          <div class="availability">Availability: <?= htmlspecialchars($f['availability']) ?></div>

          <!-- Buttons -->
          <div class="mt-3">
            <a href="freelancer_details.php?id=<?= $f['id'] ?>" class="upwork-btn">View Profile</a>
            <a href="booking.php?freelancer_id=<?= $f['id'] ?>" class="upwork-btn">Book Now</a>
          </div>

        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($freelancers)): ?>
      <p class="text-center mt-4">No freelancers found.</p>
    <?php endif; ?>

  </div>
</div>

</body>
</html>
