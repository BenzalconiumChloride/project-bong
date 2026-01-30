<?php
// report.php
require_once 'config.php';

// Function to calculate BMI category - FIXED TYPO HERE
function getBMICategory($bmi) {
    if ($bmi < 18.5) return "Underweight";
    if ($bmi >= 18.5 && $bmi < 25) return "Normal";  // FIXED: Changed $bi to $bmi
    if ($bmi >= 25 && $bmi < 30) return "Overweight";
    return "Obese";
}

// Function to get BMI category color
function getBMICategoryColor($bmiCategory) {
    switch($bmiCategory) {
        case 'Underweight': return '#17a2b8';
        case 'Normal': return '#28a745';
        case 'Overweight': return '#ffc107';
        case 'Obese': return '#dc3545';
        default: return '#6c757d';
    }
}

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=student_bmi_report_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fwrite($output, "\xEF\xBB\xBF");
    
    // Headers
    fputcsv($output, array('ID', 'Name', 'School', 'Grade', 'Section', 'Age', 'Gender', 'Height (cm)', 'Weight (kg)', 'BMI', 'Category', 'Health Status', 'Measurement Date', 'Created At'));
    
    // Get filter parameters
    $filter_school = $_GET['school'] ?? '';
    $filter_grade = $_GET['grade'] ?? '';
    $filter_category = $_GET['category'] ?? '';
    
    // Build query with filters
    $query = "SELECT *, 
              CASE 
                WHEN bmi_category = 'Normal' THEN 'Healthy'
                WHEN bmi_category = 'Overweight' THEN 'Needs Monitoring'
                ELSE 'Needs Attention'
              END as health_status
              FROM students WHERE 1=1";
    
    if (!empty($filter_school)) {
        $query .= " AND school = '" . mysqli_real_escape_string($conn, $filter_school) . "'";
    }
    
    if (!empty($filter_grade)) {
        $query .= " AND grade = '" . mysqli_real_escape_string($conn, $filter_grade) . "'";
    }
    
    if (!empty($filter_category)) {
        $query .= " AND bmi_category = '" . mysqli_real_escape_string($conn, $filter_category) . "'";
    }
    
    $query .= " ORDER BY school, grade, section, name";
    
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

// Get filter parameters for display
$filter_school = $_GET['school'] ?? '';
$filter_grade = $_GET['grade'] ?? '';
$filter_category = $_GET['category'] ?? '';

// Build query with filters
$query = "SELECT * FROM students WHERE 1=1";
$params = [];

if (!empty($filter_school)) {
    $query .= " AND school = ?";
    $params[] = $filter_school;
}

if (!empty($filter_grade)) {
    $query .= " AND grade = ?";
    $params[] = $filter_grade;
}

if (!empty($filter_category)) {
    $query .= " AND bmi_category = ?";
    $params[] = $filter_category;
}

$query .= " ORDER BY school, grade, section, name";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$students = [];
$summary = [
    'total' => 0,
    'categories' => [
        'Underweight' => 0,
        'Normal' => 0,
        'Overweight' => 0,
        'Obese' => 0
    ],
    'schools' => [],
    'grades' => []
];

while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
    $summary['total']++;
    
    // Count by category
    $summary['categories'][$row['bmi_category']]++;
    
    // Count by school
    if (!isset($summary['schools'][$row['school']])) {
        $summary['schools'][$row['school']] = 0;
    }
    $summary['schools'][$row['school']]++;
    
    // Count by grade
    if (!isset($summary['grades'][$row['grade']])) {
        $summary['grades'][$row['grade']] = 0;
    }
    $summary['grades'][$row['grade']]++;
}

// Get unique values for filters
$schools_query = "SELECT DISTINCT school FROM students ORDER BY school";
$schools_result = mysqli_query($conn, $schools_query);
$all_schools = [];
while ($row = mysqli_fetch_assoc($schools_result)) {
    $all_schools[] = $row['school'];
}

$grades_query = "SELECT DISTINCT grade FROM students ORDER BY grade";
$grades_result = mysqli_query($conn, $grades_query);
$all_grades = [];
while ($row = mysqli_fetch_assoc($grades_result)) {
    $all_grades[] = $row['grade'];
}

