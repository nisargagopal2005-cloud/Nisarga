<?php
session_start();
$user = $_SESSION['user_name'] ?? 'Guest';

// Example attendee data
$attendees = [
    'Registered' => 120,
    'Checked-In' => 95,
    'VIP' => 15,
    'Late Arrivals' => 10,
    'No Show' => 5
];

// Calculate total
$totalAttendees = array_sum($attendees);

// Calculate percentages
$percentages = [];
foreach ($attendees as $key => $value) {
    $percentages[$key] = round(($value / $totalAttendees) * 100, 1); // 1 decimal place
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Attendee Tracking — Event Management</title>
  <link rel="stylesheet" href="assets/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      max-width: 1000px;
      margin: auto;
    }
    h1 {
      text-align: center;
      color: #112d4e;
      margin-bottom: 2rem;
    }
    .chart-container {
      position: relative;
      height: 500px;
      background: #fff;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      animation: fadeIn 1.5s ease-in-out;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <header class="nav-3d">
    <div class="logo"> 📊 Analytics</div>
  </header>

  <main>
    <h1>📊 Live Attendee Tracking </h1>

    <div class="chart-container">
      <canvas id="attendeeChart"></canvas>
    </div>
  </main>

  <script>
    const ctx = document.getElementById('attendeeChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(63, 114, 175, 0.9)');
    gradient.addColorStop(1, 'rgba(200, 220, 255, 0.9)');

    // PHP-generated data
    const labels = <?php echo json_encode(array_keys($attendees)); ?>;
    const dataValues = <?php echo json_encode(array_values($attendees)); ?>;
    const percentages = <?php echo json_encode(array_values($percentages)); ?>;

    const data = {
      labels: labels,
      datasets: [{
        label: 'Attendees',
        data: dataValues,
        backgroundColor: gradient,
        borderColor: '#112d4e',
        borderWidth: 2,
        borderRadius: 12,
        barThickness: 60,
        hoverBackgroundColor: '#3f72af'
      }]
    };

    const options = {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              const index = context.dataIndex;
              return dataValues[index] + ' people (' + percentages[index] + '%)';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 20 }
        }
      },
      animation: {
        duration: 2000,
        easing: 'easeOutBounce'
      }
    };

    new Chart(ctx, {
      type: 'bar',
      data: data,
      options: options
    });
  </script>
</body>
</html>
