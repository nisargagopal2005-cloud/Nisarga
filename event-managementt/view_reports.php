<?php
// view_reports.php - Self-contained version with sample data (2025 Profit & Loss)

// Sample event data for 2025 with proper event management event names
$sample_events = [
    // January 2025
    [
        'event_id' => 1,
        'event_name' => 'Annual Corporate Conference 2025',
        'event_date' => '2025-01-15',
        'venue' => 'Grand Convention Center',
        'status' => 'completed',
        'budget' => 25000.00,
        'actual_cost' => 24500.00,
        'registrations' => 300,
        'confirmed_registrations' => 285,
        'revenue' => 85500.00,
        'attendance' => 275
    ],
    [
        'event_id' => 2,
        'event_name' => 'Winter Leadership Summit',
        'event_date' => '2025-01-25',
        'venue' => 'Business Tower Hotel',
        'status' => 'completed',
        'budget' => 18000.00,
        'actual_cost' => 17500.00,
        'registrations' => 150,
        'confirmed_registrations' => 140,
        'revenue' => 42000.00,
        'attendance' => 135
    ],

    // February 2025
    [
        'event_id' => 3,
        'event_name' => 'Valentine Charity Gala Dinner',
        'event_date' => '2025-02-14',
        'venue' => 'Luxury Grand Hotel',
        'status' => 'completed',
        'budget' => 35000.00,
        'actual_cost' => 34500.00,
        'registrations' => 200,
        'confirmed_registrations' => 190,
        'revenue' => 95000.00,
        'attendance' => 185
    ],
    [
        'event_id' => 4,
        'event_name' => 'Business Networking Mixer',
        'event_date' => '2025-02-28',
        'venue' => 'Skyline Rooftop',
        'status' => 'completed',
        'budget' => 12000.00,
        'actual_cost' => 11500.00,
        'registrations' => 120,
        'confirmed_registrations' => 110,
        'revenue' => 33000.00,
        'attendance' => 105
    ],

    // March 2025
    [
        'event_id' => 5,
        'event_name' => 'Spring Product Launch Event',
        'event_date' => '2025-03-10',
        'venue' => 'Tech Innovation Hub',
        'status' => 'completed',
        'budget' => 45000.00,
        'actual_cost' => 43500.00,
        'registrations' => 250,
        'confirmed_registrations' => 240,
        'revenue' => 120000.00,
        'attendance' => 235
    ],
    [
        'event_id' => 6,
        'event_name' => 'Corporate Team Building Workshop',
        'event_date' => '2025-03-20',
        'venue' => 'Executive Retreat Center',
        'status' => 'completed',
        'budget' => 22000.00,
        'actual_cost' => 21000.00,
        'registrations' => 80,
        'confirmed_registrations' => 75,
        'revenue' => 60000.00,
        'attendance' => 72
    ],

    // April 2025
    [
        'event_id' => 7,
        'event_name' => 'Annual Shareholders Meeting',
        'event_date' => '2025-04-08',
        'venue' => 'Corporate Headquarters',
        'status' => 'completed',
        'budget' => 18000.00,
        'actual_cost' => 17500.00,
        'registrations' => 150,
        'confirmed_registrations' => 145,
        'revenue' => 0.00, // Internal event
        'attendance' => 140
    ],
    [
        'event_id' => 8,
        'event_name' => 'Industry Trade Show 2025',
        'event_date' => '2025-04-22',
        'venue' => 'Exhibition Center',
        'status' => 'completed',
        'budget' => 75000.00,
        'actual_cost' => 72000.00,
        'registrations' => 500,
        'confirmed_registrations' => 480,
        'revenue' => 240000.00,
        'attendance' => 470
    ],

    // May 2025
    [
        'event_id' => 9,
        'event_name' => 'Executive Leadership Retreat',
        'event_date' => '2025-05-15',
        'venue' => 'Mountain Resort & Spa',
        'status' => 'completed',
        'budget' => 55000.00,
        'actual_cost' => 53000.00,
        'registrations' => 60,
        'confirmed_registrations' => 58,
        'revenue' => 174000.00,
        'attendance' => 56
    ],
    [
        'event_id' => 10,
        'event_name' => 'Customer Appreciation Gala',
        'event_date' => '2025-05-30',
        'venue' => 'Grand Ballroom',
        'status' => 'completed',
        'budget' => 42000.00,
        'actual_cost' => 40500.00,
        'registrations' => 180,
        'confirmed_registrations' => 175,
        'revenue' => 0.00, // Complimentary event
        'attendance' => 170
    ],

    // June 2025
    [
        'event_id' => 11,
        'event_name' => 'Summer Corporate Festival',
        'event_date' => '2025-06-20',
        'venue' => 'City Park Amphitheater',
        'status' => 'completed',
        'budget' => 68000.00,
        'actual_cost' => 65000.00,
        'registrations' => 400,
        'confirmed_registrations' => 380,
        'revenue' => 152000.00,
        'attendance' => 375
    ],
    [
        'event_id' => 12,
        'event_name' => 'HR & Recruitment Fair',
        'event_date' => '2025-06-28',
        'venue' => 'Convention Hall',
        'status' => 'completed',
        'budget' => 32000.00,
        'actual_cost' => 30500.00,
        'registrations' => 250,
        'confirmed_registrations' => 240,
        'revenue' => 120000.00,
        'attendance' => 235
    ],

    // July 2025
    [
        'event_id' => 13,
        'event_name' => 'Mid-Year Business Review Conference',
        'event_date' => '2025-07-15',
        'venue' => 'Business Conference Center',
        'status' => 'completed',
        'budget' => 38000.00,
        'actual_cost' => 36500.00,
        'registrations' => 220,
        'confirmed_registrations' => 210,
        'revenue' => 105000.00,
        'attendance' => 205
    ],
    [
        'event_id' => 14,
        'event_name' => 'Corporate Social Responsibility Event',
        'event_date' => '2025-07-25',
        'venue' => 'Community Center',
        'status' => 'completed',
        'budget' => 15000.00,
        'actual_cost' => 14500.00,
        'registrations' => 100,
        'confirmed_registrations' => 95,
        'revenue' => 0.00, // Charity event
        'attendance' => 90
    ],

    // August 2025
    [
        'event_id' => 15,
        'event_name' => 'Executive Board Meeting',
        'event_date' => '2025-08-08',
        'venue' => 'Boardroom Suite',
        'status' => 'completed',
        'budget' => 12000.00,
        'actual_cost' => 11500.00,
        'registrations' => 25,
        'confirmed_registrations' => 25,
        'revenue' => 0.00, // Internal meeting
        'attendance' => 24
    ],
    [
        'event_id' => 16,
        'event_name' => 'Annual Sales Kickoff',
        'event_date' => '2025-08-22',
        'venue' => 'Sales Training Center',
        'status' => 'completed',
        'budget' => 45000.00,
        'actual_cost' => 43500.00,
        'registrations' => 180,
        'confirmed_registrations' => 175,
        'revenue' => 0.00, // Internal training
        'attendance' => 170
    ],

    // September 2025
    [
        'event_id' => 17,
        'event_name' => 'Fall Technology Expo',
        'event_date' => '2025-09-10',
        'venue' => 'Tech Convention Center',
        'status' => 'completed',
        'budget' => 85000.00,
        'actual_cost' => 82000.00,
        'registrations' => 600,
        'confirmed_registrations' => 580,
        'revenue' => 290000.00,
        'attendance' => 570
    ],
    [
        'event_id' => 18,
        'event_name' => 'Professional Development Seminar',
        'event_date' => '2025-09-25',
        'venue' => 'Learning & Development Center',
        'status' => 'completed',
        'budget' => 28000.00,
        'actual_cost' => 26500.00,
        'registrations' => 120,
        'confirmed_registrations' => 115,
        'revenue' => 57500.00,
        'attendance' => 110
    ],

    // October 2025
    [
        'event_id' => 19,
        'event_name' => 'Annual Customer Conference',
        'event_date' => '2025-10-15',
        'venue' => 'International Convention Center',
        'status' => 'completed',
        'budget' => 120000.00,
        'actual_cost' => 115000.00,
        'registrations' => 800,
        'confirmed_registrations' => 780,
        'revenue' => 780000.00,
        'attendance' => 760
    ],
    [
        'event_id' => 20,
        'event_name' => 'Halloween Networking Party',
        'event_date' => '2025-10-28',
        'venue' => 'Premium Event Space',
        'status' => 'completed',
        'budget' => 25000.00,
        'actual_cost' => 23500.00,
        'registrations' => 180,
        'confirmed_registrations' => 170,
        'revenue' => 51000.00,
        'attendance' => 165
    ],

    // November 2025
    [
        'event_id' => 21,
        'event_name' => 'Industry Awards Ceremony',
        'event_date' => '2025-11-08',
        'venue' => 'Grand Theater',
        'status' => 'completed',
        'budget' => 65000.00,
        'actual_cost' => 62000.00,
        'registrations' => 350,
        'confirmed_registrations' => 340,
        'revenue' => 170000.00,
        'attendance' => 335
    ],
    [
        'event_id' => 22,
        'event_name' => 'Thanksgiving Charity Dinner',
        'event_date' => '2025-11-22',
        'venue' => 'Luxury Hotel Ballroom',
        'status' => 'completed',
        'budget' => 35000.00,
        'actual_cost' => 33500.00,
        'registrations' => 200,
        'confirmed_registrations' => 195,
        'revenue' => 97500.00,
        'attendance' => 190
    ],

    // December 2025
    [
        'event_id' => 23,
        'event_name' => 'Year-End Celebration Gala',
        'event_date' => '2025-12-05',
        'venue' => 'Five-Star Resort',
        'status' => 'completed',
        'budget' => 75000.00,
        'actual_cost' => 72000.00,
        'registrations' => 300,
        'confirmed_registrations' => 290,
        'revenue' => 145000.00,
        'attendance' => 285
    ],
    [
        'event_id' => 24,
        'event_name' => 'Strategic Planning Retreat',
        'event_date' => '2025-12-15',
        'venue' => 'Executive Conference Center',
        'status' => 'completed',
        'budget' => 32000.00,
        'actual_cost' => 30500.00,
        'registrations' => 40,
        'confirmed_registrations' => 40,
        'revenue' => 0.00, // Internal planning
        'attendance' => 38
    ]
];

