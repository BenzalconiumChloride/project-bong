<?php
// index.php
require_once '../global-library/database.php';

// Function to calculate BMI category - FIXED TYPO HERE
function getBMICategory($bmi) {
    if ($bmi < 18.5) return "Underweight";
    if ($bmi >= 18.5 && $bmi < 25) return "Normal";  // FIXED: Changed $bi to $bmi
    if ($bmi >= 25 && $bmi < 30) return "Overweight";
    return "Obese";
}

// Function to get BMI category class for styling
function getBMICategoryClass($bmi) {
    if ($bmi < 18.5) return "bmi-underweight";
    if ($bmi >= 18.5 && $bmi < 25) return "bmi-normal";
    if ($bmi >= 25 && $bmi < 30) return "bmi-overweight";
    return "bmi-obese";
}

// Function to determine if student needs health assistance
function needsHealthAssistance($bmiCategory) {
    return $bmiCategory === "Underweight" || $bmiCategory === "Obese";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['studentName'];
    $school = $_POST['schoolName'];
    $grade = $_POST['studentGrade'];
    $section = $_POST['studentSection'];
    $age = intval($_POST['studentAge']);
    $gender = $_POST['studentGender'];
    $date = $_POST['measurementDate'];

    // Determine input method
    $inputMethod = $_POST['inputMethod'] ?? 'bmi';
    $bmi = 0;
    $height = null;
    $weight = null;

    if ($inputMethod == 'bmi') {
        // Direct BMI input
        $bmi = floatval($_POST['studentBMI']);
    } else {
        // Height/weight input
        $height = floatval($_POST['studentHeight']);
        $weight = floatval($_POST['studentWeight']);

        // Calculate BMI if height and weight are provided
        if ($height > 0 && $weight > 0) {
            $heightInMeters = $height / 100;
            $bmi = $weight / ($heightInMeters * $heightInMeters);
        }
    }

    // Get BMI category - USING CORRECTED FUNCTION
    $bmiCategory = getBMICategory($bmi);

    // Debug: Check what's being calculated
    error_log("BMI Calculation Debug: BMI=$bmi, Category=$bmiCategory, Height=$height, Weight=$weight");

    // Validate BMI value
    if ($bmi < 10 || $bmi > 50) {
        $errorMessage = "Please enter a valid BMI value (between 10 and 50)";
    } else {
        // Insert into database using PDO
        $query = "INSERT INTO students (name, school, grade, section, age, gender, height, weight, bmi, bmi_category, measurement_date)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $conn->prepare($query);
            $stmt->execute([$name, $school, $grade, $section, $age, $gender, $height, $weight, $bmi, $bmiCategory, $date]);
            $successMessage = "Student record added successfully!";
        } catch (PDOException $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}


// Handle data export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=student_bmi_data_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    // Add UTF-8 BOM for Excel compatibility
    fwrite($output, "\xEF\xBB\xBF");

    // Headers
    fputcsv($output, array('ID', 'Name', 'School', 'Grade', 'Section', 'Age', 'Gender', 'Height (cm)', 'Weight (kg)', 'BMI', 'Category', 'Measurement Date', 'Created At'));

    $query = "SELECT * FROM students ORDER BY created_at DESC";
    $stmt = $conn->query($query);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Handle data deletion
if (isset($_GET['delete_all']) && $_GET['delete_all'] == 'true') {
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
        $conn->exec("DELETE FROM students");
        $successMessage = "All data has been cleared!";
    }
}

// Fetch all students for display
$query = "SELECT * FROM students ORDER BY created_at DESC";
$stmt = $conn->query($query);
$students = [];
$totalStudents = 0;
$normalCount = 0;
$overweightCount = 0;
$atRiskCount = 0;
$atRiskStudents = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $students[] = $row;
    $totalStudents++;

    if ($row['bmi_category'] == 'Normal') $normalCount++;
    if ($row['bmi_category'] == 'Overweight') $overweightCount++;
    if (needsHealthAssistance($row['bmi_category'])) {
        $atRiskCount++;
        $atRiskStudents[] = $row;
    }
}

// Get recent students (last 5)
$recentStudents = array_slice($students, 0, 5);

