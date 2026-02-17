<?php
// database_tools.php - Database Management Tools

class DatabaseTools {
    private $databases = [];
    
    public function __construct() {
        $this->loadDatabaseTools();
    }
    
    private function loadDatabaseTools() {
        $this->databases = [
            [
                'id' => 1,
                'name' => 'MySQL',
                'type' => 'Relational Database',
                'vendor' => 'Oracle',
                'version' => '8.0',
                'description' => 'Popular open-source relational database'
            ],
            [
                'id' => 2,
                'name' => 'MongoDB',
                'type' => 'NoSQL Database',
                'vendor' => 'MongoDB Inc.',
                'version' => '6.0',
                'description' => 'Document-based NoSQL database'
            ],
            [
                'id' => 3,
                'name' => 'PostgreSQL',
                'type' => 'Relational Database',
                'vendor' => 'PostgreSQL Group',
                'version' => '15.0',
                'description' => 'Advanced open-source relational database'
            ],
            [
                'id' => 4,
                'name' => 'SQLite',
                'type' => 'Embedded Database',
                'vendor' => 'SQLite Consortium',
                'version' => '3.40',
                'description' => 'Lightweight file-based database'
            ],
            [
                'id' => 5,
                'name' => 'Redis',
                'type' => 'In-Memory Database',
                'vendor' => 'Redis Labs',
                'version' => '7.0',
                'description' => 'High-performance key-value store'
            ],
            [
                'id' => 6,
                'name' => 'Microsoft SQL Server',
                'type' => 'Relational Database',
                'vendor' => 'Microsoft',
                'version' => '2022',
                'description' => 'Enterprise relational database system'
            ]
        ];
    }
    
    public function getAllDatabases() {
        return $this->databases;
    }
    
    public function getDatabaseById($id) {
        foreach ($this->databases as $db) {
            if ($db['id'] == $id) {
                return $db;
            }
        }
        return null;
    }
    
    public function searchDatabases($query) {
        $results = [];
        foreach ($this->databases as $db) {
            if (stripos($db['name'], $query) !== false || 
                stripos($db['type'], $query) !== false ||
                stripos($db['description'], $query) !== false) {
                $results[] = $db;
            }
        }
        return $results;
    }
}

// Create instance
$dbTools = new DatabaseTools();

// Handle search
$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search_query'])) {
    $searchResults = $dbTools->searchDatabases($_GET['search_query']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Tools Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .database-card {
            transition: transform 0.2s;
            margin-bottom: 15px;
        }
        .database-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .vendor-badge {
            font-size: 0.8em;
            padding: 3px 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">📊 Database Management Tools</h1>
        
        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Search Database Tools</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" name="search_query" class="form-control" 
                               placeholder="Search by name, type, or description..." 
                               value="<?= $_GET['search_query'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="search" class="btn btn-success w-100">
                            🔍 Search
                        </button>
                    </div>
                </form>
                <?php if (isset($_GET['search'])): ?>
                    <div class="mt-3">
                        <a href="database_tools.php" class="btn btn-outline-secondary btn-sm">
                            🗑️ Clear Search
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Database Tools List -->
        <div class="row">
            <?php 
            $databasesToShow = !empty($searchResults) ? $searchResults : $dbTools->getAllDatabases();
            
            foreach($databasesToShow as $db): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card database-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= $db['name'] ?></h5>
                        <span class="badge bg-secondary vendor-badge"><?= $db['vendor'] ?></span>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= $db['description'] ?></p>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted"><strong>Type:</strong><br><?= $db['type'] ?></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted"><strong>Version:</strong><br><?= $db['version'] ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">ID: <?= $db['id'] ?></small>
                        <button class="btn btn-sm btn-outline-primary float-end" 
                                onclick="alert('Selected: <?= $db['name'] ?>')">
                            Select
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Database Tools Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h3><?= count($dbTools->getAllDatabases()) ?></h3>
                                <p class="text-muted">Total Tools</p>
                            </div>
                            <div class="col-md-3">
                                <h3>4</h3>
                                <p class="text-muted">Relational DBs</p>
                            </div>
                            <div class="col-md-3">
                                <h3>2</h3>
                                <p class="text-muted">NoSQL DBs</p>
                            </div>
                            <div class="col-md-3">
                                <h3>6</h3>
                                <p class="text-muted">Vendors</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>