// Initialize variables with 2025 dates
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] . '-01' : '2025-01-01';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] . '-31' : '2025-12-31';
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : 'profit_loss';

// Convert month inputs to proper date format
if (isset($_GET['start_date']) && strlen($_GET['start_date']) == 7) {
    $start_date = $_GET['start_date'] . '-01';
}
if (isset($_GET['end_date']) && strlen($_GET['end_date']) == 7) {
    $end_date = $_GET['end_date'] . '-31';
}

// Filter events by date range
$filtered_events = array_filter($sample_events, function($event) use ($start_date, $end_date) {
    return $event['event_date'] >= $start_date && $event['event_date'] <= $end_date;
});

// Generate monthly profit & loss data
$monthly_data = [];
$all_months = [
    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
];

foreach ($all_months as $month_num => $month_name) {
    $month_events = array_filter($filtered_events, function($event) use ($month_num) {
        return date('m', strtotime($event['event_date'])) == $month_num;
    });
    
    $month_revenue = array_sum(array_column($month_events, 'revenue'));
    $month_costs = array_sum(array_column($month_events, 'actual_cost'));
    $month_profit = $month_revenue - $month_costs;
    $profit_margin = $month_revenue > 0 ? ($month_profit / $month_revenue) * 100 : 0; // FIXED LINE
    
    $monthly_data[$month_name] = [
        'revenue' => $month_revenue,
        'costs' => $month_costs,
        'profit' => $month_profit,
        'profit_margin' => $profit_margin,
        'event_count' => count($month_events)
    ];
}

