<style>
.payment-content {
    text-align: left;
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.payment-content h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #2c3e50;
    font-size: 2.5rem;
}
.payment-content h2 {
    margin-top: 40px;
    margin-bottom: 20px;
    color: #2c3e50;
    border-bottom: 2px solid #ff6b81;
    padding-bottom: 10px;
    font-size: 1.8rem;
}
.payment-content h3 {
    margin-top: 25px;
    margin-bottom: 15px;
    color: #34495e;
    font-size: 1.4rem;
}
.payment-content p {
    margin-bottom: 20px;
    line-height: 1.6;
    font-size: 1.1rem;
}
.payment-content .event-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    border-left: 6px solid #ff6b81;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.payment-content .event-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.payment-content .event-item strong {
    color: #2c3e50;
    font-size: 1.3rem;
    display: block;
    margin-bottom: 10px;
}
.payment-content .price-range {
    color: #e74c3c;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 15px 0;
    padding: 10px;
    background: #fff;
    border-radius: 8px;
    border-left: 4px solid #e74c3c;
}
.payment-content .breakdown {
    margin: 15px 0;
    padding-left: 25px;
}
.payment-content .breakdown li {
    margin-bottom: 10px;
    font-size: 1.1rem;
    line-height: 1.5;
}
.payment-content hr {
    margin: 2.5rem 0;
    border: none;
    border-top: 3px solid #ddd;
}
.payment-content strong {
    color: #2c3e50;
}
.qr-code {
    max-width: 280px;
    display: block;
    margin: 1.5rem auto;
    border: 3px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    padding: 10px;
    background: white;
}
.payment-info {
    background: linear-gradient(135deg, #e8f4fd, #d4edda);
    padding: 20px;
    border-radius: 12px;
    margin: 25px 0;
    border-left: 6px solid #3498db;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.payment-info h3 {
    color: #2c3e50;
    margin-top: 0;
}
.event-number {
    background: #ff6b81;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: bold;
    margin-right: 10px;
    font-size: 1.1rem;
}
.event-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}
/* Ensure all content is visible */
.event-item {
    min-height: auto;
    overflow: visible;
}
/* Fix any potential hiding issues */
.payment-content * {
    max-height: none !important;
    overflow: visible !important;
}
</style>

