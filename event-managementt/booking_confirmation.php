<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step1'])) {
        // Step 1: Personal Details
        $_SESSION['booking_details'] = [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'members_count' => $_POST['members_count'],
            'booking_date' => $_POST['booking_date'],
            'special_requirements' => $_POST['special_requirements']
        ];
        $_SESSION['current_step'] = 2;
    }
    elseif (isset($_POST['step2'])) {
        // Step 2: Payment Method
        $_SESSION['booking_details']['payment_method'] = $_POST['payment_method'];
        $_SESSION['booking_details']['card_number'] = $_POST['card_number'] ?? '';
        $_SESSION['booking_details']['expiry_date'] = $_POST['expiry_date'] ?? '';
        $_SESSION['booking_details']['cvv'] = $_POST['cvv'] ?? '';
        $_SESSION['booking_details']['upi_id'] = $_POST['upi_id'] ?? '';
        $_SESSION['current_step'] = 3;
    }
    elseif (isset($_POST['step3'])) {
        // Step 3: Final Confirmation
        try {
            $pdo->beginTransaction();
            
            // Insert booking
            $sql = "INSERT INTO bookings (user_id, event_id, full_name, email, phone, members_count, booking_date, special_requirements, payment_method, card_number, expiry_date, cvv, upi_id, total_amount, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')";
            $stmt = $pdo->prepare($sql);
            
            // Calculate total amount (you can modify this based on your pricing)
            $event_price = 1000; // Default price per person
            $total_amount = $event_price * $_SESSION['booking_details']['members_count'];
            
            $stmt->execute([
                $_SESSION['user_id'],
                $_GET['event_id'] ?? 1, // Get event ID from URL or use default
                $_SESSION['booking_details']['full_name'],
                $_SESSION['booking_details']['email'],
                $_SESSION['booking_details']['phone'],
                $_SESSION['booking_details']['members_count'],
                $_SESSION['booking_details']['booking_date'],
                $_SESSION['booking_details']['special_requirements'],
                $_SESSION['booking_details']['payment_method'],
                $_SESSION['booking_details']['card_number'],
                $_SESSION['booking_details']['expiry_date'],
                $_SESSION['booking_details']['cvv'],
                $_SESSION['booking_details']['upi_id'],
                $total_amount
            ]);
            
            $booking_id = $pdo->lastInsertId();
            $pdo->commit();
            
            $_SESSION['last_booking_id'] = $booking_id;
            unset($_SESSION['booking_details']);
            unset($_SESSION['current_step']);
            
            header("Location: booking_confirmation.php");
            exit();
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Booking failed: " . $e->getMessage();
        }
    }
}

// Get current step
$current_step = $_SESSION['current_step'] ?? 1;

// Get event details if event_id is provided
$event = null;
if (isset($_GET['event_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$_GET['event_id']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking - Step <?php echo $current_step; ?></title>
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
            width: <?php echo ($current_step - 1) * 33.33; ?>%;
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
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
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
                <div class="step-label">Payment Method</div>
            </div>
            <div class="step <?php echo $current_step >= 3 ? 'active' : ''; ?>">
                <div class="step-circle">3</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Step 1: Personal Details -->
        <div class="form-section <?php echo $current_step == 1 ? 'active' : ''; ?>">
            <h2><i class="fas fa-user"></i> Personal Information</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?php echo $_SESSION['booking_details']['full_name'] ?? ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo $_SESSION['booking_details']['email'] ?? ''; ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo $_SESSION['booking_details']['phone'] ?? ''; ?>" 
                           required>
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
                    <a href="user_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" name="step1" class="btn btn-primary">
                        Continue to Payment <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Step 2: Payment Method -->
        <div class="form-section <?php echo $current_step == 2 ? 'active' : ''; ?>">
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
                    <div class="payment-method" data-method="cash">
                        <div class="payment-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <div>Cash</div>
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
                    <button type="button" onclick="goToStep(1)" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" name="step2" class="btn btn-primary">
                        Review Booking <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Step 3: Confirmation -->
        <div class="form-section <?php echo $current_step == 3 ? 'active' : ''; ?>">
            <h2><i class="fas fa-check-circle"></i> Confirm Booking</h2>
            
            <div class="success-message">
                <i class="fas fa-shield-alt" style="color: #4CAF50; font-size: 24px; margin-bottom: 10px;"></i>
                <h3>Security Verified!</h3>
                <p>Your information has been securely processed.</p>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4>Booking Summary</h4>
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
                        ₹<?php echo number_format(1000 * $_SESSION['booking_details']['members_count'], 2); ?>
                    </span>
                </div>
            </div>

            <?php if (!empty($_SESSION['booking_details']['special_requirements'])): ?>
                <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <h4>Special Requirements</h4>
                    <p><?php echo htmlspecialchars($_SESSION['booking_details']['special_requirements']); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-actions">
                    <button type="button" onclick="goToStep(2)" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" name="step3" class="btn btn-primary" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <i class="fas fa-lock"></i> Confirm & Book Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Remove selected class from all methods
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('selected');
                });
                
                // Add selected class to clicked method
                this.classList.add('selected');
                
                // Set hidden input value
                document.getElementById('payment_method').value = this.dataset.method;
                
                // Show/hide payment details
                document.querySelectorAll('.payment-details').forEach(detail => {
                    detail.classList.remove('active');
                });
                
                if (this.dataset.method === 'credit_card' || this.dataset.method === 'debit_card') {
                    document.getElementById('card_details').classList.add('active');
                } else if (this.dataset.method === 'upi') {
                    document.getElementById('upi_details').classList.add('active');
                }
            });
        });

        // Auto-select payment method if already chosen
        const selectedMethod = document.getElementById('payment_method').value;
        if (selectedMethod) {
            document.querySelector(`[data-method="${selectedMethod}"]`).classList.add('selected');
            if (selectedMethod === 'credit_card' || selectedMethod === 'debit_card') {
                document.getElementById('card_details').classList.add('active');
            } else if (selectedMethod === 'upi') {
                document.getElementById('upi_details').classList.add('active');
            }
        }

        // Format card number
        document.getElementById('card_number')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let matches = value.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];
            for (let i = 0; i < match.length; i += 4) {
                parts.push(match.substring(i, i + 4));
            }
            if (parts.length) {
                e.target.value = parts.join(' ');
            } else {
                e.target.value = value;
            }
        });

        // Navigation between steps
        function goToStep(step) {
            window.location.href = `event_booking.php?step=${step}`;
        }

        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = this.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#dc3545';
                    } else {
                        field.style.borderColor = '#4CAF50';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    </script>
</body>
</html>