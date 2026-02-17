<?php
// events_section.php
session_start();
require_once 'config/database.php'; // Make sure this path is correct

// Function to ensure we have events table
function ensureEventsTable($pdo) {
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_name VARCHAR(255) NOT NULL,
            event_date DATE NOT NULL,
            event_time TIME NOT NULL,
            location VARCHAR(255) NOT NULL,
            event_price DECIMAL(10,2) NOT NULL,
            description TEXT,
            available_tickets INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    } catch (Exception $e) {
        die("Error creating events table: " . $e->getMessage());
    }
}

// Function to generate random events
function generateUpcomingEvents($pdo) {
    $eventTemplates = [
        [
            'name' => 'Morning Yoga Session',
            'locations' => ['Central Park', 'Sunrise Yoga Studio', 'Beach Front', 'Community Hall'],
            'prices' => [15.00, 20.00, 25.00, 18.00],
            'times' => ['06:00:00', '07:00:00', '08:00:00'],
            'descriptions' => [
                'Start your day with peaceful yoga in nature',
                'Energizing morning flow for all levels',
                'Sunrise meditation and yoga practice'
            ]
        ],
        [
            'name' => 'Tech Workshop',
            'locations' => ['Tech Hub', 'Innovation Center', 'Conference Hall', 'Business District'],
            'prices' => [50.00, 75.00, 100.00, 60.00],
            'times' => ['10:00:00', '14:00:00', '18:00:00'],
            'descriptions' => [
                'Learn cutting-edge technologies from experts',
                'Hands-on workshop with practical projects',
                'Networking with industry professionals'
            ]
        ],
        [
            'name' => 'Live Music Night',
            'locations' => ['Jazz Club', 'City Square', 'Riverside Venue', 'Downtown Pub'],
            'prices' => [25.00, 30.00, 35.00, 20.00],
            'times' => ['19:00:00', '20:00:00', '21:00:00'],
            'descriptions' => [
                'Amazing live performances by local artists',
                'Great music, food, and atmosphere',
                'Unforgettable night of entertainment'
            ]
        ]
    ];

    // Delete events that are older than today
    $deleteStmt = $pdo->prepare("DELETE FROM events WHERE event_date < CURDATE()");
    $deleteStmt->execute();

    // Check how many future events we have
    $checkStmt = $pdo->prepare("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE()");
    $checkStmt->execute();
    $eventCount = $checkStmt->fetch()['count'];

    // Generate 8-12 events for the next 7 days
    $eventsToGenerate = max(8, 12 - $eventCount);
    
    for ($i = 0; $i < $eventsToGenerate; $i++) {
        $daysToAdd = rand(0, 6); // Next 7 days
        $eventDate = date('Y-m-d', strtotime("+$daysToAdd days"));
        
        $template = $eventTemplates[array_rand($eventTemplates)];
        $location = $template['locations'][array_rand($template['locations'])];
        $price = $template['prices'][array_rand($template['prices'])];
        $time = $template['times'][array_rand($template['times'])];
        $description = $template['descriptions'][array_rand($template['descriptions'])];
        
        $eventName = $template['name'] . " - " . date('D, M j', strtotime($eventDate));
        
        // Insert the event
        try {
            $insertStmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_time, location, event_price, description, available_tickets) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->execute([
                $eventName,
                $eventDate,
                $time,
                $location,
                $price,
                $description,
                rand(15, 50)
            ]);
        } catch (Exception $e) {
            // Ignore duplicate errors and continue
            continue;
        }
    }
}

try {
    // Ensure events table exists
    ensureEventsTable($pdo);
    
    // Generate upcoming events if needed
    generateUpcomingEvents($pdo);

    // Fetch available events for next 7 days only
    $stmt = $pdo->prepare("SELECT * FROM events 
                          WHERE event_date >= CURDATE() 
                          AND event_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                          ORDER BY event_date ASC, event_time ASC");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group events by date
    $eventsByDate = [];
    foreach ($events as $event) {
        $date = $event['event_date'];
        if (!isset($eventsByDate[$date])) {
            $eventsByDate[$date] = [];
        }
        $eventsByDate[$date][] = $event;
    }

} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
    $eventsByDate = [];
}

