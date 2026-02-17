<?php // contact.php ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contact — Event Management</title>
  
  <!-- Font Awesome for social icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="assets/styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
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
      max-width: 700px;
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
    .contact-info {
      margin-bottom: 1.5rem;
      padding: 1rem 2rem;
      background: linear-gradient(145deg, #e3f2fd, #bbdefb);
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transition: transform 0.5s;
    }
    .contact-info:hover {
      transform: translateY(-5px) scale(1.02);
    }
    p {
      margin: 0.5rem 0;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Social icons */
    .social-links {
      text-align: center;
      margin-top: 2rem;
    }
    .social-links a {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 60px;
      height: 60px;
      margin: 0 10px;
      background: #112d4e;
      color: #fff;
      font-size: 2rem;
      border-radius: 50%;
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
      transition: transform 0.4s, box-shadow 0.4s;
      text-decoration: none;
    }
    .social-links a:hover {
      transform: rotateY(20deg) rotateX(10deg) translateY(-10px) scale(1.2);
      box-shadow: 0 20px 30px rgba(0,0,0,0.5);
      background: #0077b6; /* Hover color */
    }
  </style>
</head>
<body>
  <header class="nav-3d">
    <div class="logo">🎪 <strong>Event Management</strong></div>
  </header>

  <main>
    <h1>📞 Contact Us</h1>

    <div class="contact-info">
      <h2>Email</h2>
      <p>rohan.m0103@gmail.com</p>
      <p>You can also contact us via email for queries, support, or collaboration.</p>
    </div>

    <div class="contact-info">
      <h2>Phone Numbers</h2>
      <p>+91 7022288653</p>
      <p>+91 8310361918</p>
      <p>+91 7619185592</p>
      <p>Available for calls or WhatsApp messages during business hours.</p>
    </div>

    <div class="social-links">
      <a href="https://wa.me/917022288653" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      <a href="https://instagram.com/yourprofile" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
      <a href="https://twitter.com/yourprofile" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
    </div>
  </main>
</body>
</html>
