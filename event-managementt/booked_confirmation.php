<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_event.php");
    exit();
}

if (!isset($_SESSION['last_booking_id'])) {
    header("Location: index_event.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_SESSION['last_booking_id'];

// Get booking details
$sql = "SELECT b.*, e.event_name, e.event_date, e.event_time, e.event_location, e.event_price 
        FROM bookings b 
        JOIN events e ON b.event_id = e.id 
        WHERE b.id = ? AND b.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$booking_id, $user_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

unset($_SESSION['last_booking_id']);

if (!$booking) {
    header("Location: index_event.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed!</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { min-height: 100vh; overflow: hidden; background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); background-size: 400% 400%; animation: gradient 15s ease infinite; display: flex; justify-content: center; align-items: center; padding: 20px; }
        @keyframes gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .confetti { position: fixed; width: 15px; height: 15px; background: #ffd700; border-radius: 50%; animation: confetti-fall 5s linear infinite; z-index: 1; }
        .confetti:nth-child(2n) { background: #ff6b6b; }
        .confetti:nth-child(3n) { background: #4ecdc4; }
        .confetti:nth-child(4n) { background: #45b7d1; }
        .confetti:nth-child(5n) { background: #96ceb4; }
        @keyframes confetti-fall { 
            0% { transform: translateY(-100px) rotate(0deg) translateX(0); opacity: 1; }
            100% { transform: translateY(1000px) rotate(720deg) translateX(100px); opacity: 0; }
        }
        .confirmation-container { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 25px; padding: 50px; box-shadow: 0 25px 50px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.3); max-width: 600px; width: 100%; text-align: center; position: relative; z-index: 2; border: 1px solid rgba(255,255,255,0.5); animation: container-appear 1s ease-out; }
        @keyframes container-appear { 
            0% { transform: scale(0.8) translateY(50px); opacity: 0; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        .success-icon { font-size: 100px; margin-bottom: 20px; animation: icon-bounce 2s ease-in-out infinite; background: linear-gradient(135deg, #4CAF50, #45a049); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 5px 15px rgba(76, 175, 80, 0.3)); }
        @keyframes icon-bounce { 
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) scale(1); }
            40% { transform: translateY(-20px) scale(1.1); }
            60% { transform: translateY(-10px) scale(1.05); }
        }
        h1 { background: linear-gradient(135deg, #4CAF50, #2E7D32); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 3em; margin-bottom: 10px; font-weight: 800; text-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .subtitle { color: #666; font-size: 1.3em; margin-bottom: 30px; font-weight: 300; }
        .booking-id { background: linear-gradient(135deg, #4CAF50, #45a049); color: white; padding: 12px 25px; border-radius: 50px; font-weight: bold; display: inline-block; margin: 20px 0; font-size: 1.1em; box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3); animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .booking-details { background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 20px; padding: 30px; margin: 30px 0; text-align: left; border: 1px solid rgba(255,255,255,0.8); box-shadow: inset 0 2px 10px rgba(0,0,0,0.05), 0 5px 20px rgba(0,0,0,0.1); animation: slide-up 0.8s ease-out 0.3s both; }
        @keyframes slide-up { 
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .detail-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.1); transition: all 0.3s ease; }
        .detail-row:hover { background: rgba(255,255,255,0.5); border-radius: 10px; padding: 12px 15px; transform: translateX(5px); }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; }
        .detail-label { color: #555; font-weight: 600; font-size: 1em; }
        .detail-value { color: #333; font-weight: 700; font-size: 1.1em; text-align: right; }
        .total-amount { background: linear-gradient(135deg, #4CAF50, #45a049); color: white; padding: 20px; border-radius: 15px; font-size: 1.5em; font-weight: 800; margin: 25px 0; box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3); animation: glow 2s ease-in-out infinite alternate; }
        @keyframes glow { from { box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3); } to { box-shadow: 0 8px 35px rgba(76, 175, 80, 0.6); } }
        .actions { margin-top: 40px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; animation: slide-up 0.8s ease-out 0.6s both; }
        .btn { padding: 15px 30px; border: none; border-radius: 12px; font-size: 1.1em; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .btn::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); transition: left 0.5s; }
        .btn:hover::before { left: 100%; }
        .btn-primary { background: linear-gradient(135deg, #4CAF50, #45a049); color: white; box-shadow: 0 5px 20px rgba(76, 175, 80, 0.3); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(76, 175, 80, 0.4); }
        .btn-secondary { background: linear-gradient(135deg, #6c757d, #5a6268); color: white; box-shadow: 0 5px 20px rgba(108, 117, 125, 0.3); }
        .btn-secondary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(108, 117, 125, 0.4); }
        .btn-print { background: linear-gradient(135deg, #2196F3, #1976D2); color: white; box-shadow: 0 5px 20px rgba(33, 150, 243, 0.3); }
        .btn-print:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(33, 150, 243, 0.4); }
        .confirmation-message { background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 15px; padding: 20px; margin: 20px 0; color: #2E7D32; font-weight: 500; }
        @media (max-width: 768px) {
            body { padding: 10px; animation: gradient-mobile 20s ease infinite; }
            @keyframes gradient-mobile { 0% { background-position: 0% 0%; } 50% { background-position: 100% 100%; } 100% { background-position: 0% 0%; } }
            .confirmation-container { padding: 30px 20px; margin: 10px; }
            h1 { font-size: 2.2em; }
            .actions { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 5px; }
            .detail-value { text-align: left; }
        }
    </style>
</head>
<body>
    <?php for ($i = 0; $i < 100; $i++): ?>
        <div class='confetti' style='left: <?php echo rand(0, 100); ?>%; animation-delay: <?php echo rand(0, 8); ?>s; animation-duration: <?php echo rand(4, 8); ?>s; width: <?php echo rand(8, 15); ?>px; height: <?php echo rand(8, 15); ?>px;'></div>
    <?php endfor; ?>

    <div class="confirmation-container">
        <div class="success-icon">🎉</div>
        
        <h1>Congratulations!</h1>
        <p class="subtitle">Your event has been successfully booked!</p>
        
        <div class="booking-id">
            Booking ID: #<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?>
        </div>
        
        <div class="confirmation-message">
            ✅ Your booking is confirmed! A confirmation email has been sent to your registered email address.
        </div>
        
        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Event Name:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking['event_name']); ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Date & Time:</span>
                <span class="detail-value">
                    <?php echo date('F j, Y', strtotime($booking['event_date'])); ?> 
                    at <?php echo date('g:i A', strtotime($booking['event_time'])); ?>
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Location:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking['event_location']); ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Tickets Booked:</span>
                <span class="detail-value"><?php echo $booking['ticket_quantity']; ?> ticket(s)</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value" style="text-transform: capitalize;"><?php echo str_replace('_', ' ', $booking['payment_method']); ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Booking Date:</span>
                <span class="detail-value"><?php echo date('F j, Y g:i A', strtotime($booking['booking_date'])); ?></span>
            </div>
        </div>
        
        <div class="total-amount">
            Final Amount: ₹<?php echo number_format($booking['final_amount'], 2); ?>
            <?php if($booking['discount_amount'] > 0): ?>
                <div style="font-size: 0.7em; opacity: 0.9;">(Saved ₹<?php echo number_format($booking['discount_amount'], 2); ?> with discount!)</div>
            <?php endif; ?>
        </div>
        
        <div class="actions">
            <a href="user1_dashboard.php?section=booked_events" class="btn btn-primary">
                📅 View My Bookings
            </a>
            <a href="index_event.php" class="btn btn-secondary">
                ➕ Book Another Event
            </a>
            <button onclick="window.print()" class="btn btn-print">
                🖨️ Print Confirmation
            </button>
        </div>
    </div>

    <script>
        // Celebration effect
        setTimeout(() => {
            console.log('🎉 Booking confirmed!');
        }, 500);

        // Add click effects
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255,255,255,0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>