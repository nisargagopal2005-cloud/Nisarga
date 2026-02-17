<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Explore Events</title>
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
      max-width: 900px;
      margin: auto;
      animation: fadeIn 1.5s ease-in-out;
    }
    h1 {
      text-align: center;
      color: #112d4e;
      margin-bottom: 2rem;
    }
    .content-img {
      display: block;
      max-width: 100%;
      height: auto;
      margin: 2rem auto;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    .content-img:hover {
      transform: scale(1.03);
    }
    /* Modal for clicked image */
    .modal {
      display: none;
      position: fixed;
      z-index: 200;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.7);
      justify-content: center;
      align-items: center;
    }
    .modal img {
      max-width: 30%;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      animation: scaleUp 0.5s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleUp {
      from { transform: scale(0.8); }
      to { transform: scale(1); }
    }
  </style>
</head>
<body>
  <header class="nav-3d">
    <div class="logo">🎪 <strong>Event Management</strong></div>
  </header>

  <main>
    <h1>🌟 Explore Exciting Events</h1>
    <p>
      Events bring people together for learning, networking, or entertainment.  
      From conferences and workshops to exhibitions, events help people connect and celebrate.
    </p>

    <!-- Photo 1 -->
    <img src="https://images.unsplash.com/photo-1506784365847-bbad939e9335?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
         alt="Conference crowd" class="content-img">

    <p>
      Modern event platforms make it easy to browse events worldwide, book tickets instantly,  
      and attend virtual sessions from anywhere.
    </p>

    <!-- Photo 2 -->
    <img src="https://images.unsplash.com/photo-1522204523234-872d2c28d2d2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
         alt="Event management workshop" class="content-img">

    <p>
      Organizers and attendees can experience culture, innovation, and fun at events happening near you or globally.
    </p>

    <!-- Photo 3 -->
    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
         alt="Event planning session" class="content-img">
  </main>

  <!-- Modal -->
  <div id="imgModal" class="modal">
    <img id="modalImg" src="">
  </div>

  <script>
    const modal = document.getElementById('imgModal');
    const modalImg = document.getElementById('modalImg');
    const images = document.querySelectorAll('.content-img');

    images.forEach(img => {
      img.addEventListener('click', () => {
        modal.style.display = 'flex';
        modalImg.src = img.src;
      });
    });

    modal.addEventListener('click', () => {
      modal.style.display = 'none';
      modalImg.src = '';
    });
  </script>
</body>
</html>
