<?php
session_start();
$user = $_SESSION['user_name'] ?? 'Guest';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Promotions — Event Management</title>
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #f0f4f8, #d9e4f5);
      margin: 0;
      color: #222;
    }
    header.nav-3d {
      background: #ff6f61;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1.5rem 2rem;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
      transform: perspective(500px) rotateX(5deg);
      animation: float 2s ease-in-out infinite alternate;
    }
    header.nav-3d .logo {
      font-size: 1.6rem;
      font-weight: bold;
    }
    @keyframes float {
      0% { transform: perspective(500px) rotateX(5deg) translateY(0px); }
      100% { transform: perspective(500px) rotateX(5deg) translateY(8px); }
    }
    main {
      padding: 7rem 2rem;
      max-width: 900px;
      margin: auto;
      text-align: center;
    }
    h1 {
      color: #ff3b30;
      margin-bottom: 2rem;
      font-size: 2.2rem;
    }
    p {
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }
    .celebration {
      font-size: 3rem;
      animation: bounce 1.5s infinite alternate;
    }
    @keyframes bounce {
      0% { transform: translateY(0px); }
      100% { transform: translateY(-10px); }
    }
  </style>
</head>
<body>
  <header class="nav-3d">
    <div class="logo">🎉 Event Promotions</div>
  </header>

  <main>
    <h1 class="celebration">🎊 Celebrate & Save!</h1>

    <p>🎟️ Early-bird discounts available for limited-time attendees.</p>
    <p>💌 Share promo codes with friends for extra perks.</p>
    <p>✨ Special VIP offers for loyal participants.</p>
  </main>
</body>
</html>
