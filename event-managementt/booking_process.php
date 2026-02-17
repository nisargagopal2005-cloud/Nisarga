<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_event.php");
    exit();
}
require_once 'config/database.php';

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$event_id = isset($_POST['event_id']) ? $_POST['event_id'] : (isset($_SESSION['booking_event_id']) ? $_SESSION['booking_event_id'] : null);

if (!$event_id && $step > 1) {
    header("Location: index_event.php");
    exit();
}

if ($event_id) {
    $_SESSION['booking_event_id'] = $event_id;
}

// Get event details
$event_stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$event_stmt->execute([$event_id]);
$event = $event_stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: index_event.php");
    exit();
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1 && isset($_POST['ticket_quantity'])) {
        $_SESSION['booking_tickets'] = (int)$_POST['ticket_quantity'];
        header("Location: booking_process.php?step=2");
        exit();
    }
    
    if ($step === 2 && isset($_POST['attendee_name'])) {
        $_SESSION['booking_attendee'] = $_POST['attendee_name'];
        $_SESSION['booking_email'] = $_POST['attendee_email'];
        $_SESSION['booking_phone'] = $_POST['attendee_phone'];
        header("Location: booking_process.php?step=3");
        exit();
    }
    
    if ($step === 3 && isset($_POST['payment_method'])) {
        // Calculate amounts with discount
        $ticket_quantity = $_SESSION['booking_tickets'];
        $total_amount = $event['event_price'] * $ticket_quantity;
        $discount_amount = 0;
        
        // Apply discounts
        if ($total_amount > 100000) {
            $discount_amount = $total_amount * 0.05; // 5% discount
        } elseif ($total_amount > 10000) {
            $discount_amount = $total_amount * 0.03; // 3% discount
        }
        
        $final_amount = $total_amount - $discount_amount;
        
        // Store in session for confirmation
        $_SESSION['booking_total'] = $total_amount;
        $_SESSION['booking_discount'] = $discount_amount;
        $_SESSION['booking_final'] = $final_amount;
        $_SESSION['payment_method'] = $_POST['payment_method'];
        
        header("Location: process_payment.php");
        exit();
    }
}

$ticket_quantity = $_SESSION['booking_tickets'] ?? 1;
$total_amount = $event['event_price'] * $ticket_quantity;
$discount_amount = 0;

if ($total_amount > 100000) {
    $discount_amount = $total_amount * 0.05;
} elseif ($total_amount > 10000) {
    $discount_amount = $total_amount * 0.03;
}