// Generate reports based on type
$report_data = [];
switch ($report_type) {
    case 'profit_loss':
        foreach ($monthly_data as $month => $data) {
            // Only include months that have data in the filtered period
            if ($data['event_count'] > 0 || ($start_date <= '2025-' . array_search($month, $all_months) . '-31' && $end_date >= '2025-' . array_search($month, $all_months) . '-01')) {
                $report_data[] = [
                    'Month' => $month . ' 2025',
                    'Total Revenue' => '$' . number_format($data['revenue'], 2),
                    'Total Costs' => '$' . number_format($data['costs'], 2),
                    'Net Profit/Loss' => '$' . number_format($data['profit'], 2),
                    'Profit Margin' => number_format($data['profit_margin'], 2) . '%',
                    'Number of Events' => $data['event_count'],
                    'Status' => $data['profit'] >= 0 ? '✅ Profit' : '❌ Loss'
                ];
            }
        }
        break;

    case 'events':
        foreach ($filtered_events as $event) {
            $profit = $event['revenue'] - $event['actual_cost'];
            $profit_margin = $event['revenue'] > 0 ? ($profit / $event['revenue']) * 100 : 0;
            
            $report_data[] = [
                'Event ID' => $event['event_id'],
                'Event Name' => $event['event_name'],
                'Event Date' => $event['event_date'],
                'Venue' => $event['venue'],
                'Status' => ucfirst($event['status']),
                'Revenue' => '$' . number_format($event['revenue'], 2),
                'Costs' => '$' . number_format($event['actual_cost'], 2),
                'Net Profit' => '$' . number_format($profit, 2),
                'Profit Margin' => number_format($profit_margin, 2) . '%',
                'Registrations' => $event['registrations']
            ];
        }
        break;

    case 'revenue':
        foreach ($filtered_events as $event) {
            $profit = $event['revenue'] - $event['actual_cost'];
            $profit_margin = $event['revenue'] > 0 ? ($profit / $event['revenue']) * 100 : 0;
            
            $report_data[] = [
                'Event Name' => $event['event_name'],
                'Event Date' => $event['event_date'],
                'Total Revenue' => '$' . number_format($event['revenue'], 2),
                'Actual Cost' => '$' . number_format($event['actual_cost'], 2),
                'Profit/Loss' => '$' . number_format($profit, 2),
                'Profit Margin' => number_format($profit_margin, 2) . '%',
                'Registrations' => $event['registrations'],
                'Revenue per Registration' => '$' . number_format($event['registrations'] > 0 ? $event['revenue'] / $event['registrations'] : 0, 2)
            ];
        }
        break;

    default:
        $report_data = [];
        break;
}

