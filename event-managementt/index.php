<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EventSphere — Ultimate Event Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <style>
    /* Base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary: #ff6b6b;
      --secondary: #6a11cb;
      --accent: #ffd93d;
      --dark: #111;
      --light: #f8f9fa;
      --transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: #333;
      overflow-x: hidden;
      background: var(--light);
    }

    /* Background image with parallax */
    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 120%;
      z-index: -2;
      background: url('https://images.unsplash.com/photo-1530103862676-de8c9debad1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
      transform: translateZ(0);
      will-change: transform;
      filter: blur(12px) brightness(0.8);
      transform: scale(1.1);
    }

    .bg-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(106, 17, 203, 0.5), rgba(255, 107, 107, 0.5));
      z-index: -1;
      backdrop-filter: blur(3px);
    }

    /* Particle Background */
    #particles-js {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: -1;
    }

    /* Fixed Navigation with Enhanced Glow Effect - White Translucent */
    .nav-3d {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.2rem 2.5rem;
      background: rgba(255, 255, 255, 0.85); /* Changed to white translucent */
      color: #333; /* Changed to dark text */
      backdrop-filter: blur(15px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Lighter shadow */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      transition: var(--transition);
      transform-style: preserve-3d;
      perspective: 1000px;
    }

    .nav-3d.scrolled {
      padding: 0.8rem 2.5rem;
      background: rgba(255, 255, 255, 0.92); /* Slightly more opaque when scrolled */
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15); /* Lighter shadow */
    }

    .nav-3d .logo {
      font-size: 1.8rem;
      display: flex;
      align-items: center;
      gap: 10px;
      transform: translateZ(20px);
      color: #333; /* Dark text for logo */
    }

    .logo-icon {
      font-size: 2.2rem;
      color: var(--primary);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    .nav-3d nav {
      display: flex;
      gap: 1.8rem;
    }

    .nav-3d nav a {
      color: #333; /* Changed to dark text */
      text-decoration: none;
      font-weight: 600;
      display: inline-block;
      transition: var(--transition);
      text-shadow: 1px 1px 3px rgba(0,0,0,0.1); /* Lighter shadow */
      position: relative;
      padding: 8px 16px;
      cursor: pointer;
      transform-style: preserve-3d;
      border-radius: 8px;
      overflow: hidden;
    }

    .nav-3d nav a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 107, 107, 0.4), transparent);
      transition: left 0.7s ease;
      z-index: -1;
    }

    .nav-3d nav a:hover::before {
      left: 100%;
    }

    .nav-3d nav a::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 8px;
      z-index: -2;
      opacity: 0;
      transition: all 0.4s ease;
      box-shadow: 0 0 20px rgba(255, 107, 107, 0.6);
    }

    .nav-3d nav a:hover::after, .nav-3d nav a.active::after {
      opacity: 1;
      box-shadow: 0 0 30px rgba(255, 107, 107, 0.8), 0 0 60px rgba(106, 17, 203, 0.4);
    }

    .nav-3d nav a:hover, .nav-3d nav a.active {
      color: white; /* White text on hover for contrast */
      transform: translateY(-3px) scale(1.05);
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
    }

    /* Mobile Menu Button */
    .menu-toggle {
      display: none;
      background: none;
      border: none;
      color: #333; /* Dark color for menu button */
      font-size: 1.8rem;
      cursor: pointer;
      padding: 5px;
      transition: transform 0.3s;
    }

    .menu-toggle:hover {
      transform: scale(1.1);
    }

    /* Mobile Navigation */
    .mobile-nav {
      position: fixed;
      top: 0;
      right: -100%;
      width: 280px;
      height: 100vh;
      background: rgba(255, 255, 255, 0.95); /* White translucent */
      z-index: 1001;
      padding: 5rem 2rem 2rem;
      transition: right 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1); /* Lighter shadow */
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      backdrop-filter: blur(15px);
    }

    .mobile-nav.active {
      right: 0;
    }

    .mobile-nav a {
      color: #333; /* Dark text */
      text-decoration: none;
      font-weight: 600;
      font-size: 1.2rem;
      padding: 12px 15px;
      border-radius: 8px;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transform: translateX(20px);
      opacity: 0;
      position: relative;
      overflow: hidden;
    }

    .mobile-nav.active a {
      transform: translateX(0);
      opacity: 1;
      transition: all 0.5s ease;
    }

    .mobile-nav a:nth-child(1) { transition-delay: 0.1s; }
    .mobile-nav a:nth-child(2) { transition-delay: 0.2s; }
    .mobile-nav a:nth-child(3) { transition-delay: 0.3s; }
    .mobile-nav a:nth-child(4) { transition-delay: 0.4s; }
    .mobile-nav a:nth-child(5) { transition-delay: 0.5s; }
    .mobile-nav a:nth-child(6) { transition-delay: 0.6s; }

    .mobile-nav a i {
      width: 25px;
      text-align: center;
    }

    .mobile-nav a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 107, 107, 0.3), transparent);
      transition: left 0.7s ease;
      z-index: -1;
    }

    .mobile-nav a:hover::before {
      left: 100%;
    }

    .mobile-nav a::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 8px;
      z-index: -2;
      opacity: 0;
      transition: all 0.4s ease;
      box-shadow: 0 0 15px rgba(255, 107, 107, 0.5);
    }

    .mobile-nav a:hover, .mobile-nav a.active {
      background: rgba(0, 0, 0, 0.05); /* Light hover background */
      color: white; /* White text on hover for contrast */
      transform: translateX(5px) scale(1.05);
    }

    .mobile-nav a:hover::after, .mobile-nav a.active::after {
      opacity: 1;
      box-shadow: 0 0 20px rgba(255, 107, 107, 0.7);
    }

    .close-menu {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      background: none;
      border: none;
      color: #333; /* Dark color for close button */
      font-size: 1.8rem;
      cursor: pointer;
      padding: 5px;
      transition: transform 0.3s;
    }

    .close-menu:hover {
      transform: scale(1.1);
      color: var(--primary);
    }

    /* Overlay for mobile menu */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s;
    }

    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Main Content */
    .main-content {
      margin-top: 100px;
      padding: 2rem;
      max-width: 1400px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Welcome Card with 3D effect - Only visible on Home */
    .welcome-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
      color: #222;
      padding: 3rem;
      margin: 3rem auto;
      max-width: 800px;
      text-align: center;
      border-radius: 25px;
      box-shadow: 0 25px 50px rgba(0,0,0,0.15);
      transition: var(--transition);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      transform-style: preserve-3d;
      transform: perspective(1000px) rotateX(5deg);
      opacity: 0;
      animation: cardEntrance 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards 0.3s;
    }

    @keyframes cardEntrance {
      0% {
        opacity: 0;
        transform: perspective(1000px) rotateX(10deg) translateY(50px) scale(0.9);
      }
      100% {
        opacity: 1;
        transform: perspective(1000px) rotateX(0) translateY(0) scale(1);
      }
    }

    .welcome-card:hover {
      transform: perspective(1000px) rotateX(0) translateY(-10px) scale(1.02);
      box-shadow: 0 35px 70px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.5);
    }

    .welcome-card h1 {
      font-size: 3rem;
      margin-bottom: 1.2rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .welcome-card p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      color: #555;
    }

    .btn {
      display: inline-block;
      padding: 15px 35px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      transition: var(--transition);
      box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      border: none;
      font-size: 1.1rem;
      letter-spacing: 1px;
      transform-style: preserve-3d;
    }

    .btn:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 15px 25px rgba(255, 107, 107, 0.4);
    }

    /* Section Titles */
    .section-title {
      text-align: center;
      font-size: 2.5rem;
      margin: 3rem 0 2rem;
      color: white;
      text-shadow: 0 0 20px rgba(255, 255, 255, 0.5), 0 0 40px rgba(255, 107, 107, 0.3);
      position: relative;
      animation: titleGlow 3s ease-in-out infinite alternate;
    }

    @keyframes titleGlow {
      0% { text-shadow: 0 0 20px rgba(255, 255, 255, 0.5), 0 0 40px rgba(255, 107, 107, 0.3); }
      100% { text-shadow: 0 0 30px rgba(255, 255, 255, 0.8), 0 0 60px rgba(255, 107, 107, 0.5); }
    }

    .section-title::after {
      content: '';
      position: absolute;
      width: 150px;
      height: 4px;
      background: linear-gradient(90deg, transparent, var(--accent), transparent);
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 10px;
      animation: linePulse 2s ease-in-out infinite;
    }

    @keyframes linePulse {
      0%, 100% { width: 80px; opacity: 0.7; }
      50% { width: 150px; opacity: 1; }
    }

    /* Split Screen Slider - Smaller Size */
    .split-slider-section {
      padding: 1.5rem 0;
      margin: 2rem 0;
    }

    .split-swiper {
      width: 100%;
      height: 450px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .split-slide {
      display: grid;
      grid-template-columns: 1fr 1fr;
      height: 100%;
    }

    .split-image {
      position: relative;
      overflow: hidden;
    }

    .split-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .split-slide:hover .split-image img {
      transform: scale(1.1);
    }

    .split-content {
      background: linear-gradient(135deg, rgba(17, 17, 17, 0.9), rgba(34, 34, 34, 0.9));
      color: white;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .split-content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, transparent 30%, rgba(255, 107, 107, 0.1) 50%, transparent 70%);
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .split-title {
      font-size: 1.8rem;
      margin-bottom: 0.8rem;
      color: var(--accent);
      text-shadow: 0 0 15px rgba(255, 217, 61, 0.5);
      transform: translateX(-50px);
      opacity: 0;
      transition: all 0.8s ease;
    }

    .split-slide-active .split-title {
      transform: translateX(0);
      opacity: 1;
    }

    .split-description {
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 1.5rem;
      transform: translateX(50px);
      opacity: 0;
      transition: all 0.8s ease 0.2s;
    }

    .split-slide-active .split-description {
      transform: translateX(0);
      opacity: 1;
    }

    .split-features {
      list-style: none;
      transform: translateY(30px);
      opacity: 0;
      transition: all 0.8s ease 0.4s;
    }

    .split-slide-active .split-features {
      transform: translateY(0);
      opacity: 1;
    }

    .split-features li {
      margin-bottom: 0.6rem;
      padding-left: 1.5rem;
      position: relative;
      font-size: 0.9rem;
    }

    .split-features li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--accent);
      font-weight: bold;
    }

    /* Events Slider Section */
    .events-section {
      padding: 1.5rem 0;
      margin: 2rem 0;
    }

    .events-slider {
      position: relative;
      width: 100%;
      height: 500px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }

    .slider-container {
      width: 100%;
      height: 100%;
      position: relative;
    }

    .slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 1s ease-in-out;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
    }

    .slide.active {
      opacity: 1;
    }

    .slide-content {
      max-width: 600px;
      padding: 2rem;
      background: rgba(0, 0, 0, 0.6);
      border-radius: 15px;
      backdrop-filter: blur(10px);
      transform: translateY(50px);
      opacity: 0;
      transition: all 0.8s ease;
    }

    .slide.active .slide-content {
      transform: translateY(0);
      opacity: 1;
    }

    .slide h3 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: var(--accent);
      text-shadow: 0 0 10px rgba(255, 217, 61, 0.5);
    }

    .slide p {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .slide-date {
      display: inline-block;
      padding: 8px 20px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      border-radius: 25px;
      font-weight: 600;
      font-size: 1rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .slider-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.5);
      color: white;
      font-size: 2rem;
      padding: 10px 15px;
      cursor: pointer;
      transition: var(--transition);
      z-index: 10;
      border: none;
      backdrop-filter: blur(10px);
    }

    .slider-arrow:hover {
      background: rgba(0, 0, 0, 0.8);
      transform: translateY(-50%) scale(1.1);
    }

    .slider-arrow.prev {
      left: 20px;
    }

    .slider-arrow.next {
      right: 20px;
    }

    .slider-nav {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
      z-index: 10;
    }

    .slider-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: var(--transition);
    }

    .slider-dot.active {
      background: var(--accent);
      transform: scale(1.2);
    }

    .slider-dot:hover {
      background: var(--accent);
      transform: scale(1.1);
    }

    /* Vertical Slider Section */
    .vertical-slider-section {
      padding: 1.5rem 0;
      margin: 2rem 0;
    }

    .vertical-swiper {
      width: 100%;
      height: 600px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .vertical-slide {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
      color: white;
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .vertical-slide::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, var(--primary), var(--secondary));
      opacity: 0.1;
      z-index: -1;
    }

    .vertical-icon {
      font-size: 4rem;
      margin-bottom: 2rem;
      color: var(--accent);
      text-shadow: 0 0 20px rgba(255, 217, 61, 0.5);
      animation: iconFloat 3s ease-in-out infinite;
    }

    @keyframes iconFloat {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-10px) scale(1.1); }
    }

    .vertical-title {
      font-size: 2.2rem;
      margin-bottom: 1.5rem;
      color: white;
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
      transform: scale(0.8);
      opacity: 0;
      transition: all 0.6s ease;
    }

    .vertical-slide-active .vertical-title {
      transform: scale(1);
      opacity: 1;
    }

    .vertical-content {
      font-size: 1.1rem;
      line-height: 1.7;
      margin-bottom: 2rem;
      transform: translateY(30px);
      opacity: 0;
      transition: all 0.6s ease 0.3s;
    }

    .vertical-slide-active .vertical-content {
      transform: translateY(0);
      opacity: 1;
    }

    .vertical-stats {
      display: flex;
      gap: 2rem;
      margin-bottom: 2rem;
      transform: translateY(30px);
      opacity: 0;
      transition: all 0.6s ease 0.6s;
    }

    .vertical-slide-active .vertical-stats {
      transform: translateY(0);
      opacity: 1;
    }

    .stat-item {
      text-align: center;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: var(--accent);
      display: block;
    }

    .stat-label {
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .view-details-btn {
      display: inline-block;
      padding: 12px 30px;
      background: linear-gradient(90deg, var(--accent), #ffb347);
      color: var(--dark);
      text-decoration: none;
      border-radius: 50px;
      font-weight: 600;
      transition: var(--transition);
      box-shadow: 0 8px 15px rgba(255, 217, 61, 0.3);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      border: none;
      font-size: 1rem;
      letter-spacing: 1px;
      transform: translateY(30px);
      opacity: 0;
      transition: all 0.6s ease 0.9s;
    }

    .vertical-slide-active .view-details-btn {
      transform: translateY(0);
      opacity: 1;
    }

    .view-details-btn:hover {
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 12px 20px rgba(255, 217, 61, 0.4);
    }

    /* Events Grid Section */
    .events-grid-section {
      padding: 1.5rem 0;
      margin: 2rem 0;
    }

    .events-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .event-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8));
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: var(--transition);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .event-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .event-image {
      height: 200px;
      overflow: hidden;
    }

    .event-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.8s ease;
    }

    .event-card:hover .event-image img {
      transform: scale(1.1);
    }

    .event-content {
      padding: 1.5rem;
    }

    .event-title {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      color: #222;
    }

    .event-date {
      display: inline-block;
      padding: 5px 12px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: white;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .event-description {
      color: #555;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }

    .event-details {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }

    .event-detail {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .event-detail i {
      color: var(--primary);
    }

    .event-price {
      font-size: 1.2rem;
      font-weight: bold;
      color: var(--secondary);
      margin-bottom: 1.5rem;
    }

    .book-btn {
      display: block;
      width: 100%;
      padding: 12px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
      text-decoration: none;
    }

    .book-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 15px rgba(255, 107, 107, 0.3);
    }

    /* Booking Modal */
    .booking-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 2000;
      backdrop-filter: blur(5px);
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .booking-modal.active {
      display: flex;
      opacity: 1;
    }

    .booking-content {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
      margin: auto;
      padding: 2rem;
      border-radius: 15px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
      position: relative;
      transform: scale(0.8);
      transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(8px);
      max-height: 90vh;
      overflow-y: auto;
    }

    .booking-modal.active .booking-content {
      transform: scale(1);
    }

    .booking-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .booking-title {
      font-size: 1.8rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin: 0;
    }

    .close-booking {
      background: none;
      border: none;
      font-size: 1.8rem;
      cursor: pointer;
      color: var(--dark);
      transition: var(--transition);
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .close-booking:hover {
      background: var(--primary);
      color: white;
      transform: rotate(90deg);
    }

    .booking-form {
      display: grid;
      gap: 1.2rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #444;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 12px;
      border: 1px solid rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      font-size: 1rem;
      transition: var(--transition);
      background: rgba(255, 255, 255, 0.7);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.2);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .submit-booking {
      padding: 15px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1.1rem;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 1rem;
    }

    .submit-booking:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
    }

    /* Modal Styles - Horizontal Layout */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 2000;
      backdrop-filter: blur(5px);
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .modal.active {
      display: flex;
      opacity: 1;
    }

    .modal-content {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.8));
      margin: auto;
      padding: 2rem;
      border-radius: 15px;
      max-width: 800px;
      width: 90%;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
      position: relative;
      transform: scale(0.8);
      transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      border: 1px solid rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(8px);
      display: flex;
      flex-direction: row;
      gap: 2rem;
      max-height: 80vh;
      overflow-y: auto;
    }

    .modal.active .modal-content {
      transform: scale(1);
    }

    .modal-left {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .modal-right {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .modal-title {
      font-size: 1.8rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin: 0;
    }

    .close-modal {
      background: none;
      border: none;
      font-size: 1.8rem;
      cursor: pointer;
      color: var(--dark);
      transition: var(--transition);
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .close-modal:hover {
      background: var(--primary);
      color: white;
      transform: rotate(90deg);
    }

    .modal-icon {
      font-size: 3rem;
      text-align: center;
      margin-bottom: 1.2rem;
      animation: iconFloat 3s ease-in-out infinite;
    }

    .modal-description {
      font-size: 1rem;
      line-height: 1.6;
      margin-bottom: 1.5rem;
      color: #555;
    }

    .modal-stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .modal-stat {
      text-align: center;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
    }

    .modal-stat:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .modal-stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: var(--primary);
      display: block;
      margin-bottom: 0.3rem;
    }

    .modal-stat-label {
      font-size: 0.8rem;
      color: #666;
      font-weight: 600;
    }

    .modal-features {
      list-style: none;
      margin-bottom: 1.5rem;
    }

    .modal-features li {
      padding: 0.6rem 0;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      position: relative;
      padding-left: 1.8rem;
      font-size: 0.9rem;
    }

    .modal-features li:last-child {
      border-bottom: none;
    }

    .modal-features li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--accent);
      font-weight: bold;
      font-size: 1.1rem;
    }

    /* Content Sections */
    .content-section {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.85) 0%, rgba(255, 255, 255, 0.75) 100%);
      color: #222;
      padding: 2.5rem;
      margin: 1.5rem auto;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      display: none;
      opacity: 0;
      transform: translateY(30px) scale(0.95);
      transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .content-section.active {
      display: block;
      opacity: 1;
      transform: translateY(0) scale(1);
    }

    .content-section h2 {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .content-section p {
      margin-bottom: 1.2rem;
      font-size: 1.1rem;
      line-height: 1.7;
    }

    .content-section ul {
      margin-left: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .content-section li {
      margin-bottom: 0.8rem;
    }

    /* Floating Elements */
    .floating-elements {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    }

    .floating-element {
      position: absolute;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 15s infinite linear;
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    }

    @keyframes float {
      0% {
        transform: translateY(0) translateX(0) rotate(0deg);
      }
      25% {
        transform: translateY(-15px) translateX(8px) rotate(90deg);
      }
      50% {
        transform: translateY(0) translateX(15px) rotate(180deg);
      }
      75% {
        transform: translateY(15px) translateX(8px) rotate(270deg);
      }
      100% {
        transform: translateY(0) translateX(0) rotate(360deg);
      }
    }

    /* Footer - White Translucent */
    .site-footer {
      text-align: center;
      padding: 1.5rem;
      background: rgba(255, 255, 255, 0.85); /* Changed to white translucent */
      color: #333; /* Changed to dark text */
      margin-top: 2rem;
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
    }

    .site-footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    /* India Service Banner */
    .india-banner {
      background: linear-gradient(90deg, #ff9933, #ffffff, #138808);
      color: #333;
      text-align: center;
      padding: 1rem;
      font-weight: bold;
      font-size: 1.2rem;
      margin-bottom: 2rem;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .nav-3d {
        padding: 1rem;
      }
      
      .nav-3d nav {
        display: none;
      }
      
      .menu-toggle {
        display: block;
      }
      
      .welcome-card {
        margin: 2rem 1rem;
        padding: 2rem;
      }
      
      .welcome-card h1 {
        font-size: 2.2rem;
      }
      
      .section-title {
        font-size: 2rem;
        margin: 2.5rem 0 1.5rem;
      }
      
      .split-swiper {
        height: 350px;
      }
      
      .split-slide {
        grid-template-columns: 1fr;
      }
      
      .split-content {
        padding: 1.5rem;
      }
      
      .split-title {
        font-size: 1.5rem;
      }
      
      .events-slider {
        height: 400px;
      }
      
      .slide h3 {
        font-size: 2rem;
      }
      
      .slide p {
        font-size: 1rem;
      }
      
      .vertical-swiper {
        height: 500px;
      }
      
      .vertical-title {
        font-size: 1.8rem;
      }
      
      .vertical-stats {
        flex-direction: column;
        gap: 1rem;
      }
      
      .events-grid {
        grid-template-columns: 1fr;
      }
      
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .modal-stats {
        grid-template-columns: 1fr;
      }
      
      .modal-content {
        padding: 1.5rem;
        margin: 1rem;
        flex-direction: column;
      }
      
      .content-section {
        padding: 1.5rem 1.2rem;
      }
    }

    @media (max-width: 480px) {
      .section-title {
        font-size: 1.6rem;
      }
      
      .split-swiper {
        height: 300px;
      }
      
      .events-slider {
        height: 350px;
      }
      
      .slide h3 {
        font-size: 1.6rem;
      }
      
      .slide p {
        font-size: 0.9rem;
      }
      
      .vertical-swiper {
        height: 450px;
      }
      
      .vertical-slide {
        padding: 2rem;
      }
      
      .modal-title {
        font-size: 1.5rem;
      }
      
      .modal-icon {
        font-size: 2.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="bg-image"></div>
  <div class="bg-overlay"></div>
  <div id="particles-js"></div>
  <div class="floating-elements" id="floatingElements"></div>

  <!-- Fixed Navigation -->
  <header class="nav-3d">
    <div class="logo">
      <span class="logo-icon">🌎</span>
      <strong>EventSphere</strong>
    </div>
    
    <!-- Desktop Navigation -->
    <nav id="mainNav">
      <a class="active" data-section="home">Home</a>
      <a data-section="events">Events</a>
      <a data-section="about">About Us</a>
      <a data-section="contact">Contact</a>
      <a href="user_login.php">User Login</a>
      <a href="admin_login.php">Admin Login</a>
    </nav>
    
    <!-- Mobile Menu Button -->
    <button class="menu-toggle" id="menuToggle">
      <i class="fas fa-bars"></i>
    </button>
  </header>

  <!-- Mobile Navigation -->
  <div class="mobile-nav" id="mobileNav">
    <button class="close-menu" id="closeMenu">
      <i class="fas fa-times"></i>
    </button>
    <a class="active" data-section="home"><i class="fas fa-home"></i> Home</a>
    <a data-section="events"><i class="fas fa-calendar-alt"></i> Events</a>
    <a data-section="about"><i class="fas fa-info-circle"></i> About Us</a>
    <a data-section="contact"><i class="fas fa-envelope"></i> Contact</a>
    <a href="user_login.php"><i class="fas fa-user"></i> User Login</a>
    <a href="admin_login.php"><i class="fas fa-user-shield"></i> Admin Login</a>
  </div>

  <!-- Overlay for mobile menu -->
  <div class="overlay" id="overlay"></div>

  <main class="main-content">
    <!-- India Service Banner -->
    <div class="india-banner">
      <i class="fas fa-flag"></i> Our Services Available All Over India! <i class="fas fa-flag"></i>
    </div>

    <!-- Home Section (Welcome Card) -->
    <div id="home-section" class="welcome-card">
      <h1>Welcome to EventSphere 🎪</h1>
      <p>Discover amazing events and manage them with ease. From concerts to conferences, we've got you covered!</p>
      <p><strong>Email:</strong> info@eventsphere.in | <strong>Location:</strong> Mumbai, Delhi, Bangalore, Hyderabad & All Major Cities</p>
      <button class="btn" onclick="exploreEvents()">Explore Events</button>
    </div>
    
    <!-- Split Screen Slider -->
    <section class="split-slider-section">
      <h2 class="section-title">Premium Event Experiences</h2>
      <div class="swiper split-swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide split-slide">
            <div class="split-image">
              <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Music Festival">
            </div>
            <div class="split-content">
              <h3 class="split-title">Music Festival Excellence</h3>
              <p class="split-description">Experience the ultimate music festivals with world-class production, seamless logistics, and unforgettable performances.</p>
              <ul class="split-features">
                <li>Multi-stage management</li>
                <li>Artist coordination</li>
                <li>Crowd flow optimization</li>
                <li>Sound & lighting integration</li>
              </ul>
            </div>
          </div>
          <div class="swiper-slide split-slide">
            <div class="split-image">
              <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Tech Conference">
            </div>
            <div class="split-content">
              <h3 class="split-title">Tech Conference Solutions</h3>
              <p class="split-description">Power your technology conferences with advanced features including live streaming, interactive sessions, and networking tools.</p>
              <ul class="split-features">
                <li>Live streaming integration</li>
                <li>Interactive Q&A sessions</li>
                <li>Networking algorithms</li>
                <li>Sponsor management</li>
              </ul>
            </div>
          </div>
          <div class="swiper-slide split-slide">
            <div class="split-image">
              <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Corporate Events">
            </div>
            <div class="split-content">
              <h3 class="split-title">Corporate Event Mastery</h3>
              <p class="split-description">Elevate your corporate events with professional planning tools, attendee engagement features, and comprehensive reporting.</p>
              <ul class="split-features">
                <li>Brand customization</li>
                <li>Attendee engagement tools</li>
                <li>ROI tracking</li>
                <li>Team collaboration</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </section>

    <!-- Events Slider Section -->
    <section class="events-section">
      <h2 class="section-title">Featured Events</h2>
      <div class="events-slider">
        <div class="slider-container">
          <!-- Slide 1 -->
          <div class="slide active" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Summer Music Festival</h3>
              <p>Join us for the biggest music event of the year with top artists from around the world.</p>
              <span class="slide-date">June 15-17, 2023</span>
            </div>
          </div>
          <!-- Slide 2 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1531058020387-3be344556be6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Tech Conference 2023</h3>
              <p>Explore the latest innovations in technology with industry leaders and innovators.</p>
              <span class="slide-date">July 22-24, 2023</span>
            </div>
          </div>
          <!-- Slide 3 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Art Exhibition</h3>
              <p>Experience breathtaking artworks from emerging and established artists.</p>
              <span class="slide-date">August 5-20, 2023</span>
            </div>
          </div>
          <!-- Slide 4 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Food & Wine Festival</h3>
              <p>Taste exquisite dishes and fine wines from top chefs and vineyards.</p>
              <span class="slide-date">September 8-10, 2023</span>
            </div>
          </div>
          <!-- Slide 5 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Marathon Run</h3>
              <p>Challenge yourself in our annual city marathon with different race categories.</p>
              <span class="slide-date">October 14, 2023</span>
            </div>
          </div>
          <!-- Slide 6 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Business Summit</h3>
              <p>Network with industry leaders and gain insights into future business trends.</p>
              <span class="slide-date">November 5-7, 2023</span>
            </div>
          </div>
          <!-- Slide 7 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Jazz Night</h3>
              <p>An intimate evening with world-class jazz musicians in a cozy setting.</p>
              <span class="slide-date">December 2, 2023</span>
            </div>
          </div>
          <!-- Slide 8 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Film Festival</h3>
              <p>Celebrate independent cinema with screenings, Q&As, and workshops.</p>
              <span class="slide-date">January 12-15, 2024</span>
            </div>
          </div>
          <!-- Slide 9 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1551818255-e6e10975bc17?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Comedy Night</h3>
              <p>Laugh the night away with top comedians from Netflix and Comedy Central.</p>
              <span class="slide-date">February 10, 2024</span>
            </div>
          </div>
          <!-- Slide 10 -->
          <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover;">
            <div class="slide-content">
              <h3>Yoga Retreat</h3>
              <p>Rejuvenate your mind and body with yoga, meditation, and healthy cuisine.</p>
              <span class="slide-date">March 15-17, 2024</span>
            </div>
          </div>
        </div>
        <div class="slider-arrow prev">&#10094;</div>
        <div class="slider-arrow next">&#10095;</div>
        <div class="slider-nav">
          <div class="slider-dot active"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
          <div class="slider-dot"></div>
        </div>
      </div>
    </section>

    <!-- Events Grid Section -->
    <section id="events-section" class="events-grid-section content-section">
      <h2 class="section-title">All Events</h2>
      <div class="events-grid">
        <!-- Event 1 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Summer Music Festival">
          </div>
          <div class="event-content">
            <h3 class="event-title">Summer Music Festival</h3>
            <span class="event-date">June 15-17, 2023</span>
            <p class="event-description">Join us for the biggest music event of the year with top artists from around the world.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Central Park, NYC</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 3:00 PM - 11:00 PM</div>
            </div>
            <div class="event-price">$89 - $249</div>
            <button class="book-btn" onclick="openBooking('Summer Music Festival')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 2 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Tech Conference 2023">
          </div>
          <div class="event-content">
            <h3 class="event-title">Tech Conference 2023</h3>
            <span class="event-date">July 22-24, 2023</span>
            <p class="event-description">Explore the latest innovations in technology with industry leaders and innovators.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Convention Center, SF</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 9:00 AM - 6:00 PM</div>
            </div>
            <div class="event-price">$299 - $599</div>
            <button class="book-btn" onclick="openBooking('Tech Conference 2023')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 3 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Art Exhibition">
          </div>
          <div class="event-content">
            <h3 class="event-title">Art Exhibition</h3>
            <span class="event-date">August 5-20, 2023</span>
            <p class="event-description">Experience breathtaking artworks from emerging and established artists.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Modern Art Museum, LA</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 10:00 AM - 8:00 PM</div>
            </div>
            <div class="event-price">$15 - $25</div>
            <button class="book-btn" onclick="openBooking('Art Exhibition')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 4 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Food & Wine Festival">
          </div>
          <div class="event-content">
            <h3 class="event-title">Food & Wine Festival</h3>
            <span class="event-date">September 8-10, 2023</span>
            <p class="event-description">Taste exquisite dishes and fine wines from top chefs and vineyards.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Waterfront Park, Chicago</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 12:00 PM - 10:00 PM</div>
            </div>
            <div class="event-price">$45 - $120</div>
            <button class="book-btn" onclick="openBooking('Food & Wine Festival')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 5 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Marathon Run">
          </div>
          <div class="event-content">
            <h3 class="event-title">Marathon Run</h3>
            <span class="event-date">October 14, 2023</span>
            <p class="event-description">Challenge yourself in our annual city marathon with different race categories.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Downtown, Boston</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 7:00 AM - 2:00 PM</div>
            </div>
            <div class="event-price">$35 - $75</div>
            <button class="book-btn" onclick="openBooking('Marathon Run')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 6 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Business Summit">
          </div>
          <div class="event-content">
            <h3 class="event-title">Business Summit</h3>
            <span class="event-date">November 5-7, 2023</span>
            <p class="event-description">Network with industry leaders and gain insights into future business trends.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Financial District, NYC</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 8:00 AM - 5:00 PM</div>
            </div>
            <div class="event-price">$399 - $899</div>
            <button class="book-btn" onclick="openBooking('Business Summit')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 7 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Jazz Night">
          </div>
          <div class="event-content">
            <h3 class="event-title">Jazz Night</h3>
            <span class="event-date">December 2, 2023</span>
            <p class="event-description">An intimate evening with world-class jazz musicians in a cozy setting.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Blue Note Club, NYC</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 8:00 PM - 11:00 PM</div>
            </div>
            <div class="event-price">$40 - $80</div>
            <button class="book-btn" onclick="openBooking('Jazz Night')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 8 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Film Festival">
          </div>
          <div class="event-content">
            <h3 class="event-title">Independent Film Festival</h3>
            <span class="event-date">January 12-15, 2024</span>
            <p class="event-description">Celebrate independent cinema with screenings, Q&As, and workshops.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Downtown Cinema, Austin</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 10:00 AM - 11:00 PM</div>
            </div>
            <div class="event-price">$25 - $75</div>
            <button class="book-btn" onclick="openBooking('Independent Film Festival')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 9 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1551818255-e6e10975bc17?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Comedy Show">
          </div>
          <div class="event-content">
            <h3 class="event-title">Comedy Night</h3>
            <span class="event-date">February 10, 2024</span>
            <p class="event-description">Laugh the night away with top comedians from Netflix and Comedy Central.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Comedy Cellar, Chicago</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 7:30 PM - 10:00 PM</div>
            </div>
            <div class="event-price">$25 - $60</div>
            <button class="book-btn" onclick="openBooking('Comedy Night')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 10 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Yoga Retreat">
          </div>
          <div class="event-content">
            <h3 class="event-title">Wellness Yoga Retreat</h3>
            <span class="event-date">March 15-17, 2024</span>
            <p class="event-description">Rejuvenate your mind and body with yoga, meditation, and healthy cuisine.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Mountain Resort, Colorado</div>
              <div class="event-detail"><i class="fas fa-clock"></i> All Day</div>
            </div>
            <div class="event-price">$299 - $499</div>
            <button class="book-btn" onclick="openBooking('Wellness Yoga Retreat')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 11 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1527525443983-6e60c75fff46?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Startup Pitch">
          </div>
          <div class="event-content">
            <h3 class="event-title">Startup Pitch Competition</h3>
            <span class="event-date">April 5, 2024</span>
            <p class="event-description">Watch promising startups pitch to top investors for funding opportunities.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Innovation Hub, SF</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 9:00 AM - 5:00 PM</div>
            </div>
            <div class="event-price">$50 - $150</div>
            <button class="book-btn" onclick="openBooking('Startup Pitch Competition')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 12 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Wine Tasting">
          </div>
          <div class="event-content">
            <h3 class="event-title">Wine Tasting Experience</h3>
            <span class="event-date">May 20, 2024</span>
            <p class="event-description">Sample exquisite wines from renowned vineyards with expert sommeliers.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Vineyard Estate, Napa Valley</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 2:00 PM - 6:00 PM</div>
            </div>
            <div class="event-price">$75 - $150</div>
            <button class="book-btn" onclick="openBooking('Wine Tasting Experience')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 13 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1519677100203-a0e668c92439?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Photography Workshop">
          </div>
          <div class="event-content">
            <h3 class="event-title">Photography Workshop</h3>
            <span class="event-date">June 8, 2024</span>
            <p class="event-description">Learn professional photography techniques from award-winning photographers.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Art Center, Mumbai</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 10:00 AM - 4:00 PM</div>
            </div>
            <div class="event-price">₹2,500 - ₹5,000</div>
            <button class="book-btn" onclick="openBooking('Photography Workshop')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 14 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Culinary Masterclass">
          </div>
          <div class="event-content">
            <h3 class="event-title">Culinary Masterclass</h3>
            <span class="event-date">July 15, 2024</span>
            <p class="event-description">Cook with celebrity chefs and learn gourmet cooking techniques.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Culinary Institute, Delhi</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 11:00 AM - 3:00 PM</div>
            </div>
            <div class="event-price">₹3,000 - ₹6,000</div>
            <button class="book-btn" onclick="openBooking('Culinary Masterclass')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 15 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Digital Marketing Summit">
          </div>
          <div class="event-content">
            <h3 class="event-title">Digital Marketing Summit</h3>
            <span class="event-date">August 22-23, 2024</span>
            <p class="event-description">Master digital marketing strategies with industry experts and case studies.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Convention Center, Bangalore</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 9:00 AM - 6:00 PM</div>
            </div>
            <div class="event-price">₹8,000 - ₹15,000</div>
            <button class="book-btn" onclick="openBooking('Digital Marketing Summit')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 16 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Gaming Tournament">
          </div>
          <div class="event-content">
            <h3 class="event-title">Esports Gaming Tournament</h3>
            <span class="event-date">September 10-12, 2024</span>
            <p class="event-description">Compete in the biggest gaming tournament with massive prize pools.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Arena Stadium, Hyderabad</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 10:00 AM - 10:00 PM</div>
            </div>
            <div class="event-price">₹500 - ₹2,000</div>
            <button class="book-btn" onclick="openBooking('Esports Gaming Tournament')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 17 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Fitness Expo">
          </div>
          <div class="event-content">
            <h3 class="event-title">Fitness & Wellness Expo</h3>
            <span class="event-date">October 5-7, 2024</span>
            <p class="event-description">Explore latest fitness trends, equipment, and wellness practices.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Exhibition Center, Chennai</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 9:00 AM - 8:00 PM</div>
            </div>
            <div class="event-price">₹300 - ₹800</div>
            <button class="book-btn" onclick="openBooking('Fitness & Wellness Expo')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 18 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Book Fair">
          </div>
          <div class="event-content">
            <h3 class="event-title">International Book Fair</h3>
            <span class="event-date">November 15-25, 2024</span>
            <p class="event-description">Discover books from around the world and meet your favorite authors.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Trade Center, Kolkata</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 10:00 AM - 9:00 PM</div>
            </div>
            <div class="event-price">₹100 - ₹500</div>
            <button class="book-btn" onclick="openBooking('International Book Fair')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 19 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Music Concert">
          </div>
          <div class="event-content">
            <h3 class="event-title">Bollywood Night Concert</h3>
            <span class="event-date">December 20, 2024</span>
            <p class="event-description">Experience the magic of Bollywood with live performances by top artists.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Stadium, Pune</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 6:00 PM - 11:00 PM</div>
            </div>
            <div class="event-price">₹1,500 - ₹5,000</div>
            <button class="book-btn" onclick="openBooking('Bollywood Night Concert')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 20 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1561489396-888724a1543d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="New Year Party">
          </div>
          <div class="event-content">
            <h3 class="event-title">New Year's Eve Gala</h3>
            <span class="event-date">December 31, 2024</span>
            <p class="event-description">Welcome the new year with an extravagant party, fireworks, and live DJ.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Luxury Hotel, Goa</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 8:00 PM - 2:00 AM</div>
            </div>
            <div class="event-price">₹3,000 - ₹10,000</div>
            <button class="book-btn" onclick="openBooking('New Year's Eve Gala')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 21 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1519677100203-a0e668c92439?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Startup Networking">
          </div>
          <div class="event-content">
            <h3 class="event-title">Startup Networking Mixer</h3>
            <span class="event-date">January 18, 2025</span>
            <p class="event-description">Connect with entrepreneurs, investors, and industry leaders.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Business Hub, Gurgaon</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 6:00 PM - 9:00 PM</div>
            </div>
            <div class="event-price">₹1,000 - ₹2,500</div>
            <button class="book-btn" onclick="openBooking('Startup Networking Mixer')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 22 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Art & Craft Fair">
          </div>
          <div class="event-content">
            <h3 class="event-title">Art & Craft Fair</h3>
            <span class="event-date">February 14-16, 2025</span>
            <p class="event-description">Explore unique handmade crafts, artworks, and meet local artisans.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Cultural Center, Jaipur</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 10:00 AM - 8:00 PM</div>
            </div>
            <div class="event-price">Free Entry</div>
            <button class="book-btn" onclick="openBooking('Art & Craft Fair')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 23 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Science Exhibition">
          </div>
          <div class="event-content">
            <h3 class="event-title">Science & Technology Exhibition</h3>
            <span class="event-date">March 8-10, 2025</span>
            <p class="event-description">Discover cutting-edge innovations and interactive science exhibits.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Science Center, Ahmedabad</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 9:00 AM - 6:00 PM</div>
            </div>
            <div class="event-price">₹200 - ₹500</div>
            <button class="book-btn" onclick="openBooking('Science & Technology Exhibition')">Book Now</button>
          </div>
        </div>
        
        <!-- Event 24 -->
        <div class="event-card">
          <div class="event-image">
            <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Food Festival">
          </div>
          <div class="event-content">
            <h3 class="event-title">Street Food Festival</h3>
            <span class="event-date">April 12-14, 2025</span>
            <p class="event-description">Taste delicious street food from different regions of India and beyond.</p>
            <div class="event-details">
              <div class="event-detail"><i class="fas fa-map-marker-alt"></i> Food Street, Delhi</div>
              <div class="event-detail"><i class="fas fa-clock"></i> 11:00 AM - 11:00 PM</div>
            </div>
            <div class="event-price">₹200 Entry</div>
            <button class="book-btn" onclick="openBooking('Street Food Festival')">Book Now</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Vertical Slider -->
    <section class="vertical-slider-section">
      <h2 class="section-title">EventSphere Success Metrics</h2>
      <div class="swiper vertical-swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide vertical-slide">
            <div class="vertical-icon">🚀</div>
            <h3 class="vertical-title">Rapid Growth</h3>
            <p class="vertical-content">EventSphere has experienced exponential growth, serving thousands of events worldwide with our cutting-edge platform.</p>
            <div class="vertical-stats">
              <div class="stat-item">
                <span class="stat-number">500%</span>
                <span class="stat-label">Growth Rate</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">50K+</span>
                <span class="stat-label">Events Hosted</span>
              </div>
            </div>
            <button class="view-details-btn" onclick="openModal('growth')">View Details</button>
          </div>
          <div class="swiper-slide vertical-slide">
            <div class="vertical-icon">⭐</div>
            <h3 class="vertical-title">Customer Satisfaction</h3>
            <p class="vertical-content">Our users love EventSphere! We maintain exceptional satisfaction ratings across all our services and features.</p>
            <div class="vertical-stats">
              <div class="stat-item">
                <span class="stat-number">4.9/5</span>
                <span class="stat-label">User Rating</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">98%</span>
                <span class="stat-label">Retention Rate</span>
              </div>
            </div>
            <button class="view-details-btn" onclick="openModal('satisfaction')">View Details</button>
          </div>
          <div class="swiper-slide vertical-slide">
            <div class="vertical-icon">🌍</div>
            <h3 class="vertical-title">Global Reach</h3>
            <p class="vertical-content">From local gatherings to international conferences, EventSphere powers events across the globe with localized support.</p>
            <div class="vertical-stats">
              <div class="stat-item">
                <span class="stat-number">75+</span>
                <span class="stat-label">Countries</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">1M+</span>
                <span class="stat-label">Attendees</span>
              </div>
            </div>
            <button class="view-details-btn" onclick="openModal('global')">View Details</button>
          </div>
          <div class="swiper-slide vertical-slide">
            <div class="vertical-icon">⚡</div>
            <h3 class="vertical-title">Performance</h3>
            <p class="vertical-content">Lightning-fast performance and 99.9% uptime ensure your events run smoothly without any technical interruptions.</p>
            <div class="vertical-stats">
              <div class="stat-item">
                <span class="stat-number">99.9%</span>
                <span class="stat-label">Uptime</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">0.2s</span>
                <span class="stat-label">Load Time</span>
              </div>
            </div>
            <button class="view-details-btn" onclick="openModal('performance')">View Details</button>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <!-- About Us Section -->
    <section id="about-section" class="content-section">
      <h2>About EventSphere</h2>
      <p>EventSphere is a comprehensive event management platform designed to simplify the process of planning, organizing, and executing events of all sizes. Founded in 2018, we've grown to become a trusted partner for event organizers worldwide.</p>
      
      <p>Our platform offers a wide range of features including event registration, ticketing, attendee management, marketing tools, and analytics. We serve event organizers across various industries including corporate events, conferences, music festivals, and more.</p>
      
      <h3>Our Mission</h3>
      <p>To empower event organizers with innovative technology that simplifies event management while creating unforgettable experiences for attendees.</p>
      
      <h3>Our Vision</h3>
      <p>To become the world's leading event management platform, connecting people through memorable experiences.</p>
      
      <h3>Our Values</h3>
      <ul>
        <li><strong>Innovation:</strong> Constantly pushing boundaries to deliver cutting-edge solutions</li>
        <li><strong>Reliability:</strong> Providing stable, secure, and dependable services</li>
        <li><strong>Customer Focus:</strong> Putting our clients' success at the center of everything we do</li>
        <li><strong>Collaboration:</strong> Working together to achieve extraordinary results</li>
      </ul>
      
      <p>With EventSphere, you can create memorable experiences for your attendees while streamlining your event management processes. Our team of experts is dedicated to providing exceptional support and ensuring your events run smoothly from start to finish.</p>
    </section>

    <!-- Contact Section -->
    <section id="contact-section" class="content-section">
      <h2>Contact Us</h2>
      <p>We'd love to hear from you! Whether you have questions about our services, need support with an existing event, or want to discuss a potential partnership, our team is here to help.</p>
      
      <h3>Get In Touch</h3>
      <p><strong>Email:</strong> info@eventsphere.in</p>
      <p><strong>Phone:</strong> +91 98765 43210</p>
      <p><strong>Address:</strong> 123 Event Street, Mumbai, Maharashtra 400001</p>
      <p><strong>Service Areas:</strong> Available all over India - Mumbai, Delhi, Bangalore, Hyderabad, Chennai, Kolkata, Pune, Ahmedabad, Jaipur, and all major cities</p>
      
      <h3>Office Hours</h3>
      <p>Our support team is available Monday through Friday, 9am to 6pm IST. For urgent matters outside these hours, please leave a message and we'll get back to you as soon as possible.</p>
      
      <h3>Sales Inquiries</h3>
      <p>Interested in learning more about our enterprise solutions? Contact our sales team at sales@eventsphere.in or call +91 98765 43211.</p>
      
      <h3>Technical Support</h3>
      <p>For technical assistance with our platform, please contact support@eventsphere.in or use the help center within your EventSphere dashboard.</p>
      
      <h3>Connect With Us</h3>
      <p>Follow us on social media for the latest updates, event management tips, and industry insights:</p>
      <ul>
        <li>Twitter: @EventSphereIndia</li>
        <li>LinkedIn: EventSphere India</li>
        <li>Instagram: @EventSphereIndia</li>
        <li>Facebook: EventSphere India</li>
      </ul>
    </section>
  </main>

  <!-- Booking Modal -->
  <div id="booking-modal" class="booking-modal">
    <div class="booking-content">
      <div class="booking-header">
        <h2 class="booking-title">Book Your Event</h2>
        <button class="close-booking" onclick="closeBooking()">&times;</button>
      </div>
      <form class="booking-form" id="booking-form">
        <div class="form-group">
          <label for="event-name">Event</label>
          <input type="text" id="event-name" readonly>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="full-name">Full Name</label>
            <input type="text" id="full-name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" required>
          </div>
          <div class="form-group">
            <label for="tickets">Number of Tickets</label>
            <select id="tickets" required>
              <option value="">Select</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="special-requests">Special Requests</label>
          <textarea id="special-requests" rows="3"></textarea>
        </div>
        <button type="submit" class="submit-booking">Complete Booking</button>
      </form>
    </div>
  </div>

  <!-- Modal Popups -->
  <div id="growth-modal" class="modal">
    <div class="modal-content">
      <div class="modal-left">
        <div class="modal-header">
          <h2 class="modal-title">Rapid Growth Details</h2>
          <button class="close-modal" onclick="closeModal('growth')">&times;</button>
        </div>
        <div class="modal-icon">🚀</div>
        <p class="modal-description">EventSphere has experienced unprecedented growth in the past year, expanding our user base and event portfolio exponentially.</p>
        <div class="modal-stats">
          <div class="modal-stat">
            <span class="modal-stat-number">500%</span>
            <span class="modal-stat-label">Year-over-Year Growth</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">50K+</span>
            <span class="modal-stat-label">Events Managed</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">200+</span>
            <span class="modal-stat-label">Enterprise Clients</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">95%</span>
            <span class="modal-stat-label">Client Retention</span>
          </div>
        </div>
      </div>
      <div class="modal-right">
        <ul class="modal-features">
          <li>Scalable infrastructure supporting events of all sizes</li>
          <li>Advanced analytics and reporting capabilities</li>
          <li>Seamless integration with popular marketing tools</li>
          <li>24/7 customer support and technical assistance</li>
          <li>Regular feature updates based on user feedback</li>
          <li>Comprehensive training and onboarding programs</li>
        </ul>
      </div>
    </div>
  </div>

  <div id="satisfaction-modal" class="modal">
    <div class="modal-content">
      <div class="modal-left">
        <div class="modal-icon">⭐</div>
        <div class="modal-header">
          <h2 class="modal-title">Customer Satisfaction Details</h2>
          <button class="close-modal" onclick="closeModal('satisfaction')">&times;</button>
        </div>
        <p class="modal-description">Our commitment to exceptional user experience has resulted in outstanding satisfaction ratings across all our services.</p>
        <div class="modal-stats">
          <div class="modal-stat">
            <span class="modal-stat-number">4.9/5</span>
            <span class="modal-stat-label">Average Rating</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">98%</span>
            <span class="modal-stat-label">Retention Rate</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">24h</span>
            <span class="modal-stat-label">Avg. Response Time</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">99%</span>
            <span class="modal-stat-label">Issue Resolution</span>
          </div>
        </div>
      </div>
      <div class="modal-right">
        <ul class="modal-features">
          <li>Intuitive user interface designed for ease of use</li>
          <li>Comprehensive training and onboarding programs</li>
          <li>Regular feature updates based on user feedback</li>
          <li>Dedicated account managers for enterprise clients</li>
          <li>Proactive support and personalized recommendations</li>
          <li>Extensive knowledge base and tutorial videos</li>
        </ul>
      </div>
    </div>
  </div>

  <div id="global-modal" class="modal">
    <div class="modal-content">
      <div class="modal-left">
        <div class="modal-header">
          <h2 class="modal-title">Global Reach Details</h2>
          <button class="close-modal" onclick="closeModal('global')">&times;</button>
        </div>
        <div class="modal-icon">🌍</div>
        <p class="modal-description">EventSphere powers events across the globe with localized support and multi-language capabilities.</p>
        <div class="modal-stats">
          <div class="modal-stat">
            <span class="modal-stat-number">75+</span>
            <span class="modal-stat-label">Countries Served</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">1M+</span>
            <span class="modal-stat-label">Total Attendees</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">15</span>
            <span class="modal-stat-label">Languages Supported</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">24/7</span>
            <span class="modal-stat-label">Global Support</span>
          </div>
        </div>
      </div>
      <div class="modal-right">
        <ul class="modal-features">
          <li>Multi-currency payment processing</li>
          <li>Localized content and marketing tools</li>
          <li>Regional compliance and data protection</li>
          <li>International event best practices</li>
          <li>Local partnerships in key markets</li>
          <li>Cultural adaptation for diverse audiences</li>
        </ul>
      </div>
    </div>
  </div>

  <div id="performance-modal" class="modal">
    <div class="modal-content">
      <div class="modal-left">
        <div class="modal-header">
          <h2 class="modal-title">Performance Details</h2>
          <button class="close-modal" onclick="closeModal('performance')">&times;</button>
        </div>
        <div class="modal-icon">⚡</div>
        <p class="modal-description">Our cutting-edge infrastructure ensures lightning-fast performance and maximum uptime for all your events.</p>
        <div class="modal-stats">
          <div class="modal-stat">
            <span class="modal-stat-number">99.9%</span>
            <span class="modal-stat-label">Uptime Guarantee</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">0.2s</span>
            <span class="modal-stat-label">Average Load Time</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">99.99%</span>
            <span class="modal-stat-label">Data Accuracy</span>
          </div>
          <div class="modal-stat">
            <span class="modal-stat-number">50ms</span>
            <span class="modal-stat-label">API Response</span>
          </div>
        </div>
      </div>
      <div class="modal-right">
        <ul class="modal-features">
          <li>Cloud-based scalable infrastructure</li>
          <li>Real-time analytics and reporting</li>
          <li>Automated backup and disaster recovery</li>
          <li>Advanced security and encryption</li>
          <li>Continuous performance monitoring</li>
          <li>Regular security audits and updates</li>
        </ul>
      </div>
    </div>
  </div>

  <footer class="site-footer">© <span id="year"></span> EventSphere | All Rights Reserved | Service Available Across India</footer>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Particles.js Library -->
  <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  
  <script>
    // Initialize Swipers
    var splitSwiper = new Swiper(".split-swiper", {
      effect: "fade",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 1,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        slideChange: function () {
          const slides = document.querySelectorAll('.split-slide');
          slides.forEach(slide => slide.classList.remove('split-slide-active'));
          slides[this.activeIndex].classList.add('split-slide-active');
        }
      }
    });

    // Vertical Swiper
    var verticalSwiper = new Swiper(".vertical-swiper", {
      direction: "vertical",
      slidesPerView: 1,
      spaceBetween: 0,
      loop: true,
      autoplay: {
        delay: 4500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      on: {
        slideChange: function () {
          const slides = document.querySelectorAll('.vertical-slide');
          slides.forEach(slide => slide.classList.remove('vertical-slide-active'));
          slides[this.activeIndex].classList.add('vertical-slide-active');
        }
      }
    });

    // Booking functionality
    function openBooking(eventName) {
      const modal = document.getElementById('booking-modal');
      document.getElementById('event-name').value = eventName;
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeBooking() {
      const modal = document.getElementById('booking-modal');
      modal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    // Handle booking form submission
    document.getElementById('booking-form').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = {
        event: document.getElementById('event-name').value,
        name: document.getElementById('full-name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        tickets: document.getElementById('tickets').value,
        requests: document.getElementById('special-requests').value
      };
      
      // In a real application, you would send this data to a server
      console.log('Booking submitted:', formData);
      
      // Show success message
      alert(`Thank you for booking ${formData.tickets} ticket(s) for ${formData.event}! A confirmation has been sent to ${formData.email}.`);
      
      // Close modal and reset form
      closeBooking();
      this.reset();
    });

    // Modal functionality
    function openModal(type) {
      const modal = document.getElementById(`${type}-modal`);
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeModal(type) {
      const modal = document.getElementById(`${type}-modal`);
      modal.classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside content
    document.querySelectorAll('.modal, .booking-modal').forEach(modal => {
      modal.addEventListener('click', function(e) {
        if (e.target === this) {
          if (this.classList.contains('booking-modal')) {
            closeBooking();
          } else {
            const modalId = this.id;
            const type = modalId.replace('-modal', '');
            closeModal(type);
          }
        }
      });
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active, .booking-modal.active').forEach(modal => {
          if (modal.classList.contains('booking-modal')) {
            closeBooking();
          } else {
            const modalId = modal.id;
            const type = modalId.replace('-modal', '');
            closeModal(type);
          }
        });
      }
    });

    // Initialize particles.js
    document.addEventListener('DOMContentLoaded', function() {
      particlesJS('particles-js', {
        particles: {
          number: { value: 80, density: { enable: true, value_area: 800 } },
          color: { value: "#ffffff" },
          shape: { type: "circle" },
          opacity: { value: 0.5, random: true },
          size: { value: 3, random: true },
          line_linked: {
            enable: true,
            distance: 150,
            color: "#ffffff",
            opacity: 0.4,
            width: 1
          },
          move: {
            enable: true,
            speed: 2,
            direction: "none",
            random: true,
            straight: false,
            out_mode: "out",
            bounce: false
          }
        },
        interactivity: {
          detect_on: "canvas",
          events: {
            onhover: { enable: true, mode: "repulse" },
            onclick: { enable: true, mode: "push" },
            resize: true
          }
        },
        retina_detect: true
      });
    });

    // Featured Events Slider Functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const totalSlides = slides.length;

    function showSlide(n) {
      // Hide all slides
      slides.forEach(slide => {
        slide.classList.remove('active');
      });
      dots.forEach(dot => {
        dot.classList.remove('active');
      });
      
      // Update current slide index
      currentSlide = (n + totalSlides) % totalSlides;
      
      // Show current slide and activate corresponding dot
      slides[currentSlide].classList.add('active');
      dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
      showSlide(currentSlide + 1);
    }

    function prevSlide() {
      showSlide(currentSlide - 1);
    }

    // Set up event listeners for slider
    document.querySelector('.slider-arrow.next').addEventListener('click', nextSlide);
    document.querySelector('.slider-arrow.prev').addEventListener('click', prevSlide);

    // Set up event listeners for dots
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        showSlide(index);
      });
    });

    // Auto-advance slides
    let slideInterval = setInterval(nextSlide, 5000);

    // Pause auto-advance on hover
    const eventsSlider = document.querySelector('.events-slider');
    eventsSlider.addEventListener('mouseenter', () => {
      clearInterval(slideInterval);
    });
    eventsSlider.addEventListener('mouseleave', () => {
      slideInterval = setInterval(nextSlide, 5000);
    });

    // Create floating elements
    function createFloatingElements() {
      const container = document.getElementById('floatingElements');
      const colors = ['#ff6b6b', '#6a11cb', '#ffd93d', '#2575fc'];
      
      for (let i = 0; i < 15; i++) {
        const element = document.createElement('div');
        element.classList.add('floating-element');
        
        // Random properties
        const size = Math.random() * 25 + 8;
        const color = colors[Math.floor(Math.random() * colors.length)];
        const left = Math.random() * 100;
        const top = Math.random() * 100;
        const delay = Math.random() * 15;
        const duration = Math.random() * 10 + 15;
        
        element.style.width = `${size}px`;
        element.style.height = `${size}px`;
        element.style.background = color;
        element.style.left = `${left}%`;
        element.style.top = `${top}%`;
        element.style.animationDelay = `${delay}s`;
        element.style.animationDuration = `${duration}s`;
        
        container.appendChild(element);
      }
    }

    // Update footer year
    document.getElementById('year').textContent = new Date().getFullYear();

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.nav-3d');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Mobile menu functionality
    const menuToggle = document.getElementById('menuToggle');
    const mobileNav = document.getElementById('mobileNav');
    const closeMenu = document.getElementById('closeMenu');
    const overlay = document.getElementById('overlay');

    function openMobileMenu() {
      mobileNav.classList.add('active');
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
      mobileNav.classList.remove('active');
      overlay.classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    menuToggle.addEventListener('click', openMobileMenu);
    closeMenu.addEventListener('click', closeMobileMenu);
    overlay.addEventListener('click', closeMobileMenu);

    // Section navigation functionality
    function showSection(sectionId) {
      // Hide all sections
      document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
      });
      document.querySelectorAll('.split-slider-section, .events-section, .vertical-slider-section').forEach(section => {
        section.style.display = 'none';
      });
      document.querySelector('.welcome-card').style.display = 'none';
      
      // Show selected section
      if (sectionId === 'home') {
        document.querySelector('.welcome-card').style.display = 'block';
        document.querySelectorAll('.split-slider-section, .events-section, .vertical-slider-section').forEach(section => {
          section.style.display = 'block';
        });
      } else {
        document.getElementById(sectionId + '-section').classList.add('active');
      }
      
      // Update active nav links
      document.querySelectorAll('#mainNav a, .mobile-nav a').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-section') === sectionId) {
          link.classList.add('active');
        }
      });
      
      // Close mobile menu if open
      closeMobileMenu();
      
      // Scroll to top
      window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    // Add event listeners to navigation links
    document.querySelectorAll('#mainNav a, .mobile-nav a').forEach(link => {
      if (link.getAttribute('data-section')) {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          showSection(this.getAttribute('data-section'));
        });
      }
    });

    // Close mobile menu when clicking on a link
    const mobileLinks = document.querySelectorAll('.mobile-nav a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', closeMobileMenu);
    });

    // Explore Events button animation
    function exploreEvents() {
      const btn = document.querySelector('.btn');
      btn.style.transform = 'scale(0.9)';
      setTimeout(() => {
        btn.style.transform = '';
        showSection('events');
      }, 200);
    }

    // Create floating elements on load
    window.addEventListener('load', createFloatingElements);
  </script>
</body>
</html>