$final_amount = $total_amount - $discount_amount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event - Step <?php echo $step; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .booking-container { background: rgba(255,255,255,0.95); border-radius: 25px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.2); max-width: 600px; width: 100%; backdrop-filter: blur(20px); }
        .progress-steps { display: flex; justify-content: space-between; margin-bottom: 40px; position: relative; }
        .progress-steps::before { content: ''; position: absolute; top: 15px; left: 0; right: 0; height: 4px; background: #e9ecef; z-index: 1; }
        .progress-bar { position: absolute; top: 15px; left: 0; height: 4px; background: #4CAF50; transition: all 0.3s ease; z-index: 2; }
        .step { text-align: center; z-index: 3; position: relative; }
        .step-circle { width: 40px; height: 40px; border-radius: 50%; background: #e9ecef; color: #6c757d; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: bold; border: 3px solid #e9ecef; transition: all 0.3s ease; }
        .step.active .step-circle, .step.completed .step-circle { background: #4CAF50; color: white; border-color: #4CAF50; }
        .step-label { font-size: 0.9em; color: #6c757d; font-weight: 500; }
        .step.active .step-label { color: #4CAF50; font-weight: 600; }
        .form-section { display: none; animation: fadeIn 0.5s ease; }
        .form-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        .event-info { background: #f8f9fa; padding: 20px; border-radius: 15px; margin-bottom: 25px; border-left: 4px solid #4CAF50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 600; }
        input, select { width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1em; transition: all 0.3s ease; }
        input:focus, select:focus { outline: none; border-color: #4CAF50; box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1); }
        .btn { padding: 15px 30px; border: none; border-radius: 10px; font-size: 1.1em; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; text-align: center; }
        .btn-primary { background: linear-gradient(135deg, #4CAF50, #45a049); color: white; width: 100%; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3); }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .amount-summary { background: linear-gradient(135deg, #4CAF50, #45a049); color: white; padding: 25px; border-radius: 15px; margin: 25px 0; text-align: center; }
        .amount-line { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.3); }
        .discount { color: #ffeb3b; font-weight: 800; }
        .final-amount { font-size: 2em; font-weight: 800; margin-top: 15px; }
        .payment-options { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 20px 0; }
        .payment-option { border: 2px solid #e9ecef; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; }
        .payment-option:hover, .payment-option.selected { border-color: #4CAF50; transform: translateY(-2px); }
        .payment-option.selected { background: rgba(76, 175, 80, 0.1); }
        .payment-icon { font-size: 2em; margin-bottom: 10px; }
        .payment-details { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-top: 10px; display: none; }
        .payment-details.active { display: block; }
    </style>
</head>
<body>
    <div class="booking-container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="progress-bar" style="width: <?php echo ($step - 1) * 33.33; ?>%;"></div>
            
            <div class="step <?php echo $step >= 1 ? 'completed' : ''; ?> <?php echo $step == 1 ? 'active' : ''; ?>">
                <div class="step-circle">1</div>
                <div class="step-label">Tickets</div>
            </div>
            
            <div class="step <?php echo $step >= 2 ? 'completed' : ''; ?> <?php echo $step == 2 ? 'active' : ''; ?>">
                <div class="step-circle">2</div>
                <div class="step-label">Details</div>
            </div>
            
            <div class="step <?php echo $step >= 3 ? 'completed' : ''; ?> <?php echo $step == 3 ? 'active' : ''; ?>">
                <div class="step-circle">3</div>
                <div class="step-label">Payment</div>
            </div>
        </div>

        <!-- Event Information -->
        <div class="event-info">
            <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
            <p>📅 <?php echo date('F j, Y', strtotime($event['event_date'])); ?> 
               ⏰ <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
            <p>📍 <?php echo htmlspecialchars($event['event_location']); ?></p>
            <p>🎫 Price: ₹<?php echo number_format($event['event_price'], 2); ?> per ticket</p>
        </div>

        <!-- Step 1: Ticket Selection -->
        <div class="form-section <?php echo $step == 1 ? 'active' : ''; ?>">
            <h2>Select Number of Tickets</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="ticket_quantity">How many tickets would you like?</label>
                    <select name="ticket_quantity" id="ticket_quantity" required>
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($ticket_quantity == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?> ticket<?php echo $i > 1 ? 's' : ''; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Amount Preview -->
                <div class="amount-summary">
                    <div class="amount-line">
                        <span>Total Amount:</span>
                        <span>₹<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <?php if($discount_amount > 0): ?>
                        <div class="amount-line discount">
                            <span>Discount Applied:</span>
                            <span>- ₹<?php echo number_format($discount_amount, 2); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="final-amount">
                        ₹<?php echo number_format($final_amount, 2); ?>
                    </div>
                    <?php if($discount_amount > 0): ?>
                        <div style="font-size: 0.9em; margin-top: 10px;">
                            🎉 You saved ₹<?php echo number_format($discount_amount, 2); ?>!
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Continue to Details →</button>
            </form>
        </div>

        <!-- Step 2: Attendee Details -->
        <div class="form-section <?php echo $step == 2 ? 'active' : ''; ?>">
            <h2>Attendee Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="attendee_name">Full Name *</label>
                    <input type="text" name="attendee_name" id="attendee_name" 
                           value="<?php echo $_SESSION['booking_attendee'] ?? ''; ?>" 
                           placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="attendee_email">Email Address *</label>
                    <input type="email" name="attendee_email" id="attendee_email" 
                           value="<?php echo $_SESSION['booking_email'] ?? ''; ?>" 
                           placeholder="Enter your email address" required>
                </div>

                <div class="form-group">
                    <label for="attendee_phone">Phone Number *</label>
                    <input type="tel" name="attendee_phone" id="attendee_phone" 
                           value="<?php echo $_SESSION['booking_phone'] ?? ''; ?>" 
                           placeholder="Enter your phone number" required>
                </div>

                <div style="display: flex; gap: 15px;">
                    <a href="booking_process.php?step=1" class="btn btn-secondary" style="flex: 1;">← Back</a>
                    <button type="submit" class="btn btn-primary" style="flex: 2;">Continue to Payment →</button>
                </div>
            </form>
        </div>

        <!-- Step 3: Payment Method -->
        <div class="form-section <?php echo $step == 3 ? 'active' : ''; ?>">
            <h2>Select Payment Method</h2>
            
            <!-- Final Amount Summary -->
            <div class="amount-summary">
                <div class="amount-line">
                    <span>Tickets (<?php echo $ticket_quantity; ?>):</span>
                    <span>₹<?php echo number_format($total_amount, 2); ?></span>
                </div>
                <?php if($discount_amount > 0): ?>
                    <div class="amount-line discount">
                        <span>Discount (<?php echo ($total_amount > 100000) ? '5%' : '3%'; ?>):</span>
                        <span>- ₹<?php echo number_format($discount_amount, 2); ?></span>
                    </div>
                <?php endif; ?>
                <div class="final-amount">
                    Final Amount: ₹<?php echo number_format($final_amount, 2); ?>
                </div>
            </div>

            <form method="POST">
                <div class="payment-options">
                    <label class="payment-option" data-method="upi">
                        <input type="radio" name="payment_method" value="upi" required style="display: none;">
                        <div class="payment-icon">📱</div>
                        <div>UPI Payment</div>
                    </label>

                    <label class="payment-option" data-method="credit_card">
                        <input type="radio" name="payment_method" value="credit_card" required style="display: none;">
                        <div class="payment-icon">💳</div>
                        <div>Credit Card</div>
                    </label>

                    <label class="payment-option" data-method="debit_card">
                        <input type="radio" name="payment_method" value="debit_card" required style="display: none;">
                        <div class="payment-icon">💳</div>
                        <div>Debit Card</div>
                    </label>

                    <label class="payment-option" data-method="cash">
                        <input type="radio" name="payment_method" value="cash" required style="display: none;">
                        <div class="payment-icon">💵</div>
                        <div>Cash</div>
                    </label>
                </div>

                <!-- Payment Details -->
                <div id="payment-details" class="payment-details">
                    <div id="upi-details">
                        <h4>UPI Payment</h4>
                        <p>Scan the QR code or use UPI ID: <strong>eventbook@upi</strong></p>
                        <p>📱 Pay using any UPI app</p>
                    </div>
                    
                    <div id="card-details">
                        <h4>Card Payment</h4>
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" placeholder="1234 5678 9012 3456">
                        </div>
                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label>Expiry Date</label>
                                <input type="text" placeholder="MM/YY">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label>CVV</label>
                                <input type="text" placeholder="123">
                            </div>
                        </div>
                    </div>
                    
                    <div id="cash-details">
                        <h4>Cash Payment</h4>
                        <p>💰 Pay at the event venue</p>
                        <p>Please bring exact change and your booking confirmation</p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="booking_process.php?step=2" class="btn btn-secondary" style="flex: 1;">← Back</a>
                    <button type="submit" class="btn btn-primary" style="flex: 2;">
                        Complete Booking 🎉
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Payment option selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Show payment details
                const method = this.getAttribute('data-method');
                showPaymentDetails(method);
            });
        });

        function showPaymentDetails(method) {
            const detailsContainer = document.getElementById('payment-details');
            detailsContainer.classList.add('active');
            
            // Hide all details first
            document.querySelectorAll('#payment-details > div').forEach(div => {
                div.style.display = 'none';
            });
            
            // Show selected method details
            if (method === 'upi') {
                document.getElementById('upi-details').style.display = 'block';
            } else if (method === 'credit_card' || method === 'debit_card') {
                document.getElementById('card-details').style.display = 'block';
            } else if (method === 'cash') {
                document.getElementById('cash-details').style.display = 'block';
            }
        }

        // Auto-select first payment method
        document.addEventListener('DOMContentLoaded', function() {
            const firstOption = document.querySelector('.payment-option');
            if (firstOption) {
                firstOption.click();
            }
        });
    </script>
</body>
</html>