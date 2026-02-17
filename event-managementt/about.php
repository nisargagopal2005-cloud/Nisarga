<?php // about.php ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>About Us — Event Management</title>
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      background: linear-gradient(135deg, #f0f4f8, #d9e4f5);
      margin: 0;
      color: #222;
    }
    header.nav-3d {
      background: #222;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem 2rem;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    header.nav-3d .logo {
      font-size: 1.4rem;
    }
    main {
      padding: 7rem 2rem;
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      animation: fadeIn 1.5s ease-in-out;
    }
    h1 {
      text-align: center;
      color: #112d4e;
      margin-bottom: 2rem;
    }
    .info-section {
      margin-bottom: 2rem;
      padding: 1rem 2rem;
      background: linear-gradient(145deg, #e3f2fd, #bbdefb);
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transition: transform 0.5s;
    }
    .info-section:hover {
      transform: translateY(-5px) scale(1.02);
    }
    h2 {
      color: #0d3b66;
      margin-bottom: 0.5rem;
    }
    p {
      margin: 0.5rem 0 1rem;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <header class="nav-3d">
    <div class="logo">🎪 <strong>Event Management</strong></div>
  </header>

  <main>
    <h1>About Us</h1>

    <div class="info-section">
      <h2>Company Background</h2>
      <p>Founded to simplify event planning, we provide an interactive platform for scheduling, ticketing, and analytics.</p>
    </div>

    <div class="info-section">
      <h2>Mission</h2>
      <p>Our mission is to make events more engaging and manageable for organizers and attendees alike.</p>
    </div>

    <div class="info-section">
      <h2>Values</h2>
      <p>We prioritize innovation, transparency, and customer satisfaction in every feature we build.</p>
    </div>

    <div class="info-section">
      <h2>Expertise</h2>
      <p>Our team specializes in event technology, data analytics, and user experience design.</p>
    </div>
  </main>
</body>
</html>
