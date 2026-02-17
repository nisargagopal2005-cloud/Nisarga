<?php
session_start();
$user = $_SESSION['user_name'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Venue Management — Event Management</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
  <header class="nav-3d">
    <div class="logo">🎪 <strong>Event Management</strong></div>
  
  </header>

  <main style="padding:7rem 2rem;max-width:900px;margin:auto;">
    <h1>🏟️ Venue Management</h1>

    <p>
      Venue management ensures that events have the right location, facilities, and capacity to succeed.
      A venue module lets organizers add, edit, and search venues by features like seating capacity,
      location, stage setup, available dates, and amenities (AV systems, Wi-Fi, catering).
    </p>

    <p>
      Integrating venues with ticketing allows seat mapping — users can select specific seats or sections
      while purchasing tickets. For multi-day events, venue scheduling prevents double-booking and ensures
      smooth flow between different halls or rooms.
    </p>

    <p>
      Organizers often require contracts, insurance, and compliance documents from venues. Store digital
      copies and reminders for deadlines. Real-time dashboards can show which venues are booked, how many
      events they hosted, and utilization rates across months or seasons.
    </p>

    <p>
      Advanced systems can integrate with maps (Google Maps APIs) to provide directions, parking info,
      and even 3D floor plans so attendees can visualize the space before arrival.
    </p>
  </main>
</body>
</html>