// Get school distribution for quick stats
$schoolsQuery = "SELECT school, COUNT(*) as count FROM students GROUP BY school ORDER BY count DESC";
$schoolsResult = $conn->query($schoolsQuery);
$schoolStats = [];
while ($row = $schoolsResult->fetch(PDO::FETCH_ASSOC)) {
    $schoolStats[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student BMI Tracker & Health Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f9fc;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 30px;
            background: linear-gradient(135deg, #1e5799 0%, #207cca 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        header::before {
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
        
        h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto 20px;
        }
        
        .header-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        
        .stat-badge {
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 25px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(5px);
        }
        
        .stat-badge i {
            font-size: 1.2rem;
        }
        
        .app-description {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #1e5799;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .input-section, .analytics-section {
            flex: 1;
            min-width: 350px;
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .section-title {
            font-size: 1.6rem;
            color: #1e5799;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaeaea;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #444;
            font-size: 1.05rem;
        }
        
        input, select {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #fcfcfc;
        }
        
        input:focus, select:focus {
            border-color: #1e5799;
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 87, 153, 0.1);
            background-color: white;
        }
        
        .input-option {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        
        .input-option:hover {
            background-color: #f0f7ff;
        }
        
        .input-option input {
            width: auto;
            transform: scale(1.2);
        }
        
        .input-option label {
            margin-bottom: 0;
            cursor: pointer;
            font-size: 1.05rem;
        }
        
        .input-group {
            display: flex;
            gap: 15px;
        }
        
        .input-group .form-group {
            flex: 1;
        }
        
        .calculated-bmi {
            background-color: #f0f7ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #1e5799;
        }
        
        .calculated-bmi p {
            margin: 8px 0;
            font-size: 1.05rem;
        }
        
        .bmi-display {
            font-weight: bold;
            font-size: 1.4rem;
            color: #1e5799;
        }
        
        .btn {
            background-color: #1e5799;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.05rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn:hover {
            background-color: #16437e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 87, 153, 0.2);
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        
        .btn-info {
            background-color: #17a2b8;
        }
        
        .btn-info:hover {
            background-color: #138496;
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.2);
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }
        
        .students-list {
            margin-top: 35px;
        }
        
        .student-card {
            background-color: #f8f9fa;
            border-left: 4px solid #1e5799;
            padding: 20px;
            margin-bottom: 18px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .student-card.at-risk {
            border-left-color: #dc3545;
            background-color: #ffeaea;
        }
        
        .student-card.moderate-risk {
            border-left-color: #ffc107;
            background-color: #fff9e6;
        }
        
        .student-info h4 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #333;
        }
        
        .student-info p {
            color: #666;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .bmi-value {
            font-weight: bold;
            font-size: 1.3rem;
        }
        
        .bmi-underweight {
            color: #17a2b8;
        }
        
        .bmi-normal {
            color: #28a745;
        }
        
        .bmi-overweight {
            color: #ffc107;
        }
        
        .bmi-obese {
            color: #dc3545;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            border-top: 4px solid #1e5799;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.at-risk {
            border-top-color: #dc3545;
        }
        
        .stat-card.moderate-risk {
            border-top-color: #ffc107;
        }
        
        .stat-card.info {
            border-top-color: #17a2b8;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .school-stats {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #17a2b8;
        }
        
        .school-stats h4 {
            color: #17a2b8;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .school-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .school-badge {
            background-color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .school-count {
            background-color: #1e5799;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 0.95rem;
            line-height: 1.8;
        }
        
        .bmi-categories {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .bmi-category {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .category-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        
        .underweight-color {
            background-color: #17a2b8;
        }
        
        .normal-color {
            background-color: #28a745;
        }
        
        .overweight-color {
            background-color: #ffc107;
        }
        
        .obese-color {
            background-color: #dc3545;
        }
        
        .hidden {
            display: none !important;
        }
        
        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .delete-confirm {
            background-color: #fff3f3;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ffcccc;
            margin-top: 20px;
        }
        
        .quick-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            h1 {
                font-size: 2.2rem;
            }
            
            .input-group {
                flex-direction: column;
                gap: 0;
            }
            
            .bmi-categories {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-stats {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
            
            .quick-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>
                <i class="fas fa-heartbeat"></i>
                Student BMI Tracker System
            </h1>
            <p class="subtitle">Comprehensive health monitoring platform for schools to track, analyze, and identify students needing health assistance</p>
            
            <div class="header-stats">
                <div class="stat-badge">
                    <i class="fas fa-users"></i>
                    <span>Total Students: <?php echo $totalStudents; ?></span>
                </div>
                <div class="stat-badge">
                    <i class="fas fa-school"></i>
                    <span>Schools: <?php echo count($schoolStats); ?></span>
                </div>
                <div class="stat-badge">
                    <i class="fas fa-heart"></i>
                    <span>Healthy: <?php echo $normalCount; ?></span>
                </div>
            </div>
        </header>
        
        <?php if (isset($successMessage)): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        
        <div class="quick-actions">
            <a href="report.php" class="btn btn-info">
                <i class="fas fa-file-medical-alt"></i> View Full Health Report
            </a>
            <?php if ($totalStudents > 0): ?>
                <a href="?export=csv" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Export Data (CSV)
                </a>
            <?php endif; ?>
        </div>
        
        <div class="app-description">
            <p>This comprehensive system allows school health professionals to record and track students' BMI (Body Mass Index) values. You can either input BMI directly or provide height and weight for automatic calculation. The analytics section identifies students who may need health assistance based on established BMI categories for children and adolescents.</p>
        </div>
        
        <div class="main-content">
            <section class="input-section">
                <h2 class="section-title"><i class="fas fa-user-plus"></i> Add Student BMI Record</h2>
                <form id="bmiForm" method="POST" action="">
                    <div class="form-group">
                        <label for="studentName"><i class="fas fa-user"></i> Student Name</label>
                        <input type="text" id="studentName" name="studentName" placeholder="Enter student's full name" required>
                    </div>
                    
                    <div class="input-group">
                        <div class="form-group">
                            <label for="schoolName"><i class="fas fa-school"></i> School</label>
                            <input type="text" id="schoolName" name="schoolName" placeholder="Enter school name" required>
                        </div>
                        <div class="form-group">
                            <label for="studentGrade"><i class="fas fa-graduation-cap"></i> Grade</label>
                            <select id="studentGrade" name="studentGrade" required>
                                <option value="">Select grade</option>
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>">Grade <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="studentSection"><i class="fas fa-chalkboard"></i> Section</label>
                            <input type="text" id="studentSection" name="studentSection" placeholder="e.g., A, B, C" required>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <div class="form-group">
                            <label for="studentAge"><i class="fas fa-birthday-cake"></i> Age (Years)</label>
                            <input type="number" id="studentAge" name="studentAge" min="5" max="19" placeholder="Between 5 and 19" required>
                        </div>
                        <div class="form-group">
                            <label for="studentGender"><i class="fas fa-venus-mars"></i> Gender</label>
                            <select id="studentGender" name="studentGender" required>
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other/Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <p style="margin-bottom: 10px; font-weight: 600; color: #444;"><i class="fas fa-calculator"></i> BMI Input Method</p>
                        <div class="input-option">
                            <input type="radio" id="inputMethodBMI" name="inputMethod" value="bmi" checked>
                            <label for="inputMethodBMI">Enter BMI directly</label>
                        </div>
                        <div class="input-option">
                            <input type="radio" id="inputMethodHW" name="inputMethod" value="heightWeight">
                            <label for="inputMethodHW">Enter height & weight</label>
                        </div>
                    </div>
                    
                    <div id="bmiInputSection">
                        <div class="form-group">
                            <label for="studentBMI"><i class="fas fa-weight"></i> BMI Value</label>
                            <input type="number" id="studentBMI" name="studentBMI" step="0.1" min="10" max="50" placeholder="Enter BMI (e.g., 18.5)" required>
                            <small style="color: #666; display: block; margin-top: 5px;">BMI = weight(kg) / height(m)²</small>
                        </div>
                    </div>
                    
                    <div id="heightWeightInputSection" class="hidden">
                        <div class="input-group">
                            <div class="form-group">
                                <label for="studentHeight"><i class="fas fa-ruler-vertical"></i> Height (cm)</label>
                                <input type="number" id="studentHeight" name="studentHeight" step="0.1" min="50" max="250" placeholder="Height in cm">
                            </div>
                            <div class="form-group">
                                <label for="studentWeight"><i class="fas fa-weight-hanging"></i> Weight (kg)</label>
                                <input type="number" id="studentWeight" name="studentWeight" step="0.1" min="10" max="200" placeholder="Weight in kg">
                            </div>
                        </div>
                        
                        <div id="calculatedBMIContainer" class="calculated-bmi hidden">
                            <p>Calculated BMI: <span id="calculatedBMI" class="bmi-display">0.0</span></p>
                            <p>Category: <span id="calculatedCategory">-</span></p>
                            <p><small>This value will be saved when you submit the form</small></p>
                        </div>
                        
                        <button type="button" id="calculateBMI" class="btn btn-secondary" style="margin-top: 10px;">
                            <i class="fas fa-calculator"></i> Calculate BMI
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="measurementDate"><i class="fas fa-calendar-alt"></i> Measurement Date</label>
                        <input type="date" id="measurementDate" name="measurementDate" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div style="display: flex; gap: 15px; margin-top: 30px;">
                        <button type="submit" class="btn">
                            <i class="fas fa-save"></i> Save BMI Record
                        </button>
                        <button type="button" id="clearForm" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Clear Form
                        </button>
                    </div>
                </form>
                
                <div class="students-list">
                    <h3 class="section-title"><i class="fas fa-history"></i> Recent Entries</h3>
                    <div id="studentsContainer">
                        <?php if (count($recentStudents) == 0): ?>
                            <div style="text-align: center; padding: 30px; background-color: #f8f9fa; border-radius: 10px;">
                                <i class="fas fa-clipboard-list" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                                <h4 style="color: #666; margin-bottom: 10px;">No student records yet</h4>
                                <p style="color: #999;">Add a student using the form above</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentStudents as $student): ?>
                                <?php
                                $bmiClass = getBMICategoryClass($student['bmi']);
                                $isAtRisk = needsHealthAssistance($student['bmi_category']);
                                $isModerateRisk = $student['bmi_category'] == "Overweight";
                                $cardClass = 'student-card';
                                if ($isAtRisk) $cardClass .= ' at-risk';
                                if ($isModerateRisk) $cardClass .= ' moderate-risk';
                                ?>
                                <div class="<?php echo $cardClass; ?>">
                                    <div class="student-info">
                                        <h4><?php echo htmlspecialchars($student['name']); ?></h4>
                                        <p>
                                            <i class="fas fa-school"></i> <?php echo htmlspecialchars($student['school']); ?> 
                                            | <i class="fas fa-graduation-cap"></i> G<?php echo $student['grade']; ?>-<?php echo $student['section']; ?>
                                            | <i class="fas fa-user"></i> <?php echo $student['age']; ?>y, <?php echo ucfirst($student['gender']); ?>
                                            | <i class="fas fa-calendar"></i> <?php echo date('M d', strtotime($student['measurement_date'])); ?>
                                        </p>
                                    </div>
                                    <div class="bmi-value <?php echo $bmiClass; ?>">
                                        <?php echo $student['bmi']; ?> 
                                        <small style="font-size: 0.9rem;">(<?php echo $student['bmi_category']; ?>)</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if (count($students) > 5): ?>
                                <div style="text-align: center; margin-top: 20px;">
                                    <span style="color: #666; padding: 10px 20px; background-color: #f8f9fa; border-radius: 20px;">
                                        <i class="fas fa-ellipsis-h"></i>
                                        and <?php echo count($students) - 5; ?> more students
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
            <section class="analytics-section">
                <h2 class="section-title"><i class="fas fa-chart-bar"></i> Health Analytics Dashboard</h2>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-label">Total Students</div>
                        <div class="stat-value"><?php echo $totalStudents; ?></div>
                        <i class="fas fa-users" style="font-size: 2rem; color: #1e5799; opacity: 0.3;"></i>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-label">Normal BMI</div>
                        <div class="stat-value"><?php echo $normalCount; ?></div>
                        <i class="fas fa-heart" style="font-size: 2rem; color: #28a745; opacity: 0.3;"></i>
                    </div>
                    
                    <div class="stat-card moderate-risk">
                        <div class="stat-label">Overweight</div>
                        <div class="stat-value"><?php echo $overweightCount; ?></div>
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #ffc107; opacity: 0.3;"></i>
                    </div>
                    
                    <div class="stat-card at-risk">
                        <div class="stat-label">At Risk</div>
                        <div class="stat-value"><?php echo $atRiskCount; ?></div>
                        <i class="fas fa-heartbeat" style="font-size: 2rem; color: #dc3545; opacity: 0.3;"></i>
                    </div>
                </div>
                
                <?php if (!empty($schoolStats)): ?>
                    <div class="school-stats">
                        <h4><i class="fas fa-school"></i> School Distribution</h4>
                        <div class="school-list">
                            <?php foreach($schoolStats as $stat): ?>
                                <div class="school-badge">
                                    <i class="fas fa-university"></i>
                                    <?php echo htmlspecialchars($stat['school']); ?>
                                    <span class="school-count"><?php echo $stat['count']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <h3 class="section-title"><i class="fas fa-exclamation-circle"></i> Students Needing Health Assistance</h3>
                <div id="atRiskContainer">
                    <?php if (count($atRiskStudents) == 0): ?>
                        <div style="text-align: center; padding: 30px; background-color: #f8f9fa; border-radius: 10px;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: #28a745; margin-bottom: 15px;"></i>
                            <h4 style="color: #666; margin-bottom: 10px;">All students are within healthy range</h4>
                            <p style="color: #999;">No students currently need immediate health assistance</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($atRiskStudents as $student): ?>
                            <?php
                            $bmiClass = getBMICategoryClass($student['bmi']);
                            $isUnderweight = $student['bmi_category'] == "Underweight";
                            ?>
                            <div class="student-card at-risk">
                                <div class="student-info">
                                    <h4><?php echo htmlspecialchars($student['name']); ?> (Grade <?php echo $student['grade']; ?>, <?php echo $student['section']; ?>)</h4>
                                    <p>
                                        <i class="fas fa-school"></i> <?php echo htmlspecialchars($student['school']); ?> 
                                        | <i class="fas fa-user"></i> <?php echo $student['age']; ?>y, <?php echo ucfirst($student['gender']); ?>
                                        | <i class="fas fa-weight"></i> BMI: <?php echo $student['bmi']; ?>
                                        | <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($student['measurement_date'])); ?>
                                    </p>
                                    <p style="margin-top: 8px; color: #dc3545;">
                                        <i class="fas fa-stethoscope"></i> 
                                        <strong>Health Priority:</strong> 
                                        <?php echo $isUnderweight ? 'Nutritional support needed' : 'Weight management program recommended'; ?>
                                    </p>
                                </div>
                                <div class="bmi-value <?php echo $bmiClass; ?>"><?php echo $student['bmi_category']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="bmi-categories">
                    <div class="bmi-category">
                        <div class="category-color underweight-color"></div>
                        <span><strong>Underweight</strong> (&lt; 18.5)</span>
                    </div>
                    <div class="bmi-category">
                        <div class="category-color normal-color"></div>
                        <span><strong>Normal</strong> (18.5 - 24.9)</span>
                    </div>
                    <div class="bmi-category">
                        <div class="category-color overweight-color"></div>
                        <span><strong>Overweight</strong> (25 - 29.9)</span>
                    </div>
                    <div class="bmi-category">
                        <div class="category-color obese-color"></div>
                        <span><strong>Obese</strong> (≥ 30)</span>
                    </div>
                </div>
                
                <div class="actions">
                    <a href="report.php" class="btn btn-info">
                        <i class="fas fa-file-medical-alt"></i> View Detailed Report
                    </a>
                    
                    <?php if ($totalStudents > 0): ?>
                        <a href="?export=csv" class="btn btn-success">
                            <i class="fas fa-file-export"></i> Export Data
                        </a>
                        
                        <?php if (isset($_GET['delete_all'])): ?>
                            <div class="delete-confirm">
                                <p>Are you sure you want to delete all <?php echo $totalStudents; ?> student records?</p>
                                <div style="display: flex; gap: 10px; margin-top: 10px;">
                                    <a href="?delete_all=true&confirm=true" class="btn btn-danger">Yes, Delete All</a>
                                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="?delete_all=true" class="btn btn-secondary">
                                <i class="fas fa-trash-alt"></i> Clear All Data
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        
        <footer>
            <p><strong>Student BMI Tracker System</strong> | Comprehensive health monitoring platform for educational institutions</p>
            <p>Note: This tool is for educational and monitoring purposes. Always consult with healthcare professionals for medical advice and diagnosis.</p>
            <p style="margin-top: 15px; font-size: 0.9rem; color: #999;">
                <i class="fas fa-info-circle"></i> 
                BMI categories are based on standard adult ranges. For children and teens, BMI percentiles should be used for accurate assessment.
            </p>
        </footer>
    </div>

    <script>
        // Set default date to today
        document.getElementById('measurementDate').valueAsDate = new Date();
        
        // Set up input method toggle
        function setupInputMethodToggle() {
            const bmiRadio = document.getElementById('inputMethodBMI');
            const hwRadio = document.getElementById('inputMethodHW');
            const bmiSection = document.getElementById('bmiInputSection');
            const hwSection = document.getElementById('heightWeightInputSection');
            
            bmiRadio.addEventListener('change', function() {
                if (this.checked) {
                    bmiSection.classList.remove('hidden');
                    hwSection.classList.add('hidden');
                    
                    // Make BMI field required
                    document.getElementById('studentBMI').required = true;
                    
                    // Clear height/weight fields
                    document.getElementById('studentHeight').value = '';
                    document.getElementById('studentWeight').value = '';
                }
            });
            
            hwRadio.addEventListener('change', function() {
                if (this.checked) {
                    bmiSection.classList.add('hidden');
                    hwSection.classList.remove('hidden');
                    
                    // Make BMI field not required
                    document.getElementById('studentBMI').required = false;
                    
                    // Clear BMI field
                    document.getElementById('studentBMI').value = '';
                }
            });
        }
        
        // Calculate BMI from height and weight
        function calculateBMIFromHeightWeight() {
            const heightInput = document.getElementById('studentHeight');
            const weightInput = document.getElementById('studentWeight');
            const bmiContainer = document.getElementById('calculatedBMIContainer');
            const calculatedBMIElement = document.getElementById('calculatedBMI');
            const calculatedCategoryElement = document.getElementById('calculatedCategory');
            
            const height = parseFloat(heightInput.value);
            const weight = parseFloat(weightInput.value);
            
            // Validate inputs
            if (!height || !weight || height <= 0 || weight <= 0) {
                bmiContainer.classList.add('hidden');
                return;
            }
            
            // Convert height from cm to meters
            const heightInMeters = height / 100;
            
            // Calculate BMI: weight (kg) / height (m)²
            const bmi = weight / (heightInMeters * heightInMeters);
            const roundedBMI = Math.round(bmi * 10) / 10;
            
            // Get BMI category
            const category = getBMICategory(roundedBMI);
            const categoryClass = getBMICategoryClass(roundedBMI);
            
            // Update display
            calculatedBMIElement.textContent = roundedBMI;
            calculatedBMIElement.className = `bmi-display ${categoryClass}`;
            calculatedCategoryElement.textContent = category;
            calculatedCategoryElement.className = categoryClass;
            
            // Show the calculated BMI container
            bmiContainer.classList.remove('hidden');
            
            return roundedBMI;
        }
        
        // Function to determine BMI category
        function getBMICategory(bmi) {
            if (bmi < 18.5) return "Underweight";
            if (bmi >= 18.5 && bmi < 25) return "Normal";
            if (bmi >= 25 && bmi < 30) return "Overweight";
            return "Obese";
        }
        
        // Function to get BMI category class for styling
        function getBMICategoryClass(bmi) {
            if (bmi < 18.5) return "bmi-underweight";
            if (bmi >= 18.5 && bmi < 25) return "bmi-normal";
            if (bmi >= 25 && bmi < 30) return "bmi-overweight";
            return "bmi-obese";
        }
        
        // Clear form button
        document.getElementById('clearForm').addEventListener('click', function() {
            document.getElementById('bmiForm').reset();
            document.getElementById('inputMethodBMI').checked = true;
            document.getElementById('bmiInputSection').classList.remove('hidden');
            document.getElementById('heightWeightInputSection').classList.add('hidden');
            document.getElementById('calculatedBMIContainer').classList.add('hidden');
            document.getElementById('studentBMI').required = true;
            document.getElementById('measurementDate').valueAsDate = new Date();
        });
        
        // Set up event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setupInputMethodToggle();
            
            // Set up BMI calculation
            document.getElementById('calculateBMI').addEventListener('click', calculateBMIFromHeightWeight);
            
            // Auto-calculate when height/weight values change
            document.getElementById('studentHeight').addEventListener('input', calculateBMIFromHeightWeight);
            document.getElementById('studentWeight').addEventListener('input', calculateBMIFromHeightWeight);
        });
    </script>
</body>
</html>