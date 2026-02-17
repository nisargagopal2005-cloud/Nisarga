<?php
session_start();

// Create data directory if it doesn't exist
if (!is_dir('data')) {
    mkdir('data', 0777, true);
}

// Initialize data files
$events_file = 'data/events.json';

// Load events data
if (file_exists($events_file)) {
    $events = json_decode(file_get_contents($events_file), true) ?? [];
} else {
    // Default events data
    $events = [
        [
            'id' => 1,
            'title' => 'Music Festival 2024',
            'description' => 'Annual music festival featuring top national and international artists. Enjoy live performances, food stalls, and amazing ambiance.',
            'price' => 1500,
            'location' => 'Central Park, New York',
            'event_date' => '2024-12-15',
            'max_participants' => 5000,
            'image' => '🎵',
            'status' => 'active'
        ],
        [
            'id' => 2,
            'title' => 'Tech Conference 2024',
            'description' => 'Latest technology trends and innovations. Workshops on AI, Blockchain, and Cloud Computing. Network with industry experts.',
            'price' => 2000,
            'location' => 'Convention Center, Bangalore',
            'event_date' => '2024-11-20',
            'max_participants' => 1000,
            'image' => '💻',
            'status' => 'active'
        ],
        [
            'id' => 3,
            'title' => 'Food & Wine Expo',
            'description' => 'Gourmet food and wine tasting event. Featuring master chefs, wine connoisseurs, and culinary workshops.',
            'price' => 1200,
            'location' => 'Exhibition Grounds, Mumbai',
            'event_date' => '2024-10-25',
            'max_participants' => 800,
            'image' => '🍷',
            'status' => 'active'
        ],
        [
            'id' => 4,
            'title' => 'Marathon Run 2024',
            'description' => 'Annual city marathon for health enthusiasts. Categories: 5K, 10K, and Half Marathon. Prizes for winners.',
            'price' => 500,
            'location' => 'Marine Drive, Mumbai',
            'event_date' => '2024-09-15',
            'max_participants' => 3000,
            'image' => '🏃',
            'status' => 'active'
        ]
    ];
    file_put_contents($events_file, json_encode($events, JSON_PRETTY_PRINT));
}

// For demo - auto login user
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Demo User';
    $_SESSION['user_email'] = 'demo@example.com';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Book Your Experience</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .event-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .event-icon {
            font-size: 4rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .event-card h3 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .event-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .event-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 600;
        }

        .price {
            font-size: 2rem;
            color: #4CAF50;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn i {
            margin-left: 8px;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .user-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .user-info p {
            color: #666;
        }

        @media (max-width: 768px) {
            .events-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Exciting Events Await!</h1>
            <p>Book your spot for unforgettable experiences</p>
        </div>

        <div class="user-info">
            <h3>Welcome, <?php echo $_SESSION['user_name']; ?>! 👋</h3>
            <p>Ready to book your next adventure? Choose from our amazing events below.</p>
        </div>

        <div class="events-grid">
            <?php foreach ($events as $event): ?>
                <?php if ($event['status'] === 'active'): ?>
                    <div class="event-card">
                        <div class="event-icon">
                            <?php echo $event['image']; ?>
                        </div>
                        
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        
                        <p class="event-description">
                            <?php echo htmlspecialchars($event['description']); ?>
                        </p>
                        
                        <div class="event-details">
                            <div class="detail-item">
                                <span class="detail-label">📅 Date:</span>
                                <span class="detail-value"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">📍 Location:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($event['location']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">👥 Capacity:</span>
                                <span class="detail-value"><?php echo number_format($event['max_participants']); ?> spots</span>
                            </div>
                        </div>
                        
                        <div class="price">
                            ₹<?php echo number_format($event['price'], 2); ?>
                        </div>
                        
                        <a href="event_booking.php?event_id=<?php echo $event['id']; ?>" class="btn">
                            <i class="fas fa-ticket-alt"></i> Book Now
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>