// Get user data for pre-filling forms (simplified for demo)
$user = null;
if (isset($_SESSION['user_id'])) {
    // For demo purposes, using session data directly
    $user = [
        'name' => $_SESSION['username'] ?? 'Guest User',
        'email' => $_SESSION['email'] ?? 'guest@example.com'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .events-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 15px;
            color: white;
        }
        .events-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .date-section {
            margin-bottom: 30px;
            background: white;
            border-radius: 15px;
            padding: 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .date-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .date-title {
            font-size: 1.4em;
            font-weight: bold;
        }
        .day-indicator {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
        }
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            padding: 25px;
        }
        .event-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #3498db;
        }
        .event-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .event-detail {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #555;
            font-size: 0.95em;
        }
        .event-detail i {
            width: 20px;
            margin-right: 12px;
            color: #3498db;
        }
        .event-price {
            font-size: 1.6em;
            font-weight: bold;
            color: #e74c3c;
            text-align: center;
            margin: 20px 0;
            padding: 12px;
            background: white;
            border-radius: 10px;
            border: 2px solid #e74c3c;
        }
        .booking-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
            border-left: 4px solid #27ae60;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        .btn-book {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #27ae60, #219a52);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
        }
        .no-events {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
            background: white;
            border-radius: 15px;
            margin: 20px 0;
        }
        .today-badge {
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .tomorrow-badge {
            background: #f39c12;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .event-time-badge {
            background: #9b59b6;
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.9em;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .refresh-notice {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="events-container">
        <div class="events-header">
            <h1><i class="fas fa-calendar-alt"></i> Upcoming Events</h1>
            <p>Discover amazing events happening in the next 7 days!</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="refresh-notice">
            <i class="fas fa-sync-alt"></i> Events are automatically updated daily. Past events are removed and new ones are added!
        </div>

        <?php if (empty($eventsByDate)): ?>
            <div class="no-events">
                <i class="fas fa-calendar-times" style="font-size: 4em; margin-bottom: 20px; color: #bdc3c7;"></i>
                <h2>No Events Available</h2>
                <p>New events will be added soon! Check back tomorrow.</p>
            </div>
        <?php else: ?>
            <?php foreach ($eventsByDate as $date => $dateEvents): ?>
                <div class="date-section">
                    <div class="date-header">
                        <div class="date-title">
                            <i class="fas fa-calendar-day"></i>
                            <?php 
                            $displayDate = date('l, F j, Y', strtotime($date));
                            $today = date('Y-m-d');
                            $tomorrow = date('Y-m-d', strtotime('+1 day'));
                            
                            echo $displayDate;
                            if ($date === $today) {
                                echo '<span class="today-badge">TODAY</span>';
                            } elseif ($date === $tomorrow) {
                                echo '<span class="tomorrow-badge">TOMORROW</span>';
                            }
                            ?>
                        </div>
                        <div class="day-indicator">
                            <i class="fas fa-list"></i> <?php echo count($dateEvents); ?> event(s)
                        </div>
                    </div>
                    
                    <div class="events-grid">
                        <?php foreach ($dateEvents as $event): ?>
                        <div class="event-card">
                            <span class="event-time-badge">
                                <i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                            </span>
                            
                            <div class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                            
                            <div class="event-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></span>
                            </div>
                            
                            <?php if (!empty($event['description'])): ?>
                            <div class="event-detail">
                                <i class="fas fa-info-circle"></i>
                                <span><?php echo htmlspecialchars($event['description']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="event-detail">
                                <i class="fas fa-ticket-alt"></i>
                                <span><strong>Tickets Available:</strong> <?php echo $event['available_tickets']; ?></span>
                            </div>
                            
                            <div class="event-price">
                                $<?php echo number_format($event['event_price'], 2); ?>
                            </div>

                            <!-- Booking Form -->
                            <div class="booking-form">
                                <form action="process_booking.php" method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    
                                    <div class="form-group">
                                        <label for="attendee_name_<?php echo $event['id']; ?>">
                                            <i class="fas fa-user"></i> Your Name:
                                        </label>
                                        <input type="text" id="attendee_name_<?php echo $event['id']; ?>" name="attendee_name" required 
                                               value="<?php echo $user ? htmlspecialchars($user['name']) : ''; ?>"
                                               placeholder="Enter your full name">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="attendee_email_<?php echo $event['id']; ?>">
                                            <i class="fas fa-envelope"></i> Your Email:
                                        </label>
                                        <input type="email" id="attendee_email_<?php echo $event['id']; ?>" name="attendee_email" required
                                               value="<?php echo $user ? htmlspecialchars($user['email']) : ''; ?>"
                                               placeholder="Enter your email">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="ticket_count_<?php echo $event['id']; ?>">
                                            <i class="fas fa-users"></i> Number of Tickets:
                                        </label>
                                        <input type="number" id="ticket_count_<?php echo $event['id']; ?>" name="ticket_count" 
                                               min="1" max="10" value="1" required>
                                    </div>
                                    
                                    <button type="submit" class="btn-book">
                                        <i class="fas fa-check-circle"></i> Book Now - Total: $<?php echo number_format($event['event_price'], 2); ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
    // Update ticket total when quantity changes
    document.querySelectorAll('input[name="ticket_count"]').forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            const priceText = form.closest('.event-card').querySelector('.event-price').textContent;
            const price = parseFloat(priceText.replace('$', ''));
            const quantity = parseInt(this.value);
            const total = price * quantity;
            
            const button = form.querySelector('.btn-book');
            button.innerHTML = `<i class="fas fa-check-circle"></i> Book Now - Total: $${total.toFixed(2)}`;
        });
    });

    // Auto-refresh every 10 minutes
    setTimeout(function() {
        location.reload();
    }, 600000);
    </script>
</body>
</html>