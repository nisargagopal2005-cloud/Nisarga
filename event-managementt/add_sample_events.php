<?php
require_once 'inc/db.php';

$events = [
    [
        'event_name' => 'Tech Conference 2024',
        'event_date' => '2024-12-15',
        'event_time' => '09:00:00',
        'location' => 'Convention Center',
        'description' => 'Annual technology conference with industry leaders',
        'event_price' => 199.99
    ],
    [
        'event_name' => 'Music Festival',
        'event_date' => '2024-11-20',
        'event_time' => '18:00:00',
        'location' => 'Central Park',
        'description' => 'Live music performances from various artists',
        'event_price' => 79.50
    ],
    [
        'event_name' => 'Business Workshop',
        'event_date' => '2024-12-05',
        'event_time' => '10:00:00',
        'location' => 'Business Hub',
        'description' => 'Learn business strategies and networking',
        'event_price' => 149.00
    ]
];

try {
    $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_time, location, description, event_price) VALUES (?, ?, ?, ?, ?, ?)");
    
    $count = 0;
    foreach ($events as $event) {
        $stmt->execute([
            $event['event_name'],
            $event['event_date'],
            $event['event_time'],
            $event['location'],
            $event['description'],
            $event['event_price']
        ]);
        $count++;
    }
    
    echo "✅ Successfully added $count sample events!";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>