// Calculate summary statistics
$summary = [
    'total_events' => count($filtered_events),
    'total_registrations' => array_sum(array_column($filtered_events, 'registrations')),
    'total_revenue' => array_sum(array_column($filtered_events, 'revenue')),
    'total_costs' => array_sum(array_column($filtered_events, 'actual_cost'))
];

$summary['total_profit'] = $summary['total_revenue'] - $summary['total_costs'];
$summary['profit_margin'] = $summary['total_revenue'] > 0 ? ($summary['total_profit'] / $summary['total_revenue']) * 100 : 0;
$summary['avg_profit_per_event'] = $summary['total_events'] > 0 ? $summary['total_profit'] / $summary['total_events'] : 0;

// Get display dates for the form
$form_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '2025-01';
$form_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '2025-12';

// Prepare data for charts
$chart_months = [];
$chart_revenue = [];
$chart_costs = [];
$chart_profit = [];

foreach ($monthly_data as $month => $data) {
    if ($data['event_count'] > 0) {
        $chart_months[] = substr($month, 0, 3);
        $chart_revenue[] = $data['revenue'];
        $chart_costs[] = $data['costs'];
        $chart_profit[] = $data['profit'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2025 Profit & Loss Statement - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .summary-card {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .profit-positive {
            background-color: #d4edda !important;
            color: #155724;
            font-weight: bold;
        }
        .profit-negative {
            background-color: #f8d7da !important;
            color: #721c24;
            font-weight: bold;
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .report-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
            position: relative;
        }
        .current-year-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .financial-highlight {
            border-left: 4px solid #28a745;
            padding-left: 15px;
            margin: 10px 0;
        }
        .event-type-internal {
            background-color: #e3f2fd !important;
        }
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: 400px;
        }
        .chart-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
        }
        .chart-tabs {
            margin-bottom: 20px;
        }
        .chart-tab {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            background: #f8f9fa;
            cursor: pointer;
        }
        .chart-tab.active {
            background: #007bff;
            color: white;
        }
        .header-actions {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .admin-dashboard-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .admin-dashboard-btn:hover {
            background: linear-gradient(45deg, #218838, #1e9e8a);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header with Logout Button -->
        <div class="report-header text-center">
            <div class="header-actions">
                <button class="admin-dashboard-btn" onclick="goToAdminDashboard()">
                    <i class="fas fa-tachometer-alt me-2"></i>Logout
                </button>
            </div>
            <div class="current-year-badge">
                <i class="fas fa-file-invoice-dollar me-2"></i>2025 PROFIT & LOSS STATEMENT
            </div>
            <h1><i class="fas fa-chart-line me-2"></i>Event Management Financial</h1>
            <p class="lead mb-0">Professional Event Profit & Loss Analysis for 2025</p>
        </div>

        <!-- Report Filters -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Event Financial Report Period Selection</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="report_type" class="form-label">Report Type</label>
                        <select class="form-select" id="report_type" name="report_type">
                            <option value="profit_loss" <?php echo $report_type == 'profit_loss' ? 'selected' : ''; ?>>Profit & Loss Statement</option>
                            <option value="events" <?php echo $report_type == 'events' ? 'selected' : ''; ?>>Events Financial Details</option>
                            <option value="revenue" <?php echo $report_type == 'revenue' ? 'selected' : ''; ?>>Revenue Analysis</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="month" class="form-control" id="start_date" name="start_date" 
                               value="<?php echo $form_start_date; ?>" min="2025-01" max="2025-12">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="month" class="form-control" id="end_date" name="end_date" 
                               value="<?php echo $form_end_date; ?>" min="2025-01" max="2025-12">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-calculator me-2"></i>Calculate P&L
                        </button>
                    </div>
                </form>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Select any month range within 2025 to view detailed Event Management Profit & Loss statements
                    </small>
                </div>
            </div>
        </div>

        <!-- Financial Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="summary-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Event Revenue</h6>
                            <h3>$<?php echo number_format($summary['total_revenue'], 2); ?></h3>
                            <small>Selected Period</small>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card" style="background: linear-gradient(45deg, #28a745, #20c997);">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Event Profit</h6>
                            <h3>$<?php echo number_format($summary['total_profit'], 2); ?></h3>
                            <small>Net Income</small>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card" style="background: linear-gradient(45deg, #ffc107, #fd7e14);">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Event Profit Margin</h6>
                            <h3><?php echo number_format($summary['profit_margin'], 2); ?>%</h3>
                            <small>Overall Margin</small>
                        </div>
                        <i class="fas fa-percentage fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card" style="background: linear-gradient(45deg, #dc3545, #e83e8c);">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6>Total Events Managed</h6>
                            <h3><?php echo $summary['total_events']; ?></h3>
                            <small>In Period</small>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Charts Section -->
        <?php if (!empty($chart_months)): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Interactive Financial Charts
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Chart Tabs -->
                        <div class="chart-tabs">
                            <button class="chart-tab active" onclick="showChart('revenueChart')">
                                <i class="fas fa-money-bill-wave me-2"></i>Revenue vs Costs
                            </button>
                            <button class="chart-tab" onclick="showChart('profitChart')">
                                <i class="fas fa-chart-line me-2"></i>Monthly Profit/Loss
                            </button>
                            <button class="chart-tab" onclick="showChart('eventsChart')">
                                <i class="fas fa-calendar-alt me-2"></i>Events per Month
                            </button>
                        </div>

                        <!-- Revenue vs Costs Chart -->
                        <div class="chart-container" id="revenueChartContainer">
                            <div class="chart-title">Monthly Revenue vs Costs (<?php echo date('M Y', strtotime($start_date)); ?> to <?php echo date('M Y', strtotime($end_date)); ?>)</div>
                            <canvas id="revenueChart"></canvas>
                        </div>

                        <!-- Profit/Loss Chart -->
                        <div class="chart-container" id="profitChartContainer" style="display: none;">
                            <div class="chart-title">Monthly Profit/Loss (<?php echo date('M Y', strtotime($start_date)); ?> to <?php echo date('M Y', strtotime($end_date)); ?>)</div>
                            <canvas id="profitChart"></canvas>
                        </div>

                        <!-- Events per Month Chart -->
                        <div class="chart-container" id="eventsChartContainer" style="display: none;">
                            <div class="chart-title">Events per Month (<?php echo date('M Y', strtotime($start_date)); ?> to <?php echo date('M Y', strtotime($end_date)); ?>)</div>
                            <canvas id="eventsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Financial Highlights -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-star me-2"></i>Event Management Financial Highlights</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="financial-highlight">
                                    <strong>Best Performing Month:</strong><br>
                                    <?php
                                    $best_month = 'No data';
                                    $best_profit = -999999;
                                    foreach ($monthly_data as $month => $data) {
                                        if ($data['profit'] > $best_profit && $data['event_count'] > 0) {
                                            $best_profit = $data['profit'];
                                            $best_month = $month;
                                        }
                                    }
                                    echo $best_month != 'No data' ? $best_month . ' 2025 - $' . number_format($best_profit, 2) . ' profit' : 'No data available';
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="financial-highlight">
                                    <strong>Highest Revenue Month:</strong><br>
                                    <?php
                                    $highest_revenue_month = 'No data';
                                    $highest_revenue = 0;
                                    foreach ($monthly_data as $month => $data) {
                                        if ($data['revenue'] > $highest_revenue && $data['event_count'] > 0) {
                                            $highest_revenue = $data['revenue'];
                                            $highest_revenue_month = $month;
                                        }
                                    }
                                    echo $highest_revenue_month != 'No data' ? $highest_revenue_month . ' 2025 - $' . number_format($highest_revenue, 2) : 'No data available';
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="financial-highlight">
                                    <strong>Average Profit per Event:</strong><br>
                                    $<?php echo number_format($summary['avg_profit_per_event'], 2); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit & Loss Statement Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-invoice-dollar me-2"></i> 
                    <?php 
                    switch($report_type) {
                        case 'profit_loss': echo '2025 Monthly Event Profit & Loss Statement'; break;
                        case 'events': echo '2025 Event Financial Details'; break;
                        case 'revenue': echo '2025 Event Revenue Analysis Report'; break;
                        default: echo '2025 Event Financial Reports'; break;
                    }
                    ?>
                    <small class="float-end">Period: <?php echo date('M Y', strtotime($start_date)); ?> to <?php echo date('M Y', strtotime($end_date)); ?></small>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($report_data)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <?php if (!empty($report_data)): ?>
                                        <?php foreach(array_keys($report_data[0]) as $column): ?>
                                            <th><?php echo $column; ?></th>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($report_data as $row): ?>
                                    <tr class="<?php 
                                        if (isset($row['Net Profit/Loss'])) {
                                            $profit = floatval(str_replace(['$', ','], '', $row['Net Profit/Loss']));
                                            echo $profit >= 0 ? 'profit-positive' : 'profit-negative';
                                        } elseif (isset($row['Net Profit'])) {
                                            $profit = floatval(str_replace(['$', ','], '', $row['Net Profit']));
                                            // Check if it's an internal event (no revenue)
                                            $revenue = floatval(str_replace(['$', ','], '', $row['Revenue']));
                                            if ($revenue == 0) {
                                                echo 'event-type-internal';
                                            } else {
                                                echo $profit >= 0 ? 'profit-positive' : 'profit-negative';
                                            }
                                        } elseif (isset($row['Profit/Loss'])) {
                                            $profit = floatval(str_replace(['$', ','], '', $row['Profit/Loss']));
                                            echo $profit >= 0 ? 'profit-positive' : 'profit-negative';
                                        }
                                    ?>">
                                        <?php foreach($row as $cell): ?>
                                            <td><?php echo $cell; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Period Summary -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-chart-bar me-2"></i>Event Management Period Summary</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Total Event Revenue:</strong> $<?php echo number_format($summary['total_revenue'], 2); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Total Event Costs:</strong> $<?php echo number_format($summary['total_costs'], 2); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Net Event Profit:</strong> $<?php echo number_format($summary['total_profit'], 2); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Event Profit Margin:</strong> <?php echo number_format($summary['profit_margin'], 2); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <h4>No Event Financial Data Available</h4>
                        <p class="mb-0">No event financial records found for the selected period (<?php echo date('M Y', strtotime($start_date)); ?> to <?php echo date('M Y', strtotime($end_date)); ?>). Please adjust your date filters and try again.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4 mb-4 text-muted">
            <p><i class="fas fa-file-invoice-dollar me-2"></i> &copy; 2025 Professional Event Management System. Profit & Loss Statement generated on <?php echo date('F j, Y'); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Chart data from PHP
        const chartMonths = <?php echo json_encode($chart_months); ?>;
        const chartRevenue = <?php echo json_encode($chart_revenue); ?>;
        const chartCosts = <?php echo json_encode($chart_costs); ?>;
        const chartProfit = <?php echo json_encode($chart_profit); ?>;
        const chartEvents = <?php echo json_encode(array_column($monthly_data, 'event_count')); ?>;

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (chartMonths.length > 0) {
                initializeCharts();
            }
        });

        function initializeCharts() {
            // Revenue vs Costs Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: chartMonths,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: chartRevenue,
                            backgroundColor: 'rgba(40, 167, 69, 0.8)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Costs',
                            data: chartCosts,
                            backgroundColor: 'rgba(220, 53, 69, 0.8)',
                            borderColor: 'rgba(220, 53, 69, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '$' + context.parsed.y.toLocaleString();
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // Profit/Loss Chart
            const profitCtx = document.getElementById('profitChart').getContext('2d');
            new Chart(profitCtx, {
                type: 'bar',
                data: {
                    labels: chartMonths,
                    datasets: [{
                        label: 'Profit/Loss',
                        data: chartProfit,
                        backgroundColor: function(context) {
                            const value = context.parsed.y;
                            return value >= 0 ? 'rgba(40, 167, 69, 0.8)' : 'rgba(220, 53, 69, 0.8)';
                        },
                        borderColor: function(context) {
                            const value = context.parsed.y;
                            return value >= 0 ? 'rgba(40, 167, 69, 1)' : 'rgba(220, 53, 69, 1)';
                        },
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    const sign = value >= 0 ? 'Profit' : 'Loss';
                                    return `${sign}: $${Math.abs(value).toLocaleString()}`;
                                }
                            }
                        }
                    }
                }
            });

            // Events per Month Chart
            const eventsCtx = document.getElementById('eventsChart').getContext('2d');
            new Chart(eventsCtx, {
                type: 'bar',
                data: {
                    labels: chartMonths,
                    datasets: [{
                        label: 'Number of Events',
                        data: chartEvents,
                        backgroundColor: 'rgba(0, 123, 255, 0.8)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Chart tab switching
        function showChart(chartType) {
            // Hide all chart containers
            document.getElementById('revenueChartContainer').style.display = 'none';
            document.getElementById('profitChartContainer').style.display = 'none';
            document.getElementById('eventsChartContainer').style.display = 'none';
            
            // Remove active class from all tabs
            document.querySelectorAll('.chart-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected chart and activate tab
            document.getElementById(chartType + 'Container').style.display = 'block';
            event.target.classList.add('active');
        }

        // Admin Dashboard Navigation
        function goToAdminDashboard() {
            // You can change this URL to match your actual admin dashboard path
            window.location.href = 'admin_dashboard.php';
            
            // If you want a confirmation dialog, uncomment below:
            /*
            if (confirm('Are you sure you want to go back to the Admin Dashboard?')) {
                window.location.href = 'admin_dashboard.php';
            }
            */
        }

        // Add row highlighting based on profit/loss
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('profit-positive') && !this.classList.contains('profit-negative') && !this.classList.contains('event-type-internal')) {
                        this.style.backgroundColor = '#f8f9fa';
                        this.style.transform = 'scale(1.01)';
                    }
                    this.style.transition = 'all 0.2s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('profit-positive') && !this.classList.contains('profit-negative') && !this.classList.contains('event-type-internal')) {
                        this.style.backgroundColor = '';
                        this.style.transform = 'scale(1)';
                    }
                });
            });
        });
    </script>
</body>
</html>