// Get detailed analytics for each school
$schoolAnalytics = [];
foreach ($all_schools as $school) {
    $analyticsQuery = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN bmi_category = 'Underweight' THEN 1 ELSE 0 END) as underweight,
        SUM(CASE WHEN bmi_category = 'Normal' THEN 1 ELSE 0 END) as normal,
        SUM(CASE WHEN bmi_category = 'Overweight' THEN 1 ELSE 0 END) as overweight,
        SUM(CASE WHEN bmi_category = 'Obese' THEN 1 ELSE 0 END) as obese
        FROM students WHERE school = ?";
    
    $stmt = mysqli_prepare($conn, $analyticsQuery);
    mysqli_stmt_bind_param($stmt, 's', $school);
    mysqli_stmt_execute($stmt);
    $analyticsResult = mysqli_stmt_get_result($stmt);
    $schoolAnalytics[$school] = mysqli_fetch_assoc($analyticsResult);
}

// Get detailed analytics for each BMI category
$categoryAnalytics = [
    'Underweight' => ['min_age' => 100, 'max_age' => 0, 'avg_age' => 0, 'count' => 0],
    'Normal' => ['min_age' => 100, 'max_age' => 0, 'avg_age' => 0, 'count' => 0],
    'Overweight' => ['min_age' => 100, 'max_age' => 0, 'avg_age' => 0, 'count' => 0],
    'Obese' => ['min_age' => 100, 'max_age' => 0, 'avg_age' => 0, 'count' => 0]
];

$categoryQuery = "SELECT bmi_category, 
    MIN(age) as min_age, 
    MAX(age) as max_age, 
    AVG(age) as avg_age,
    COUNT(*) as count
    FROM students 
    GROUP BY bmi_category";
$categoryResult = mysqli_query($conn, $categoryQuery);

