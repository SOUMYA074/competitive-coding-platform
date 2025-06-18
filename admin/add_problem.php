<?php
include 'D:/XAMPP/htdocs/coding-platform/config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $title = $_POST['title'];
    $difficulty = $_POST['difficulty'];
    $description = $_POST['description'];
    $test_cases = $_POST['test_cases'];
    $templates = $_POST['templates'];

    // Insert problem
    $stmt = $conn->prepare("INSERT INTO problems (title, difficulty, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $difficulty, $description);
    $stmt->execute();
    $problem_id = $stmt->insert_id;

    // Insert test cases
    $test_case_stmt = $conn->prepare("INSERT INTO test_cases (problem_id, input_data, output_data) VALUES (?, ?, ?)");
    foreach ($test_cases as $tc) {
        $test_case_stmt->bind_param("iss", $problem_id, $tc['input'], $tc['output']);
        $test_case_stmt->execute();
    }

    // Insert templates
    $template_stmt = $conn->prepare("INSERT INTO templates (problem_id, language, code) VALUES (?, ?, ?)");
    foreach ($templates as $lang => $code) {
        $template_stmt->bind_param("iss", $problem_id, $lang, $code);
        $template_stmt->execute();
    }

    header("Location: add_problem.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Problem</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Cyberpunk Theme Variables */
        :root {
            --bg-dark: #0a0e17;
            --bg-panel: #121b2b;
            --neon-blue: #00f3ff;
            --neon-purple: #bd00ff;
            --text-primary: #e0f4ff;
            --text-secondary: #a0c4e0;
            --border-glow: 0 0 10px rgba(0, 243, 255, 0.7);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(11, 27, 66, 0.8) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(11, 27, 66, 0.8) 0%, transparent 20%),
                linear-gradient(to bottom, #0a0e17, #05111f);
            min-height: 100vh;
            padding: 20px;
            overflow-x: hidden;
        }
        
        .cyberpunk-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background-color: rgba(18, 27, 43, 0.8);
            border-radius: 12px;
            box-shadow: 
                0 0 25px rgba(0, 243, 255, 0.2),
                inset 0 0 15px rgba(0, 243, 255, 0.1);
            border: 1px solid rgba(0, 243, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .cyberpunk-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                #00f3ff, #bd00ff, #00f3ff, #bd00ff);
            background-size: 400% 400%;
            z-index: -1;
            border-radius: 14px;
            animation: gradient-border 15s ease infinite;
        }
        
        @keyframes gradient-border {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--neon-blue);
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.7);
            position: relative;
            padding-bottom: 15px;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--neon-blue), transparent);
        }
        
        h3 {
            color: var(--neon-blue);
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(0, 243, 255, 0.3);
            text-shadow: 0 0 5px rgba(0, 243, 255, 0.5);
        }
        
        .success-message {
            background: rgba(0, 195, 0, 0.2);
            border: 1px solid rgba(0, 255, 100, 0.5);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0, 255, 100, 0.3);
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
        }
        
        .success-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 100, 0.2), transparent);
            animation: success-glow 2s infinite;
        }
        
        @keyframes success-glow {
            100% { left: 100%; }
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 14px;
            background-color: rgba(10, 20, 35, 0.7);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: var(--transition);
            outline: none;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--neon-blue);
            box-shadow: var(--border-glow);
            background-color: rgba(15, 30, 50, 0.8);
        }
        
        .dynamic-section {
            background-color: rgba(10, 20, 35, 0.5);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.3);
        }
        
        .dynamic-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
        }
        
        .test-case {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            background: rgba(15, 30, 50, 0.4);
            border-radius: 6px;
            padding: 15px;
            border: 1px solid rgba(0, 243, 255, 0.1);
        }
        
        .test-case input {
            flex: 1;
            background-color: rgba(8, 18, 33, 0.8);
        }
        
        .add-btn {
            background: linear-gradient(45deg, #0066cc, #00aaff);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: 0 0 15px rgba(0, 170, 255, 0.4);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .add-btn:hover {
            background: linear-gradient(45deg, #00aaff, #00f3ff);
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.7);
            transform: translateY(-2px);
        }
        
        .add-btn i {
            font-size: 1.1rem;
        }
        
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .template-group {
            background: rgba(15, 30, 50, 0.4);
            border-radius: 8px;
            padding: 15px;
            border: 1px solid rgba(0, 243, 255, 0.1);
        }
        
        .template-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }
        
        .template-group label i {
            color: var(--neon-blue);
        }
        
        .template-group textarea {
            min-height: 150px;
            resize: vertical;
            font-family: monospace;
            font-size: 0.95rem;
            background-color: rgba(8, 18, 33, 0.8);
        }
        
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(45deg, var(--neon-purple), var(--neon-blue));
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 0 20px rgba(189, 0, 255, 0.5);
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
            box-shadow: 
                0 0 25px rgba(0, 243, 255, 0.7),
                0 0 25px rgba(189, 0, 255, 0.7);
            transform: translateY(-3px);
        }
        
        .cyber-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.1;
            background-image: 
                linear-gradient(rgba(0, 243, 255, 0.2) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 243, 255, 0.2) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -1;
        }
        
        .glow-effect {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 243, 255, 0.3), transparent 70%);
            filter: blur(30px);
            z-index: -1;
        }
        
        .glow-1 {
            top: -150px;
            left: -150px;
        }
        
        .glow-2 {
            bottom: -150px;
            right: -150px;
            background: radial-gradient(circle, rgba(189, 0, 255, 0.3), transparent 70%);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .cyberpunk-container {
                padding: 20px;
                margin: 20px;
            }
            
            .test-case {
                flex-direction: column;
                gap: 10px;
            }
            
            .template-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="glow-effect glow-1"></div>
    <div class="glow-effect glow-2"></div>
    
    <div class="cyberpunk-container">
        <div class="cyber-grid"></div>
        
        <h1><i class="fas fa-terminal"></i> Add New Problem</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> Problem added successfully!
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Problem Title:</label>
                <input type="text" name="title" required placeholder="Enter problem title">
            </div>

            <div class="form-group">
                <label>Difficulty Level:</label>
                <select name="difficulty" required>
                    <option value="">Select difficulty</option>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>

            <div class="form-group">
                <label>Problem Description:</label>
                <textarea name="description" rows="5" required placeholder="Enter detailed problem description"></textarea>
            </div>

            <div class="dynamic-section">
                <h3><i class="fas fa-vial"></i> Test Cases</h3>
                <div id="test-cases-container">
                    <div class="test-case">
                        <input type="text" name="test_cases[0][input]" placeholder="Input data" required>
                        <input type="text" name="test_cases[0][output]" placeholder="Expected output" required>
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addTestCase()">
                    <i class="fas fa-plus-circle"></i> Add Test Case
                </button>
            </div>

            <div class="dynamic-section">
                <h3><i class="fas fa-code"></i> Code Templates</h3>
                <div class="template-grid">
                    <div class="template-group">
                        <label><i class="fab fa-js"></i> JavaScript</label>
                        <textarea name="templates[javascript]" required placeholder="JavaScript template code"></textarea>
                    </div>
                    
                    <div class="template-group">
                        <label><i class="fab fa-python"></i> Python</label>
                        <textarea name="templates[python]" required placeholder="Python template code"></textarea>
                    </div>
                    
                    <div class="template-group">
                        <label><i class="fab fa-java"></i> Java</label>
                        <textarea name="templates[java]" required placeholder="Java template code"></textarea>
                    </div>
                    
                    <div class="template-group">
                        <label><i class="fas fa-c"></i> C</label>
                        <textarea name="templates[C]" required placeholder="C template code"></textarea>
                    </div>
                    
                    <div class="template-group">
                        <label><i class="fas fa-copyright"></i> C++</label>
                        <textarea name="templates[c++]" required placeholder="C++ template code"></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Save Problem
            </button>
        </form>
    </div>

    <script>
        let testCaseCount = 1;

        function addTestCase() {
            const container = document.getElementById('test-cases-container');
            const newCase = document.createElement('div');
            newCase.className = 'test-case';
            newCase.innerHTML = `
                <input type="text" name="test_cases[${testCaseCount}][input]" placeholder="Input data" required>
                <input type="text" name="test_cases[${testCaseCount}][output]" placeholder="Expected output" required>
            `;
            container.appendChild(newCase);
            testCaseCount++;
            
            // Scroll to the new test case
            newCase.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    </script>
</body>
</html>