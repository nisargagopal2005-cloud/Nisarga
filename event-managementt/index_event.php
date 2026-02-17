<?php
session_start();
require_once 'config/database.php';

// Get all events
$stmt = $pdo->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no events found in database, use sample events
if (empty($events)) {
    $events = [
        [
            'id' => 1,
            'event_name' => 'Grand Music Festival',
            'event_date' => '2025-12-25',
            'event_time' => '18:00:00',
            'event_location' => 'City Stadium',
            'event_price' => 2500.00,
            'available_tickets' => 500,
            'event_description' => 'Annual music festival with top international artists and amazing performances'
        ],
        [
            'id' => 2,
            'event_name' => 'Tech Summit 2025',
            'event_date' => '2025-11-20',
            'event_time' => '09:00:00',
            'event_location' => 'Convention Center',
            'event_price' => 5000.00,
            'available_tickets' => 200,
            'event_description' => 'Technology conference with industry leaders and innovation showcases'
        ],
        [
            'id' => 3,
            'event_name' => 'Food & Wine Expo',
            'event_date' => '2025-10-15',
            'event_time' => '11:00:00',
            'event_location' => 'Exhibition Grounds',
            'event_price' => 1500.00,
            'available_tickets' => 300,
            'event_description' => 'Gourmet food and wine tasting event with master chefs'
        ],
        [
            'id' => 4,
            'event_name' => 'Business Leadership Conference',
            'event_date' => '2025-12-10',
            'event_time' => '10:00:00',
            'event_location' => 'Grand Hotel',
            'event_price' => 8000.00,
            'available_tickets' => 150,
            'event_description' => 'Networking event for business leaders and entrepreneurs'
        ],
        [
            'id' => 5,
            'event_name' => 'Marathon Run 2025',
            'event_date' => '2025-11-05',
            'event_time' => '06:00:00',
            'event_location' => 'Central Park',
            'event_price' => 1200.00,
            'available_tickets' => 1000,
            'event_description' => 'Annual city marathon race with professional timing'
        ],
        [
            'id' => 6,
            'event_name' => 'Comedy Night Special',
            'event_date' => '2025-10-28',
            'event_time' => '20:00:00',
            'event_location' => 'Comedy Club Downtown',
            'event_price' => 800.00,
            'available_tickets' => 100,
            'event_description' => 'Stand-up comedy show with top comedians'
        ],
        [
            'id' => 7,
            'event_name' => 'Art & Culture Festival',
            'event_date' => '2025-11-15',
            'event_time' => '10:00:00',
            'event_location' => 'Art Gallery Square',
            'event_price' => 600.00,
            'available_tickets' => 250,
            'event_description' => 'Cultural festival showcasing local artists and performers'
        ],
        [
            'id' => 8,
            'event_name' => 'Startup Pitch Competition',
            'event_date' => '2025-12-05',
            'event_time' => '14:00:00',
            'event_location' => 'Innovation Hub',
            'event_price' => 2000.00,
            'available_tickets' => 80,
            'event_description' => 'Startup pitching event with investor networking'
        ],
        [
            'id' => 9,
            'event_name' => 'Yoga & Wellness Retreat',
            'event_date' => '2025-10-20',
            'event_time' => '07:00:00',
            'event_location' => 'Serenity Park',
            'event_price' => 900.00,
            'available_tickets' => 120,
            'event_description' => 'Full day yoga and meditation retreat'
        ],
        [
            'id' => 10,
            'event_name' => 'Film Festival Gala',
            'event_date' => '2025-11-25',
            'event_time' => '19:30:00',
            'event_location' => 'Royal Theater',
            'event_price' => 1800.00,
            'available_tickets' => 200,
            'event_description' => 'International film festival with red carpet event'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
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
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .header { 
            background: rgba(255,255,255,0.95); 
            border-radius: 20px; 
            padding: 30px; 
            text-align: center; 
            margin-bottom: 30px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
        }
        .header h1 { 
            color: #333; 
            font-size: 3em; 
            margin-bottom: 10px; 
        }
        .nav-tabs { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-top: 20px; 
        }
        .nav-tabs a { 
            padding: 12px 25px; 
            background: #4CAF50; 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            font-weight: 600; 
            transition: all 0.3s ease; 
        }
        .nav-tabs a:hover { 
            background: #45a049; 
            transform: translateY(-2px); 
        }
        .events-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); 
            gap: 25px; 
            margin-bottom: 40px; 
        }
        .event-card { 
            background: rgba(255,255,255,0.95); 
            border-radius: 20px; 
            padding: 25px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            transition: all 0.3s ease; 
            border-left: 5px solid #4CAF50; 
        }
        .event-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.15); 
        }
        .event-name { 
            font-size: 1.5em; 
            color: #333; 
            margin-bottom: 15px; 
            font-weight: 700; 
        }
        .event-details { 
            color: #666; 
            margin-bottom: 15px; 
            line-height: 1.6; 
        }
        .event-price { 
            font-size: 1.8em; 
            color: #4CAF50; 
            font-weight: 800; 
            margin: 15px 0; 
        }
        .book-btn { 
            background: linear-gradient(135deg, #4CAF50, #45a049); 
            color: white; 
            padding: 12px 30px; 
            border: none; 
            border-radius: 10px; 
            font-size: 1.1em; 
            font-weight: 600; 
            cursor: pointer; 
            width: 100%; 
            transition: all 0.3s ease; 
        }
        .book-btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3); 
        }
        .discount-badge { 
            background: #ff6b6b; 
            color: white; 
            padding: 5px 15px; 
            border-radius: 20px; 
            font-size: 0.9em; 
            font-weight: 600; 
            display: inline-block; 
            margin-bottom: 10px; 
        }
        .event-category { 
            background: #2196F3; 
            color: white; 
            padding: 3px 10px; 
            border-radius: 15px; 
            font-size: 0.8em; 
            display: inline-block; 
            margin-right: 10px; 
        }
        .event-features {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            margin: 10px 0;
            font-size: 0.9em;
        }
        .event-features ul {
            list-style: none;
            padding-left: 0;
        }
        .event-features li {
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .event-features li:last-child {
            border-bottom: none;
        }
        .event-count {
            text-align: center;
            color: white;
            margin-bottom: 20px;
            font-size: 1.2em;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Event Management System</h1>
            <p>Discover and book amazing events with exclusive discounts!</p>
            <div class="nav-tabs">
                <a href="index_event.php">🏠 Home</a>
                <a href="user1_dashboard.php?section=booked_events">📅 My Bookings</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="logout.php">🚪 Logout</a>
                <?php else: ?>
                    <a href="user_event.php">🔐 Login</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="event-count">
            🎊 Showing <?php echo count($events); ?> Amazing Events Available for Booking!
        </div>

        <div class="events-grid">
            <?php foreach($events as $event): ?>
                <?php 
                $discount = '';
                if($event['event_price'] >= 5000) $discount = '🎁 5% Discount on bookings above ₹5,000!';
                if($event['event_price'] >= 10000) $discount = '🎁 Special offers for premium events!';
                
                // Category based on price
                if($event['event_price'] >= 5000) {
                    $category = 'Premium';
                    $category_color = '#ff6b6b';
                } elseif($event['event_price'] >= 2000) {
                    $category = 'Standard';
                    $category_color = '#4CAF50';
                } else {
                    $category = 'Basic';
                    $category_color = '#2196F3';
                }

                // Event features based on category
                if($category === 'Premium') {
                    $features = ['VIP Access', 'Premium Seating', 'Backstage Pass', 'Free Merchandise'];
                } elseif($category === 'Standard') {
                    $features = ['Standard Seating', 'Food & Beverages', 'Event Souvenir'];
                } else {
                    $features = ['General Admission', 'Basic Amenities'];
                }
                ?>
                <div class="event-card">
                    <?php if($discount): ?>
                        <div class="discount-badge"><?php echo $discount; ?></div>
                    <?php endif; ?>
                    
                    <span class="event-category" style="background: <?php echo $category_color; ?>">
                        <?php echo $category; ?>
                    </span>
                    
                    <h3 class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                    
                    <div class="event-details">
                        <p>📅 <strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                        <p>⏰ <strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                        <p>📍 <strong>Location:</strong> <?php echo htmlspecialchars($event['event_location']); ?></p>
                        <p>🎫 <strong>Tickets Available:</strong> <?php echo $event['available_tickets']; ?></p>
                        <p><?php echo htmlspecialchars($event['event_description']); ?></p>
                        
                        <div class="event-features">
                            <strong>🎯 Event Features:</strong>
                            <ul>
                                <?php foreach($features as $feature): ?>
                                    <li>✓ <?php echo $feature; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="event-price">
                        ₹<?php echo number_format($event['event_price'], 2); ?>
                        <div style="font-size: 0.6em; color: #666; margin-top: 5px;">
                            per ticket
                        </div>
                    </div>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form action="booking_process.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <input type="hidden" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>">
                            <input type="hidden" name="event_price" value="<?php echo $event['event_price']; ?>">
                            <button type="submit" class="book-btn">
                                Book Now 🎟️
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="user_event.php" class="book-btn" style="text-decoration: none; display: block; text-align: center;">
                            Login to Book 🔐
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if(empty($events)): ?>
            <div style="text-align: center; color: white; padding: 40px;">
                <h2>No Events Available</h2>
                <p>Check back later for upcoming events!</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const eventCards = document.querySelectorAll('.event-card');
            
            eventCards.forEach((card, index) => {
                // Add staggered animation
                card.style.animationDelay = (index * 0.1) + 's';
                card.style.animation = 'fadeInUp 0.6s ease-out forwards';
                
                // Add click effect
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('form') && !e.target.closest('a')) {
                        this.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = 'translateY(-10px)';
                        }, 150);
                    }
                });
            });
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .event-card {
                opacity: 0;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>