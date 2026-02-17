<?php
session_start();

// Create data directory if it doesn't exist
if (!is_dir('data')) {
    mkdir('data', 0777, true);
}

// Initialize data files
$events_file = 'data/events.json';
$bookings_file = 'data/bookings.json';

// Initialize empty files if they don't exist
if (!file_exists($events_file)) file_put_contents($events_file, '[]');
if (!file_exists($bookings_file)) file_put_contents($bookings_file, '[]');

// Load data
$events = json_decode(file_get_contents($events_file), true) ?? [];
$bookings = json_decode(file_get_contents($bookings_file), true) ?? [];

// For demo purposes - auto login as demo user if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Demo User';
    $_SESSION['user_email'] = 'demo@example.com';
}

// Get event details
$event = null;
$error = '';
$success = '';

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    foreach ($events as $e) {
        if ($e['id'] == $event_id && $e['status'] == 'active') {
            $event = $e;
            break;
        }
    }
    
    if (!$event) {
        $error = "Event not found or inactive!";
    }
} else {
    $error = "No event selected! <a href='events_list.php' style='color: #007bff;'>Browse Events</a>";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step1'])) {
        // Step 1: Personal Details
        $required_fields = ['full_name', 'email', 'phone', 'members_count', 'booking_date'];
        $valid = true;
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $valid = false;
                $error = "Please fill in all required fields!";
                break;
            }
        }
        
        if ($valid) {
            $_SESSION['booking_details'] = [
                'full_name' => htmlspecialchars(trim($_POST['full_name'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'phone' => htmlspecialchars(trim($_POST['phone'])),
                'members_count' => intval($_POST['members_count']),
                'booking_date' => $_POST['booking_date'],
                'special_requirements' => htmlspecialchars(trim($_POST['special_requirements'] ?? ''))
            ];
            $_SESSION['current_step'] = 2;
        }
    }
    elseif (isset($_POST['step2'])) {
        // Step 2: Payment Method
        if (empty($_POST['payment_method'])) {
            $error = "Please select a payment method!";
        } else {
            $_SESSION['booking_details']['payment_method'] = $_POST['payment_method'];
            $_SESSION['booking_details']['card_number'] = $_POST['card_number'] ?? '';
            $_SESSION['booking_details']['expiry_date'] = $_POST['expiry_date'] ?? '';
            $_SESSION['booking_details']['cvv'] = $_POST['cvv'] ?? '';
            $_SESSION['booking_details']['upi_id'] = $_POST['upi_id'] ?? '';
            $_SESSION['current_step'] = 3;
        }
    }
    elseif (isset($_POST['step3'])) {
        // Step 3: Final Confirmation and Save Booking
        try {
            // Calculate total amount
            $total_amount = $event['price'] * $_SESSION['booking_details']['members_count'];
            
            // Generate booking reference
            $booking_reference = 'BK' . date('YmdHis') . rand(100, 999);
            
            // Create booking record
            $booking_data = [
                'id' => count($bookings) + 1,
                'booking_reference' => $booking_reference,
                'user_id' => $_SESSION['user_id'],
                'user_name' => $_SESSION['user_name'],
                'event_id' => $event['id'],
                'event_title' => $event['title'],
                'full_name' => $_SESSION['booking_details']['full_name'],
                'email' => $_SESSION['booking_details']['email'],
                'phone' => $_SESSION['booking_details']['phone'],
                'members_count' => $_SESSION['booking_details']['members_count'],
                'booking_date' => $_SESSION['booking_details']['booking_date'],
                'special_requirements' => $_SESSION['booking_details']['special_requirements'],
                'payment_method' => $_SESSION['booking_details']['payment_method'],
                'card_number' => $_SESSION['booking_details']['card_number'],
                'expiry_date' => $_SESSION['booking_details']['expiry_date'],
                'cvv' => $_SESSION['booking_details']['cvv'],
                'upi_id' => $_SESSION['booking_details']['upi_id'],
                'total_amount' => $total_amount,
                'status' => 'confirmed',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Add to bookings array
            $bookings[] = $booking_data;
            
            // Save to file
            if (file_put_contents($bookings_file, json_encode($bookings, JSON_PRETTY_PRINT))) {
                $success = "Booking confirmed successfully! Your booking reference: " . $booking_reference;
                $_SESSION['last_booking'] = $booking_data;
                
                // Clear booking session
                unset($_SESSION['booking_details']);
                unset($_SESSION['current_step']);
                
                // Redirect to confirmation page
                header("Location: booking_confirmation.php?ref=" . $booking_reference);
                exit();
            } else {
                throw new Exception("Failed to save booking data.");
            }
            
        } catch (Exception $e) {
            $error = "Booking failed: " . $e->getMessage();
        }
    }
}

// Get current step
$current_step = $_SESSION['current_step'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo $event['title'] ?? 'Event'; ?></title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .booking-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 4px;
            background: #e0e0e0;
            z-index: 1;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            width: <?php 
                if ($current_step == 1) echo '0%';
                elseif ($current_step == 2) echo '50%';
                else echo '100%';
            ?>;
            height: 4px;
            background: #4CAF50;
            transition: width 0.3s ease;
            z-index: 2;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 3;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: #4CAF50;
            color: white;
            transform: scale(1.1);
        }

        .step.completed .step-circle {
            background: #4CAF50;
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #4CAF50;
            font-weight: bold;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .event-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #4CAF50;
        }

        .event-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .event-info p {
            color: #666;
            margin-bottom: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: #4CAF50;
        }

        .payment-method.selected {
            border-color: #4CAF50;
            background: rgba(76, 175, 80, 0.1);
        }

        .payment-icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #666;
        }

        .payment-method.selected .payment-icon {
            color: #4CAF50;
        }

        .payment-details {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .payment-details.active {
            display: block;
        }

        .card-fields {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #2c3e50;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        @media (max-width: 768px) {
            .booking-container {
                padding: 20px;
            }
            
            .progress-bar {
                margin-bottom: 30px;
            }
            
            .step-label {
                font-size: 10px;
            }
            
            .card-fields {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="step <?php echo $current_step >= 1 ? 'active' : ''; ?> <?php echo $current_step > 1 ? 'completed' : ''; ?>">
                <div class="step-circle">1</div>
                <div class="step-label">Personal Details</div>
            </div>
            <div class="step <?php echo $current_step >= 2 ? 'active' : ''; ?> <?php echo $current_step > 2 ? 'completed' : ''; ?>">
                <div class="step-circle">2</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step <?php echo $current_step >= 3 ? 'active' : ''; ?>">
                <div class="step-circle">3</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Event Information -->
        <?php if ($event): ?>
            <div class="event-info">
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo number_format($event['price'], 2); ?> per person</p>
            </div>
        <?php endif; ?>

        <!-- Step 1: Personal Details -->
        <?php if ($current_step == 1 && $event): ?>
            <div class="form-section active">
                <h2><i class="fas fa-user"></i> Personal Information</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="<?php echo $_SESSION['booking_details']['full_name'] ?? $_SESSION['user_name'] ?? ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $_SESSION['booking_details']['email'] ?? $_SESSION['user_email'] ?? ''; ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo $_SESSION['booking_details']['phone'] ?? ''; ?>" 
                               placeholder="+91 9876543210" required>
                    </div>

                    <div class="form-group">
                        <label for="members_count">Number of Members *</label>
                        <select id="members_count" name="members_count" required>
                            <option value="">Select members</option>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>" 
                                    <?php echo ($_SESSION['booking_details']['members_count'] ?? '') == $i ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> member<?php echo $i > 1 ? 's' : ''; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="booking_date">Preferred Date *</label>
                        <input type="date" id="booking_date" name="booking_date" 
                               value="<?php echo $_SESSION['booking_details']['booking_date'] ?? ''; ?>" 
                               min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="special_requirements">Special Requirements</label>
                        <textarea id="special_requirements" name="special_requirements" rows="3" 
                                  placeholder="Any special requests or requirements..."><?php echo $_SESSION['booking_details']['special_requirements'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="events_list.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Events
                        </a>
                        <button type="submit" name="step1" class="btn btn-primary">
                            Continue to Payment <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Step 2: Payment Method -->
        <?php if ($current_step == 2 && $event): ?>
            <div class="form-section active">
                <h2><i class="fas fa-credit-card"></i> Payment Method</h2>
                <form method="POST">
                    <div class="payment-methods">
                        <div class="payment-method" data-method="credit_card">
                            <div class="payment-icon"><i class="fas fa-credit-card"></i></div>
                            <div>Credit Card</div>
                        </div>
                        <div class="payment-method" data-method="debit_card">
                            <div class="payment-icon"><i class="fas fa-credit-card"></i></div>
                            <div>Debit Card</div>
                        </div>
                        <div class="payment-method" data-method="upi">
                            <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
                            <div>UPI</div>
                        </div>
                    </div>

                    <input type="hidden" id="payment_method" name="payment_method" 
                           value="<?php echo $_SESSION['booking_details']['payment_method'] ?? ''; ?>" required>

                    <!-- Credit/Debit Card Details -->
                    <div class="payment-details" id="card_details">
                        <h4>Card Details</h4>
                        <div class="card-fields">
                            <div class="form-group">
                                <label for="card_number">Card Number</label>
                                <input type="text" id="card_number" name="card_number" 
                                       value="<?php echo $_SESSION['booking_details']['card_number'] ?? ''; ?>" 
                                       placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="month" id="expiry_date" name="expiry_date" 
                                       value="<?php echo $_SESSION['booking_details']['expiry_date'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" 
                                       value="<?php echo $_SESSION['booking_details']['cvv'] ?? ''; ?>" 
                                       placeholder="123" maxlength="3">
                            </div>
                        </div>
                    </div>

                    <!-- UPI Details -->
                    <div class="payment-details" id="upi_details">
                        <div class="form-group">
                            <label for="upi_id">UPI ID</label>
                            <input type="text" id="upi_id" name="upi_id" 
                                   value="<?php echo $_SESSION['booking_details']['upi_id'] ?? ''; ?>" 
                                   placeholder="yourname@upi">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" name="step2" class="btn btn-primary">
                            Review Booking <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Step 3: Confirmation -->
        <?php if ($current_step == 3 && $event): ?>
            <div class="form-section active">
                <h2><i class="fas fa-check-circle"></i> Confirm Booking</h2>
                
                <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-shield-alt" style="color: #4CAF50; font-size: 24px; margin-bottom: 10px;"></i>
                    <h3 style="color: #2e7d32; margin-bottom: 10px;">Security Verified!</h3>
                    <p style="color: #388e3c;">Your information has been securely processed.</p>
                </div>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                    <h4>Booking Summary</h4>
                    <div class="summary-item">
                        <span>Event:</span>
                        <span><?php echo htmlspecialchars($event['title']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Full Name:</span>
                        <span><?php echo htmlspecialchars($_SESSION['booking_details']['full_name']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Email:</span>
                        <span><?php echo htmlspecialchars($_SESSION['booking_details']['email']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Phone:</span>
                        <span><?php echo htmlspecialchars($_SESSION['booking_details']['phone']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Members:</span>
                        <span><?php echo $_SESSION['booking_details']['members_count']; ?> person(s)</span>
                    </div>
                    <div class="summary-item">
                        <span>Booking Date:</span>
                        <span><?php echo date('F j, Y', strtotime($_SESSION['booking_details']['booking_date'])); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Payment Method:</span>
                        <span style="text-transform: capitalize;">
                            <?php echo str_replace('_', ' ', $_SESSION['booking_details']['payment_method']); ?>
                        </span>
                    </div>
                    <div class="summary-item">
                        <span>Total Amount:</span>
                        <span style="color: #4CAF50; font-weight: bold;">
                            ₹<?php echo number_format($event['price'] * $_SESSION['booking_details']['members_count'], 2); ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($_SESSION['booking_details']['special_requirements'])): ?>
                    <div style="background: #fff3cd; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                        <h4><i class="fas fa-star"></i> Special Requirements</h4>
                        <p><?php echo htmlspecialchars($_SESSION['booking_details']['special_requirements']); ?></p>
                    </div>
                <?php endif; ?>

                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <h4><i class="fas fa-info-circle"></i> Important Information</h4>
                    <ul style="padding-left: 20px; margin-bottom: 0;">
                        <li>Please arrive 15 minutes before the scheduled time</li>
                        <li>Carry a valid ID proof for verification</li>
                        <li>Cancellation policy: 24 hours notice required</li>
                        <li>For any queries, contact: support@events.com</li>
                    </ul>
                </div>

                <form method="POST">
                    <div class="form-actions">
                        <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" name="step3" class="btn btn-primary">
                            <i class="fas fa-check"></i> Confirm & Pay Now
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Payment method selection
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethods = document.querySelectorAll('.payment-method');
            const paymentMethodInput = document.getElementById('payment_method');
            const cardDetails = document.getElementById('card_details');
            const upiDetails = document.getElementById('upi_details');
            
            // Set initial state
            const currentMethod = paymentMethodInput.value;
            if (currentMethod === 'credit_card' || currentMethod === 'debit_card') {
                showCardDetails();
                selectPaymentMethod(currentMethod);
            } else if (currentMethod === 'upi') {
                showUpiDetails();
                selectPaymentMethod('upi');
            }
            
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    const methodType = this.getAttribute('data-method');
                    paymentMethodInput.value = methodType;
                    
                    // Remove selected class from all methods
                    paymentMethods.forEach(m => m.classList.remove('selected'));
                    
                    // Add selected class to clicked method
                    this.classList.add('selected');
                    
                    // Show appropriate payment details
                    if (methodType === 'credit_card' || methodType === 'debit_card') {
                        showCardDetails();
                    } else if (methodType === 'upi') {
                        showUpiDetails();
                    }
                });
            });
            
            function showCardDetails() {
                cardDetails.classList.add('active');
                upiDetails.classList.remove('active');
            }
            
            function showUpiDetails() {
                upiDetails.classList.add('active');
                cardDetails.classList.remove('active');
            }
            
            function selectPaymentMethod(method) {
                paymentMethods.forEach(m => {
                    if (m.getAttribute('data-method') === method) {
                        m.classList.add('selected');
                    }
                });
            }
            
            // Format card number
            const cardNumberInput = document.getElementById('card_number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ');
                    if (formattedValue) {
                        e.target.value = formattedValue;
                    }
                });
            }
            
            // Format CVV
            const cvvInput = document.getElementById('cvv');
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</body>
</html>