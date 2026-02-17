<?php
// event_manager.php - Complete Event Management System
class EventManager {
    private $events = [];
    private $venues = [];
    private $schedules = [];
    private $logistics = [];
    
    public function __construct() {
        $this->loadSampleData();
    }
    
    private function loadSampleData() {
        // Events
        $this->events = [
            ['id'=>1, 'name'=>'Tech Conference 2025', 'type'=>'Conference', 'date'=>'2025-03-15', 'venue_id'=>1, 'budget'=>50000, 'status'=>'Planning'],
            ['id'=>2, 'name'=>'Product Launch', 'type'=>'Launch', 'date'=>'2025-04-20', 'venue_id'=>2, 'budget'=>30000, 'status'=>'Confirmed']
        ];
        
        // Venues
        $this->venues = [
            ['id'=>1, 'name'=>'Convention Center', 'capacity'=>1000, 'location'=>'Downtown', 'contact'=>'venue@email.com'],
            ['id'=>2, 'name'=>'Business Hotel', 'capacity'=>500, 'location'=>'City Center', 'contact'=>'hotel@email.com']
        ];
        
        // Schedules
        $this->schedules = [
            ['event_id'=>1, 'activity'=>'Registration', 'time'=>'09:00 AM', 'duration'=>'1 hour'],
            ['event_id'=>1, 'activity'=>'Keynote Speech', 'time'=>'10:00 AM', 'duration'=>'2 hours']
        ];
        
        // Logistics
        $this->logistics = [
            ['event_id'=>1, 'item'=>'Audio System', 'quantity'=>2, 'vendor'=>'AV Solutions'],
            ['event_id'=>1, 'item'=>'Chairs', 'quantity'=>1000, 'vendor'=>'Rental Co.']
        ];
    }
    
    public function getAllEvents() { return $this->events; }
    public function getAllVenues() { return $this->venues; }
    public function getAllSchedules() { return $this->schedules; }
    public function getAllLogistics() { return $this->logistics; }
    
    public function addEvent($name, $type, $date, $venue_id, $budget) {
        $newEvent = [
            'id' => count($this->events) + 1,
            'name' => $name,
            'type' => $type,
            'date' => $date,
            'venue_id' => $venue_id,
            'budget' => $budget,
            'status' => 'Planning'
        ];
        $this->events[] = $newEvent;
        return $newEvent;
    }
}

// Create instance
$eventSystem = new EventManager();

// Handle form submissions
if ($_POST['action'] ?? '' == 'add_event') {
    $newEvent = $eventSystem->addEvent(
        $_POST['event_name'],
        $_POST['event_type'],
        $_POST['event_date'],
        $_POST['venue_id'],
        $_POST['budget']
    );
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Event Management System</h1>
        
        <!-- Add Event Form -->
        <div class="card mb-4">
            <div class="card-header">Add New Event</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_event">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="event_name" class="form-control" placeholder="Event Name" required>
                        </div>
                        <div class="col-md-3">
                            <select name="event_type" class="form-control" required>
                                <option value="Conference">Conference</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Seminar">Seminar</option>
                                <option value="Launch">Product Launch</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="budget" class="form-control" placeholder="Budget" required>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-success">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Events Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">Events</div>
                    <div class="card-body">
                        <?php foreach($eventSystem->getAllEvents() as $event): ?>
                        <div class="border p-2 mb-2">
                            <strong><?= $event['name'] ?></strong><br>
                            <small>Date: <?= $event['date'] ?> | Budget: $<?= number_format($event['budget']) ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">Venues</div>
                    <div class="card-body">
                        <?php foreach($eventSystem->getAllVenues() as $venue): ?>
                        <div class="border p-2 mb-2">
                            <strong><?= $venue['name'] ?></strong><br>
                            <small>Capacity: <?= $venue['capacity'] ?> | Location: <?= $venue['location'] ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedules & Logistics -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning">Schedules</div>
                    <div class="card-body">
                        <?php foreach($eventSystem->getAllSchedules() as $schedule): ?>
                        <div class="border p-2 mb-2">
                            <strong><?= $schedule['activity'] ?></strong><br>
                            <small>Time: <?= $schedule['time'] ?> | Duration: <?= $schedule['duration'] ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">Logistics</div>
                    <div class="card-body">
                        <?php foreach($eventSystem->getAllLogistics() as $logistic): ?>
                        <div class="border p-2 mb-2">
                            <strong><?= $logistic['item'] ?></strong><br>
                            <small>Qty: <?= $logistic['quantity'] ?> | Vendor: <?= $logistic['vendor'] ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>