<div class="payment-content">
    <h1><i class="fas fa-credit-card"></i> Payment Information & Pricing</h1>
    
    <div class="payment-info">
        <h3><i class="fas fa-info-circle"></i> Payment Instructions</h3>
        <p>All payments should be made in advance to confirm your booking. Please review the pricing details below and use our online payment methods.</p>
    </div>

    <h2>🎉 Event Pricing Packages</h2>

    <!-- Event 1 - Fixed -->
    <div class="event-item">
        <div class="event-header">
            <span class="event-number">1</span>
            <strong>🎂 Small Birthday Party — 10–20 guests</strong>
        </div>
        <p class="price-range">Total: ₹2,000 → ₹45,000</p>
        <ul class="breakdown">
            <li>• Venue (home / small hall): ₹2,000 → ₹8,000</li>
            <li>• Food & cake (₹250–₹800/plate): ₹5,000 → ₹32,000</li>
            <li>• Decor & balloons: ₹800 → ₹6,000</li>
            <li>• Entertainment (DJ / games / magician): ₹2,000 → ₹4,000</li>
            <li>• Photography / misc: ₹200 → ₹5,000</li>
        </ul>
        <p style="font-style: italic; color: #666; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px;">
            <i class="fas fa-lightbulb"></i> Good for kids' parties or intimate adult gatherings.
        </p>
    </div>

    <!-- Event 2 - Fixed -->
    <div class="event-item">
        <div class="event-header">
            <span class="event-number">2</span>
            <strong>🎉 Medium Birthday / Milestone — 80–150 guests</strong>
        </div>
        <p class="price-range">Total: ₹60,000 → ₹3,50,000</p>
        <ul class="breakdown">
            <li>• Venue (banquet / lawn): ₹15,000 → ₹1,00,000</li>
            <li>• Catering (₹400–₹1,200/plate): ₹32,000 → ₹1,80,000</li>
            <li>• Decor & lighting: ₹6,000 → ₹60,000</li>
            <li>• DJ / live music / MC: ₹3,000 → ₹50,000</li>
            <li>• Photography, cake, misc: ₹4,000 → ₹60,000</li>
        </ul>
        <p style="font-style: italic; color: #666; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px;">
            <i class="fas fa-lightbulb"></i> Suitable for big family or friend gatherings and milestone birthdays.
        </p>
    </div>

    <!-- Event 3 - Fixed -->
    <div class="event-item">
        <div class="event-header">
            <span class="event-number">3</span>
            <strong>👶 Baby Shower — 40–80 guests</strong>
        </div>
        <p class="price-range">Total: ₹25,000 → ₹1,20,000</p>
        <ul class="breakdown">
            <li>• Venue (home / small hall): ₹0 → ₹15,000</li>
            <li>• Catering (₹300–₹900/plate): ₹12,000 → ₹72,000</li>
            <li>• Decor (floral, backdrop, props): ₹3,000 → ₹20,000</li>
            <li>• Games / host / photographer: ₹2,000 → ₹8,000</li>
            <li>• Gifts / return favors: ₹1,500 → ₹5,000</li>
        </ul>
        <p style="font-style: italic; color: #666; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px;">
            <i class="fas fa-lightbulb"></i> Focus on comfortable seating, soft décor and light refreshments.
        </p>
    </div>

    <!-- Event 4 -->
    <div class="event-item">
        <div class="event-header">
            <span class="event-number">4</span>
            <strong>💕 Anniversary Party — 30–80 guests</strong>
        </div>
        <p class="price-range">Total: ₹20,000 → ₹1,50,000</p>
        <ul class="breakdown">
            <li>• Venue: ₹0 → ₹30,000</li>
            <li>• Catering (₹350–₹1,000/plate): ₹10,500 → ₹80,000</li>
            <li>• Decor & floral: ₹2,500 → ₹25,000</li>
            <li>• Music / memories (video/photography): ₹2,000 → ₹12,000</li>
            <li>• Cake & extras: ₹1,000 → ₹3,000</li>
        </ul>
        <p style="font-style: italic; color: #666; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px;">
            <i class="fas fa-lightbulb"></i> Great for intimate celebrations or upscale dinner parties.
        </p>
    </div>

    <!-- Event 5 -->
    <div class="event-item">
        <div class="event-header">
            <span class="event-number">5</span>
            <strong>🏠 Housewarming (Griha Pravesh) — 30–120 guests</strong>
        </div>
        <p class="price-range">Total: ₹15,000 → ₹1,40,000</p>
        <ul class="breakdown">
            <li>• Catering (simple prasad/snacks to full meal): ₹6,000 → ₹72,000</li>
            <li>• Puja items / priest: ₹800 → ₹6,000</li>
            <li>• Small decor / rangoli / flowers: ₹1,000 → ₹15,000</li>
            <li>• Gifts / return favors: ₹1,000 → ₹10,000</li>
            <li>• Misc (cleaning, helpers): ₹1,000 → ₹5,000</li>
        </ul>
        <p style="font-style: italic; color: #666; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px;">
            <i class="fas fa-lightbulb"></i> Can be very budget-friendly if held at home.
        </p>
    </div>

    <hr>

    <h2><i class="fas fa-mobile-alt"></i> Online Payment Methods</h2>
    
    <div class="payment-info">
        <h3><i class="fas fa-qrcode"></i> Quick Payment Options</h3>
        <p style="font-size: 1.1rem;">
            <strong>Transaction / Contact Number:</strong> <span style="color:#e74c3c; font-size:1.3rem; font-weight:bold;">7022288653</span><br>
            <strong>Accepted UPI Apps:</strong> PhonePe, GPay, Paytm, Amazon Pay<br>
            <strong>Bank Transfer:</strong> Available upon request<br>
            You can pay directly using any of the above apps with the provided number.
        </p>
    </div>

    <div style="text-align:center; margin:40px 0; padding: 20px; background: #f8f9fa; border-radius: 12px;">
        <p style="font-size: 1.2rem; margin-bottom: 20px;"><strong>Scan QR Code to Pay Instantly:</strong></p>
        <!-- Replace with actual QR code image -->
        <div style="background: white; padding: 20px; display: inline-block; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div style="width: 250px; height: 250px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin: 0 auto;">
                <span style="color: #666; font-size: 14px;">[QR Code Image]</span>
            </div>
        </div>
        <p style="color:#666; font-size:1rem; margin-top:20px;">
            <i class="fas fa-qrcode"></i> Scan this QR code with any UPI app to make payment
        </p>
    </div>

    <div class="payment-info">
        <h3><i class="fas fa-shield-alt"></i> Payment Security & Terms</h3>
        <ul class="breakdown" style="font-size: 1.1rem;">
            <li>• 50% advance payment required for booking confirmation</li>
            <li>• Balance payment due 7 days before the event</li>
            <li>• Cancellation policy: 90% refund if cancelled 15 days prior</li>
            <li>• All payments are secure and encrypted</li>
            <li>• Payment receipts will be emailed automatically</li>
        </ul>
    </div>

    <div style="text-align:center; margin-top:40px; padding: 25px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px;">
        <p style="margin: 0; font-size: 1.1rem;">
            <i class="fas fa-phone"></i> Need help with payment? Call: <strong>7022288653</strong> | 
            <i class="fas fa-envelope"></i> Email: support@eventmanagement.com
        </p>
    </div>
</div>