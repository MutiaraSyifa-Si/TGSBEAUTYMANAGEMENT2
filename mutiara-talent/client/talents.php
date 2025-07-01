<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit();
}

$db = require_once('../config/database.php');

// Logika pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT id, name, skills, experience FROM talents WHERE status = 'Aktif'";
if ($search) {
    $query .= " AND (name LIKE ? OR skills LIKE ? OR experience LIKE ?)";
}
$query .= " ORDER BY created_at DESC";

try {
    $stmt = $db->prepare($query);
    if ($search) {
        $searchParam = "%$search%";
        $stmt->execute([$searchParam, $searchParam, $searchParam]);
    } else {
        $stmt->execute();
    }
    $talents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error: " . $e->getMessage();
    $talents = [];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Talent - Beauty Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --pink-primary: #FF1493;
            --pink-secondary: #FF69B4;
            --pink-light: #FFB6C1;
            --white: #FFFFFF;
            --gray-100: #F8F9FA;
            --gray-200: #E9ECEF;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--pink-primary) 0%, var(--pink-secondary) 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--white);
            color: var(--pink-primary);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: var(--pink-primary);
            color: var(--white);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            background: var(--white);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            max-width: 800px;
            margin: 0 auto;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1.25rem;
            border: 2px solid var(--gray-200);
            border-radius: 25px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .search-button {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
        }

        .empty-state-icon {
            font-size: 3rem;
            color: var(--pink-secondary);
            margin-bottom: 1rem;
        }

        .empty-state-text {
            color: #666;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .back-button {
            padding: 0.6rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 0.75rem;
            }

            .search-container {
                padding: 1rem;
            }

            .search-form {
                flex-direction: column;
                gap: 0.5rem;
            }

            .search-button {
                width: 100%;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <a href="dashboard.php" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Dashboard
        </a>

        <h1 class="page-title">Cari Talent</h1>

        <div class="search-container">
            <form method="GET" action="talents.php" class="search-form">
                <input type="text" class="search-input" name="search"
                    placeholder="Cari berdasarkan nama, skills, atau pengalaman"
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">
                    <i class="bi bi-search me-2"></i>
                    Cari
                </button>
            </form>

            <?php if (empty($talents)): ?>
                <div class="empty-state">
                    <i class="bi bi-search empty-state-icon"></i>
                    <p class="empty-state-text">Coba kata kunci pencarian lain</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>