while ($row = mysqli_fetch_assoc($categoryResult)) {
    $category = $row['bmi_category'];
    $categoryAnalytics[$category] = [
        'min_age' => $row['min_age'],
        'max_age' => $row['max_age'],
        'avg_age' => round($row['avg_age'], 1),
        'count' => $row['count']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student BMI Health Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .report-header {
            background: linear-gradient(135deg, #1e5799 0%, #207cca 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .report-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }
        
        .report-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .report-header .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .report-meta {
            display: flex;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .meta-item {
            text-align: center;
            flex: 1;
        }
        
        .meta-item .label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }
        
        .meta-item .value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        /* Filter Section */
        .filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
        }
        
        .filter-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background-color: white;
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
        }
        
        /* Summary Cards */
        .summary-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
        }
        
        .summary-card.total {
            border-top: 4px solid #1e5799;
        }
        
        .summary-card.underweight {
            border-top: 4px solid #17a2b8;
        }
        
        .summary-card.normal {
            border-top: 4px solid #28a745;
        }
        
        .summary-card.overweight {
            border-top: 4px solid #ffc107;
        }
        
        .summary-card.obese {
            border-top: 4px solid #dc3545;
        }
        
        .summary-card .value {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .summary-card .label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Analytics Sections */
        .analytics-section {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        
        .analytics-section h3 {
            color: #1e5799;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .school-analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .school-analytic-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1e5799;
        }
        
        .school-analytic-card h4 {
            color: #1e5799;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .category-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        
        .category-stat {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .category-stat.underweight {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }
        
        .category-stat.normal {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .category-stat.overweight {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .category-stat.obese {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .category-stat .count {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        /* BMI Category Analytics */
        .category-analytics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .category-analytic-card {
            padding: 20px;
            border-radius: 8px;
            color: white;
        }
        
        .category-analytic-card.underweight {
            background-color: #17a2b8;
        }
        
        .category-analytic-card.normal {
            background-color: #28a745;
        }
        
        .category-analytic-card.overweight {
            background-color: #ffc107;
        }
        
        .category-analytic-card.obese {
            background-color: #dc3545;
        }
        
        .category-analytic-card h4 {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .age-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        
        .age-stat {
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }
        
        .age-stat .value {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .age-stat .label {
            font-size: 0.8rem;
            opacity: 0.9;
        }
        
        /* Report Table */
        .report-table-container {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .report-table thead {
            background: linear-gradient(135deg, #1e5799 0%, #207cca 100%);
            color: white;
        }
        
        .report-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border: none;
        }
        
        .report-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }
        
        .report-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .report-table td {
            padding: 12px 15px;
            border: none;
        }
        
        .bmi-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            text-align: center;
            min-width: 100px;
        }
        
        /* Charts */
        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .chart-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        
        .chart-title {
            font-size: 1.2rem;
            color: #1e5799;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #1e5799;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #16437e;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #138496;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .export-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 4px solid #28a745;
        }
        
        .export-section h3 {
            color: #28a745;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .export-info {
            font-size: 0.9rem;
            color: #666;
            margin-top: 10px;
            padding: 10px;
            background-color: #e9f7ef;
            border-radius: 5px;
        }
        
        /* Footer */
        .report-footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .report-header h1 {
                font-size: 2rem;
            }
            
            .report-meta {
                flex-direction: column;
                gap: 15px;
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
            
            .summary-section {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .school-analytics-grid {
                grid-template-columns: 1fr;
            }
            
            .category-analytics {
                grid-template-columns: 1fr;
            }
            
            .category-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .report-table {
                display: block;
                overflow-x: auto;
            }
            
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Report Header -->
        <div class="report-header">
            <h1>
                <i class="fas fa-file-medical-alt"></i>
                Student BMI Health Report
            </h1>
            <div class="subtitle">
                Comprehensive analysis of student health metrics and BMI categories
            </div>
            
            <div class="report-meta">
                <div class="meta-item">
                    <div class="label">Report Date</div>
                    <div class="value"><?php echo date('F d, Y'); ?></div>
                </div>
                <div class="meta-item">
                    <div class="label">Total Students</div>
                    <div class="value"><?php echo $summary['total']; ?></div>
                </div>
                <div class="meta-item">
                    <div class="label">Schools</div>
                    <div class="value"><?php echo count($summary['schools']); ?></div>
                </div>
                <div class="meta-item">
                    <div class="label">Records</div>
                    <div class="value"><?php echo $summary['total']; ?></div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="actions">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Tracker
            </a>
            <?php if ($summary['total'] > 0): ?>
                <a href="report.php?export=csv<?php 
                    if (!empty($filter_school)) echo '&school=' . urlencode($filter_school);
                    if (!empty($filter_grade)) echo '&grade=' . urlencode($filter_grade);
                    if (!empty($filter_category)) echo '&category=' . urlencode($filter_category);
                ?>" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Export Data (CSV)
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Export Information Section -->
        <div class="export-section">
            <h3><i class="fas fa-download"></i> Data Export</h3>
            <p>Export the current report data to CSV format. The exported file will include:</p>
            <div class="export-info">
                <p><i class="fas fa-check-circle"></i> All student information (Name, School, Grade, Section)</p>
                <p><i class="fas fa-check-circle"></i> Health metrics (Height, Weight, BMI, Category)</p>
                <p><i class="fas fa-check-circle"></i> Health status classification</p>
                <p><i class="fas fa-check-circle"></i> Measurement dates and timestamps</p>
                <p><i class="fas fa-check-circle"></i> Current filter settings (if applied)</p>
            </div>
            <?php if ($summary['total'] > 0): ?>
                <div style="margin-top: 15px;">
                    <a href="report.php?export=csv<?php 
                        if (!empty($filter_school)) echo '&school=' . urlencode($filter_school);
                        if (!empty($filter_grade)) echo '&grade=' . urlencode($filter_grade);
                        if (!empty($filter_category)) echo '&category=' . urlencode($filter_category);
                    ?>" class="btn btn-success" style="font-size: 1.1rem; padding: 12px 25px;">
                        <i class="fas fa-file-csv"></i> Download CSV File
                    </a>
                    <p style="margin-top: 10px; color: #666; font-size: 0.9rem;">
                        File will be named: <code>student_bmi_report_<?php echo date('Y-m-d'); ?>.csv</code>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <h3 style="margin-bottom: 15px; color: #1e5799;">
                <i class="fas fa-filter"></i> Filter Report Data
            </h3>
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="school">Filter by School:</label>
                    <select id="school" name="school" onchange="this.form.submit()">
                        <option value="">All Schools</option>
                        <?php foreach($all_schools as $school): ?>
                            <option value="<?php echo htmlspecialchars($school); ?>" 
                                <?php echo $filter_school == $school ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($school); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="grade">Filter by Grade:</label>
                    <select id="grade" name="grade" onchange="this.form.submit()">
                        <option value="">All Grades</option>
                        <?php foreach($all_grades as $grade): ?>
                            <option value="<?php echo htmlspecialchars($grade); ?>"
                                <?php echo $filter_grade == $grade ? 'selected' : ''; ?>>
                                Grade <?php echo htmlspecialchars($grade); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="category">Filter by BMI Category:</label>
                    <select id="category" name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <option value="Underweight" <?php echo $filter_category == 'Underweight' ? 'selected' : ''; ?>>Underweight</option>
                        <option value="Normal" <?php echo $filter_category == 'Normal' ? 'selected' : ''; ?>>Normal</option>
                        <option value="Overweight" <?php echo $filter_category == 'Overweight' ? 'selected' : ''; ?>>Overweight</option>
                        <option value="Obese" <?php echo $filter_category == 'Obese' ? 'selected' : ''; ?>>Obese</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <a href="report.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-card total">
                <div class="value"><?php echo $summary['total']; ?></div>
                <div class="label">Total Students</div>
            </div>
            
            <div class="summary-card underweight">
                <div class="value"><?php echo $summary['categories']['Underweight']; ?></div>
                <div class="label">Underweight</div>
            </div>
            
            <div class="summary-card normal">
                <div class="value"><?php echo $summary['categories']['Normal']; ?></div>
                <div class="label">Normal Weight</div>
            </div>
            
            <div class="summary-card overweight">
                <div class="value"><?php echo $summary['categories']['Overweight']; ?></div>
                <div class="label">Overweight</div>
            </div>
            
            <div class="summary-card obese">
                <div class="value"><?php echo $summary['categories']['Obese']; ?></div>
                <div class="label">Obese</div>
            </div>
        </div>
        
        <!-- School Analytics Section -->
        <?php if (!empty($schoolAnalytics)): ?>
        <div class="analytics-section">
            <h3><i class="fas fa-chart-line"></i> School-wise Analytics</h3>
            <p style="color: #666; margin-bottom: 20px;">Detailed breakdown of BMI categories across different schools:</p>
            
            <div class="school-analytics-grid">
                <?php foreach ($schoolAnalytics as $school => $data): ?>
                    <div class="school-analytic-card">
                        <h4><i class="fas fa-university"></i> <?php echo htmlspecialchars($school); ?></h4>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Total Students:</span>
                                <span style="font-weight: bold;"><?php echo $data['total']; ?></span>
                            </div>
                        </div>
                        
                        <div class="category-stats">
                            <div class="category-stat underweight">
                                <div class="count"><?php echo $data['underweight']; ?></div>
                                <div class="label">Underweight</div>
                            </div>
                            <div class="category-stat normal">
                                <div class="count"><?php echo $data['normal']; ?></div>
                                <div class="label">Normal</div>
                            </div>
                            <div class="category-stat overweight">
                                <div class="count"><?php echo $data['overweight']; ?></div>
                                <div class="label">Overweight</div>
                            </div>
                            <div class="category-stat obese">
                                <div class="count"><?php echo $data['obese']; ?></div>
                                <div class="label">Obese</div>
                            </div>
                        </div>
                        
                        <?php if ($data['total'] > 0): ?>
                            <?php 
                            $normalPercentage = round(($data['normal'] / $data['total']) * 100);
                            $atRiskPercentage = round((($data['underweight'] + $data['obese']) / $data['total']) * 100);
                            ?>
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                    <span>Healthy Students:</span>
                                    <span style="font-weight: bold; color: #28a745;"><?php echo $normalPercentage; ?>%</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-top: 5px;">
                                    <span>At Risk Students:</span>
                                    <span style="font-weight: bold; color: #dc3545;"><?php echo $atRiskPercentage; ?>%</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- BMI Category Analytics -->
        <div class="analytics-section">
            <h3><i class="fas fa-chart-bar"></i> BMI Category Analytics</h3>
            <p style="color: #666; margin-bottom: 20px;">Age distribution and statistics for each BMI category:</p>
            
            <div class="category-analytics">
                <?php foreach ($categoryAnalytics as $category => $data): ?>
                    <?php if ($data['count'] > 0): ?>
                    <div class="category-analytic-card <?php echo strtolower($category); ?>">
                        <h4><i class="fas fa-weight"></i> <?php echo $category; ?> Students</h4>
                        <div style="text-align: center; margin-bottom: 15px;">
                            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 5px;">
                                <?php echo $data['count']; ?>
                            </div>
                            <div style="font-size: 0.9rem; opacity: 0.9;">
                                <?php echo round(($data['count'] / $summary['total']) * 100); ?>% of total
                            </div>
                        </div>
                        
                        <div class="age-stats">
                            <div class="age-stat">
                                <div class="value"><?php echo $data['min_age']; ?></div>
                                <div class="label">Min Age</div>
                            </div>
                            <div class="age-stat">
                                <div class="value"><?php echo $data['max_age']; ?></div>
                                <div class="label">Max Age</div>
                            </div>
                            <div class="age-stat">
                                <div class="value"><?php echo $data['avg_age']; ?></div>
                                <div class="label">Avg Age</div>
                            </div>
                        </div>
                        
                        <?php if ($category == 'Underweight'): ?>
                            <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid rgba(255, 255, 255, 0.3); font-size: 0.85rem;">
                                <i class="fas fa-info-circle"></i> These students may need nutritional support
                            </div>
                        <?php elseif ($category == 'Normal'): ?>
                            <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid rgba(255, 255, 255, 0.3); font-size: 0.85rem;">
                                <i class="fas fa-check-circle"></i> Students are within healthy weight range
                            </div>
                        <?php elseif ($category == 'Overweight'): ?>
                            <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid rgba(255, 255, 255, 0.3); font-size: 0.85rem;">
                                <i class="fas fa-exclamation-triangle"></i> Monitor diet and exercise habits
                            </div>
                        <?php elseif ($category == 'Obese'): ?>
                            <div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid rgba(255, 255, 255, 0.3); font-size: 0.85rem;">
                                <i class="fas fa-heartbeat"></i> Health intervention recommended
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i> BMI Category Distribution
                </div>
                <div style="height: 250px; display: flex; align-items: center; justify-content: center;">
                    <div style="width: 200px; height: 200px; position: relative;">
                        <!-- Simple pie chart using CSS -->
                        <div style="position: absolute; width: 100%; height: 100%;">
                            <?php
                            $total = $summary['total'];
                            if ($total > 0) {
                                $angles = [];
                                $start = 0;
                                
                                $categories = [
                                    'Underweight' => '#17a2b8',
                                    'Normal' => '#28a745',
                                    'Overweight' => '#ffc107',
                                    'Obese' => '#dc3545'
                                ];
                                
                                foreach ($categories as $cat => $color) {
                                    $count = $summary['categories'][$cat];
                                    if ($count > 0) {
                                        $percentage = ($count / $total) * 100;
                                        $angle = ($percentage / 100) * 360;
                                        echo '<div style="position: absolute; width: 100%; height: 100%; border-radius: 50%; border: 20px solid ' . $color . '; clip-path: polygon(50% 50%, 50% 0%, ' . 
                                             (50 + 50 * sin(deg2rad($start))) . '% ' . (50 - 50 * cos(deg2rad($start))) . '%, ' .
                                             (50 + 50 * sin(deg2rad($start + $angle))) . '% ' . (50 - 50 * cos(deg2rad($start + $angle))) . '%);"></div>';
                                        $start += $angle;
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div style="position: absolute; width: 60%; height: 60%; background: white; border-radius: 50%; top: 20%; left: 20%;"></div>
                    </div>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 20px;">
                    <?php foreach(['Underweight', 'Normal', 'Overweight', 'Obese'] as $cat): ?>
                        <?php if($summary['categories'][$cat] > 0): ?>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div style="width: 15px; height: 15px; background-color: <?php echo getBMICategoryColor($cat); ?>; border-radius: 3px;"></div>
                                <span><?php echo $cat . ': ' . $summary['categories'][$cat] . ' (' . round(($summary['categories'][$cat]/$total)*100) . '%)'; ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-school"></i> School Distribution
                </div>
                <div style="height: 250px; padding: 10px;">
                    <?php if(!empty($summary['schools'])): ?>
                        <?php foreach($summary['schools'] as $school => $count): ?>
                            <?php $percentage = ($count / $total) * 100; ?>
                            <div style="margin-bottom: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($school); ?></span>
                                    <span style="font-weight: bold;"><?php echo $count; ?></span>
                                </div>
                                <div style="height: 10px; background-color: #e9ecef; border-radius: 5px; overflow: hidden;">
                                    <div style="height: 100%; width: <?php echo $percentage; ?>%; background-color: #1e5799; border-radius: 5px;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; margin-top: 50px;">No school data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Detailed Report Table -->
        <div class="report-table-container">
            <div style="padding: 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="color: #1e5799; margin: 0;">
                    <i class="fas fa-list-alt"></i> Detailed Student Report
                </h3>
                <?php if ($summary['total'] > 0): ?>
                    <span style="background-color: #e9f7fe; padding: 5px 15px; border-radius: 20px; font-weight: bold; color: #1e5799;">
                        <?php echo $summary['total']; ?> records
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if(empty($students)): ?>
                <div style="text-align: center; padding: 50px;">
                    <i class="fas fa-clipboard-list" style="font-size: 3rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h3 style="color: #666;">No student records found</h3>
                    <p style="color: #999;">Try adjusting your filters or add students in the tracker.</p>
                    <a href="index.php" class="btn btn-primary" style="margin-top: 20px;">
                        <i class="fas fa-plus"></i> Add Students
                    </a>
                </div>
            <?php else: ?>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Information</th>
                            <th>School Details</th>
                            <th>Measurements</th>
                            <th>BMI Analysis</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        $current_school = '';
                        foreach($students as $student): 
                            $bmiColor = getBMICategoryColor($student['bmi_category']);
                            
                            if($student['school'] != $current_school):
                                $current_school = $student['school'];
                        ?>
                        <tr style="background-color: #f8f9fa;">
                            <td colspan="6" style="padding: 15px 20px;">
                                <h4 style="margin: 0; color: #1e5799;">
                                    <i class="fas fa-school"></i> <?php echo htmlspecialchars($current_school); ?>
                                    <small style="font-weight: normal; color: #666; margin-left: 10px;">
                                        (<?php echo $summary['schools'][$current_school]; ?> students)
                                    </small>
                                </h4>
                            </td>
                        </tr>
                        <?php endif; ?>
                        
                        <tr>
                            <td style="font-weight: bold; color: #666;"><?php echo $counter++; ?></td>
                            
                            <td>
                                <div style="font-weight: bold; font-size: 1.1rem;">
                                    <?php echo htmlspecialchars($student['name']); ?>
                                </div>
                                <div style="color: #666; font-size: 0.9rem;">
                                    <i class="fas fa-user"></i> <?php echo ucfirst($student['gender']); ?> 
                                    | <i class="fas fa-birthday-cake"></i> <?php echo $student['age']; ?> years
                                </div>
                            </td>
                            
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="background-color: #e9f7fe; padding: 5px 10px; border-radius: 20px; font-weight: bold;">
                                        G<?php echo $student['grade']; ?>-<?php echo $student['section']; ?>
                                    </div>
                                </div>
                                <div style="color: #666; font-size: 0.9rem; margin-top: 5px;">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($student['school']); ?>
                                </div>
                            </td>
                            
                            <td>
                                <?php if($student['height'] && $student['weight']): ?>
                                    <div style="font-weight: bold;">
                                        <span style="color: #1e5799;"><?php echo $student['height']; ?> cm</span>
                                        <span style="margin: 0 10px;">/</span>
                                        <span style="color: #1e5799;"><?php echo $student['weight']; ?> kg</span>
                                    </div>
                                    <div style="color: #666; font-size: 0.9rem;">
                                        Height & Weight Recorded
                                    </div>
                                <?php else: ?>
                                    <div style="color: #999; font-style: italic;">
                                        Direct BMI Entry
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="font-size: 1.2rem; font-weight: bold; color: <?php echo $bmiColor; ?>;">
                                        <?php echo $student['bmi']; ?>
                                    </div>
                                    <span class="bmi-badge" style="background-color: <?php echo $bmiColor; ?>;">
                                        <?php echo $student['bmi_category']; ?>
                                    </span>
                                </div>
                                <div style="color: #666; font-size: 0.85rem; margin-top: 5px;">
                                    <?php 
                                    $healthStatus = '';
                                    switch($student['bmi_category']) {
                                        case 'Underweight': $healthStatus = 'Needs nutritional support'; break;
                                        case 'Normal': $healthStatus = 'Healthy weight range'; break;
                                        case 'Overweight': $healthStatus = 'Monitor diet & exercise'; break;
                                        case 'Obese': $healthStatus = 'Requires health intervention'; break;
                                    }
                                    echo $healthStatus;
                                    ?>
                                </div>
                            </td>
                            
                            <td>
                                <div style="font-weight: bold; color: #333;">
                                    <?php echo date('M d, Y', strtotime($student['measurement_date'])); ?>
                                </div>
                                <div style="color: #666; font-size: 0.85rem;">
                                    Recorded: <?php echo date('M d', strtotime($student['created_at'])); ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Health Recommendations -->
        <div class="chart-container">
            <div class="chart-title">
                <i class="fas fa-stethoscope"></i> Health Recommendations Summary
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <div style="background-color: #e9f7fe; padding: 15px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                    <h4 style="color: #17a2b8; margin-bottom: 10px;">
                        <i class="fas fa-utensils"></i> Underweight Students (<?php echo $summary['categories']['Underweight']; ?>)
                    </h4>
                    <ul style="color: #666; padding-left: 20px; font-size: 0.9rem;">
                        <li>Increase calorie intake with healthy foods</li>
                        <li>Regular health check-ups</li>
                        <li>Nutritional counseling recommended</li>
                    </ul>
                </div>
                
                <div style="background-color: #e9f7ef; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <h4 style="color: #28a745; margin-bottom: 10px;">
                        <i class="fas fa-heart"></i> Normal Weight Students (<?php echo $summary['categories']['Normal']; ?>)
                    </h4>
                    <ul style="color: #666; padding-left: 20px; font-size: 0.9rem;">
                        <li>Maintain healthy diet and exercise</li>
                        <li>Annual BMI monitoring recommended</li>
                        <li>Continue healthy lifestyle habits</li>
                    </ul>
                </div>
                
                <div style="background-color: #fff9e6; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <h4 style="color: #ffc107; margin-bottom: 10px;">
                        <i class="fas fa-running"></i> Overweight Students (<?php echo $summary['categories']['Overweight']; ?>)
                    </h4>
                    <ul style="color: #666; padding-left: 20px; font-size: 0.9rem;">
                        <li>Increase physical activity</li>
                        <li>Monitor portion sizes</li>
                        <li>Health education sessions recommended</li>
                    </ul>
                </div>
                
                <div style="background-color: #ffeaea; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;">
                    <h4 style="color: #dc3545; margin-bottom: 10px;">
                        <i class="fas fa-heartbeat"></i> Obese Students (<?php echo $summary['categories']['Obese']; ?>)
                    </h4>
                    <ul style="color: #666; padding-left: 20px; font-size: 0.9rem;">
                        <li>Medical consultation required</li>
                        <li>Structured weight management program</li>
                        <li>Regular monitoring and support needed</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Report Footer -->
        <div class="report-footer">
            <p>
                <strong>Student BMI Health Report</strong> | 
                Generated on <?php echo date('F d, Y \a\t h:i A'); ?> | 
                Total Records: <?php echo $summary['total']; ?>
                <?php if (!empty($filter_school) || !empty($filter_grade) || !empty($filter_category)): ?>
                    | Filtered: 
                    <?php 
                    $filters = [];
                    if (!empty($filter_school)) $filters[] = "School: " . htmlspecialchars($filter_school);
                    if (!empty($filter_grade)) $filters[] = "Grade: " . htmlspecialchars($filter_grade);
                    if (!empty($filter_category)) $filters[] = "Category: " . htmlspecialchars($filter_category);
                    echo implode(', ', $filters);
                    ?>
                <?php endif; ?>
            </p>
            <p style="margin-top: 10px;">
                <i class="fas fa-exclamation-circle"></i> 
                This report is for educational and monitoring purposes. 
                For medical diagnosis and treatment, consult with healthcare professionals.
            </p>
        </div>
    </div>

    <script>
        // Add row highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.report-table tbody tr:not([style*="background-color"])');
            rows.forEach((row, index) => {
                if (index % 2 === 0) {
                    row.style.backgroundColor = '#fcfcfc';
                }
            });
        });
    </script>
</body>
</html>