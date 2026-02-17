<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit();
}

require_once __DIR__.'/inc/db.php';

// Fetch user details
$stmt = $pdo->prepare('SELECT name, email FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: user_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Scheduling</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(81, 45, 168, 0.95);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .welcome-text {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .events-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0 0 15px 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .back-btn {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #2980b9;
        }

        .current-date {
            font-size: 1.2rem;
            color: #2c3e50;
            font-weight: bold;
        }

        .event-day {
            margin-bottom: 40px;
            padding: 25px;
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .event-day h2 {
            color: #2c3e50;
            border-bottom: 3px solid #ff6b81;
            padding-bottom: 12px;
            margin-bottom: 25px;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
        }

        .event-card {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            border-left: 6px solid #3498db;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            border-left-color: #e74c3c;
        }

        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #e74c3c);
        }

        .event-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .event-time {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .event-location {
            color: #7f8c8d;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .today-badge {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .update-notice {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            border-left: 5px solid #28a745;
            font-weight: 600;
        }

        .no-events {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
            font-size: 1.2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .last-updated {
            text-align: center;
            margin-top: 40px;
            color: #666;
            font-size: 0.9rem;
            padding: 15px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .event-day h2 {
                font-size: 1.4rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .event-card {
                padding: 20px;
            }
            
            .navigation {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-calendar-alt"></i> Event Schedule</h1>
            <div class="welcome-text">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</div>
        </div>

        <div class="events-content">
            <div class="navigation">
                <a href="user_dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <div class="current-date">
                    <i class="fas fa-clock"></i> <?php echo date('l, F j, Y'); ?>
                </div>
            </div>

            <div class="update-notice">
                <i class="fas fa-sync-alt"></i> Live Event Schedule - Updates Automatically
            </div>

            <?php
            // Generate dynamic events
            function generateDynamicEvents() {
                $events = [];
                $today = new DateTime();
                
                // Today's events
                $events[] = [
                    'title' => 'Opening Ceremony', 
                    'time' => '10:00 AM - 11:30 AM', 
                    'location' => 'Main Hall A', 
                    'date' => $today->format('Y-m-d')
                ];
                $events[] = [
                    'title' => 'Team Building Workshop', 
                    'time' => '1:00 PM - 3:00 PM', 
                    'location' => 'Room B2', 
                    'date' => $today->format('Y-m-d')
                ];
                $events[] = [
                    'title' => 'Networking Dinner', 
                    'time' => '7:00 PM - 9:00 PM', 
                    'location' => 'Rooftop Terrace', 
                    'date' => $today->format('Y-m-d')
                ];
                
                // Tomorrow's events
                $tomorrow = clone $today; 
                $tomorrow->modify('+1 day');
                $events[] = [
                    'title' => 'Keynote Speech: Future Trends', 
                    'time' => '9:00 AM - 10:30 AM', 
                    'location' => 'Conference Hall', 
                    'date' => $tomorrow->format('Y-m-d')
                ];
                $events[] = [
                    'title' => 'Tech Innovation Panel', 
                    'time' => '11:00 AM - 12:30 PM', 
                    'location' => 'Room C1', 
                    'date' => $tomorrow->format('Y-m-d')
                ];
                $events[] = [
                    'title' => 'Startup Pitch Session', 
                    'time' => '2:00 PM - 4:00 PM', 
                    'location' => 'Innovation Lab', 
                    'date' => $tomorrow->format('Y-m-d')
                ];
                
                // Day after tomorrow events
                $dayAfter = clone $today; 
                $dayAfter->modify('+2 days');
                $events[] = [
                    'title' => 'Closing Ceremony & Awards', 
                    'time' => '3:00 PM - 5:00 PM', 
                    'location' => 'Grand Ballroom', 
                    'date' => $dayAfter->format('Y-m-d')
                ];
                
                return $events;
            }

            // Process events
            $allEvents = generateDynamicEvents();
            $today = date('Y-m-d');
            $filteredEvents = array_filter($allEvents, function($event) use ($today) {
                return $event['date'] >= $today;
            });

            $eventsByDate = [];
            foreach ($filteredEvents as $event) {
                $eventsByDate[$event['date']][] = $event;
            }
            ksort($eventsByDate);
            ?>

            <?php if (empty($eventsByDate)): ?>
                <div class="no-events">
                    <i class="fas fa-calendar-times" style="font-size: 4rem; margin-bottom: 20px; color: #bdc3c7;"></i>
                    <h3>No Upcoming Events Scheduled</h3>
                    <p>Check back later for new events and updates!</p>
                </div>
            <?php else: ?>
                <?php foreach($eventsByDate as $date => $dayEvents): ?>
                    <?php
                    $dateObj = new DateTime($date);
                    $formattedDate = $dateObj->format('l, F j, Y');
                    $isToday = $date === $today;
                    ?>
                    <section class="event-day">
                        <h2>
                            <?php echo $formattedDate; ?>
                            <?php if ($isToday): ?>
                                <span class="today-badge">TODAY</span>
                            <?php endif; ?>
                        </h2>
                        <div class="events-grid">
                            <?php foreach($dayEvents as $event): ?>
                                <div class="event-card">
                                    <div class="event-title">
                                        <i class="fas fa-star" style="color: #f39c12; margin-right: 8px;"></i>
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </div>
                                    <div class="event-time">
                                        <i class="far fa-clock"></i> 
                                        <?php echo htmlspecialchars($event['time']); ?>
                                    </div>
                                    <div class="event-location">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php echo htmlspecialchars($event['location']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
                
                <div class="last-updated">
                    <i class="fas fa-info-circle"></i> 
                    Schedule last updated: <?php echo date('F j, Y g:i A'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Add interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to event cards
            const eventCards = document.querySelectorAll('.event-card');
            eventCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            console.log('Event scheduling page loaded successfully!');
        });
    </script>
</body>
</html>