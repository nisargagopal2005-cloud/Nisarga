<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['booking_event_id'])) {
    header("Location: index_event.php");
    exit();
}

require_once 'config/database.php';

$user_id = $_SESSION['user_id'];
$event_id = $_SESSION['booking_event_id'];
$ticket_quantity = $_SESSION['booking_tickets'];
$total_amount = $_SESSION['booking_total'];
$discount_amount = $_SESSION['booking_discount'];
$final_amount = $_SESSION['booking_final'];
$payment_method = $_SESSION['payment_method'];

try {
    $pdo->beginTransaction();
    
    // Insert booking
    $booking_sql = "INSERT INTO bookings (user_id, event_id, ticket_quantity, total_amount, discount_amount, final_amount, payment_method, payment_status, status) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'completed', 'confirmed')";
    $booking_stmt = $pdo->prepare($booking_sql);
    $booking_stmt->execute([$user_id, $event_id, $ticket_quantity, $total_amount, $discount_amount, $final_amount, $payment_method]);
    
    $booking_id = $pdo->lastInsertId();
    
    // Update available tickets
    $update_sql = "UPDATE events SET available_tickets = available_tickets - ? WHERE id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([$ticket_quantity, $event_id]);
    
    $pdo->commit();
    
    // Store booking ID for confirmation page
    $_SESSION['last_booking_id'] = $booking_id;
    
    // Clear booking session data
    unset($_SESSION['booking_event_id'], $_SESSION['booking_tickets'], $_SESSION['booking_total'], 
          $_SESSION['booking_discount'], $_SESSION['booking_final'], $_SESSION['payment_method']);
    
    // Redirect to confirmation page
    header("Location: booked_confirmation.php");
    exit();
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Payment failed: " . $e->getMessage();
    header("Location: booking_process.php?step=3");
    exit();
}
?>