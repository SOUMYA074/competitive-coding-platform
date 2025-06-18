<?php
// config.php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cc_platform";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
// index.php
include 'config.php';

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    $problemId = (int)$_GET['id'];
    switch($_GET['action']) {
        case 'problem':
            $problem = $conn->query("SELECT * FROM problems WHERE id = $problemId")->fetch_assoc();
            echo json_encode($problem);
            exit;
            
        case 'testcases':
            $testCases = $conn->query("SELECT input_data, output_data FROM test_cases WHERE problem_id = $problemId")->fetch_all(MYSQLI_ASSOC);
            echo json_encode($testCases);
            exit;
            
        case 'templates':
            $templates = $conn->query("SELECT language, code FROM templates WHERE problem_id = $problemId")->fetch_all(MYSQLI_ASSOC);
            echo json_encode($templates);
            exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['problem_id'])) {
    $response = ['success' => false];
    
    $user_id = $_SESSION['user_id'];
    $problem_id = (int)$_POST['problem_id'];
    $code = $conn->real_escape_string($_POST['code']);
    $language = $conn->real_escape_string($_POST['language']);

    $solved = $conn->query("SELECT id FROM submissions WHERE user_id=$user_id AND problem_id=$problem_id")->num_rows;

    if ($solved === 0) {
        $problem = $conn->query("SELECT difficulty FROM problems WHERE id=$problem_id")->fetch_assoc();
        
        if ($problem) {
            $points = match(strtolower($problem['difficulty'])) {
                'easy' => 20, 'medium' => 30, 'hard' => 50, default => 0
            };

            $conn->query("UPDATE users SET 
                points = points + $points,
                problems_solved = problems_solved + 1 
                WHERE id=$user_id");

            $conn->query("INSERT INTO submissions (user_id, problem_id, code, language) 
                VALUES ($user_id, $problem_id, '$code', '$language')");

            $response['success'] = true;
            $response['points'] = $points;
            $response['new_points'] = $conn->query("SELECT points FROM users WHERE id=$user_id")->fetch_assoc()['points'];
        }
    }
    die(json_encode($response));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODIL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa, #ffffff, #e6e6ff);
            background-size: 400% 400%;
            animation: gradientFlow 15s ease infinite;
            min-height: 100vh;
        }

        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .header-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 2px solid #00f3ff;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.1);
        }

        .xp-display {
            background: linear-gradient(145deg, #00f3ff, #ff0080);
            color: white !important;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 243, 255, 0.3);
        }

        .problems-container {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            border: 2px solid #00f3ff;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.1) !important;
        }

        .problem-item {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(0, 243, 255, 0.3);
            transition: all 0.3s ease;
        }

        .problem-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 243, 255, 0.2) !important;
        }

        .btn-primary {
            background: linear-gradient(145deg, #00f3ff, #ff0080);
            border: none;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 243, 255, 0.3);
        }

        .editor-panel {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            border: 2px solid #00f3ff !important;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.1) !important;
        }

        .cm-editor {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid #00f3ff !important;
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.1);
        }

        .test-case-card {
            background: rgba(255, 255, 255, 0.9) !important;
            border-left: 4px solid #00f3ff !important;
            box-shadow: 0 2px 10px rgba(0, 243, 255, 0.1);
        }

        .passed-test {
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid #00f3ff;
        }

        .failed-test {
            background: rgba(255, 0, 128, 0.1);
            border: 1px solid #ff0080;
        }

        .loading-indicator {
            color: #2d2d44;
            font-family: 'Orbitron', sans-serif;
        }
    </style>
</head>
<body>
<?php include 'D:/XAMPP/htdocs/coding-platform/includes/header.php'; ?>
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="header-container flex justify-between items-center mb-8 p-6 rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800" style="font-family: 'Orbitron', sans-serif;">CODIL</h1>
            <div class="flex items-center gap-4">
                <div class="xp-display px-4 py-2 rounded-lg">
                    <span>XP : </span>
                    <span class="font-semibold">
                        <?= $conn->query("SELECT points FROM users WHERE id=".$_SESSION['user_id'])->fetch_assoc()['points'] ?? 0 ?>
                    </span>
                </div>
                <a href="logout.php" class="btn-primary px-4 py-2 rounded-lg transition-colors">
                    Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Problems List -->
            <div class="problems-container p-6 rounded-xl">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Problems</h2>
                <div class="space-y-4">
                    <?php
                    $result = $conn->query("SELECT * FROM problems ORDER BY FIELD(difficulty, 'easy', 'medium', 'hard')");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <div class="problem-item p-4 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-800"><?= htmlspecialchars($row['title']) ?></h3>
                                <span class="text-sm <?= match($row['difficulty']) {
                                    'easy' => 'text-green-600',
                                    'medium' => 'text-yellow-600',
                                    'hard' => 'text-red-600'
                                } ?>"><?= ucfirst($row['difficulty']) ?></span>
                            </div>
                            <button class="btn-primary px-4 py-2 rounded-lg"
                                    data-problem-id="<?= $row['id'] ?>">
                                Solve
                            </button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Editor Panel -->
            <div id="editor-panel" class="editor-panel hidden p-6 rounded-xl">
                <div class="flex justify-between items-center mb-6">
                    <button onclick="showProblemList()" class="text-gray-600 hover:text-blue-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back
                    </button>
                    <select id="language-select" class="bg-gray-50 text-gray-900 rounded-lg px-4 py-2">
                        <option value="javascript">JavaScript</option>
                        <option value="python">Python</option>
                        <option value="c">C</option>
                        <option value="cpp">C++</option>
                        <option value="java">Java</option>
                    </select>
                </div>

                <!-- Problem Content -->
                <div id="problem-content">
                    <div id="loading-indicator" class="hidden loading-indicator space-y-4">
                        <div class="h-6 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-200 rounded w-full"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    </div>

                    <h3 id="problem-title" class="text-xl font-semibold mb-4 text-gray-800"></h3>
                    <div id="problem-description" class="prose max-w-none mb-6 text-gray-600"></div>
                    
                    <h4 class="text-lg font-medium mb-3 text-gray-800">Test Cases</h4>
                    <div id="test-cases" class="space-y-4"></div>
                </div>

                <!-- Code Editor -->
                <div class="mb-6">
                    <textarea id="code-editor"></textarea>
                </div>

                <!-- Submit Button -->
                <button onclick="runTests()" 
                        class="btn-primary w-full font-medium py-3 rounded-lg">
                    Submit Solution
                </button>

                <!-- Results -->
                <div id="results" class="mt-6 space-y-4"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.0/mode/clike/clike.min.js"></script>

    <!-- JavaScript remains exactly the same -->
    <script>
        let editor = CodeMirror.fromTextArea(document.getElementById('code-editor'), {
            lineNumbers: true,
            theme: 'default',
            mode: 'javascript',
            matchBrackets: true,
            autoCloseBrackets: true,
            extraKeys: {'Ctrl-Space': 'autocomplete'}
        });

        let currentProblem = null;

        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.addEventListener('click', async () => {
                const problemId = btn.dataset.problemId;
                try {
                    // Show loading state
                    document.getElementById('editor-panel').classList.remove('hidden');
                    document.getElementById('loading-indicator').classList.remove('hidden');
                    document.getElementById('problem-title').classList.add('hidden');
                    document.getElementById('problem-description').classList.add('hidden');
                    document.getElementById('test-cases').classList.add('hidden');

                    const [problemRes, testCasesRes, templatesRes] = await Promise.all([
                        fetch(`?action=problem&id=${problemId}`),
                        fetch(`?action=testcases&id=${problemId}`),
                        fetch(`?action=templates&id=${problemId}`)
                    ]);

                    currentProblem = {
                        ...await problemRes.json(),
                        test_cases: await testCasesRes.json(),
                        templates: await templatesRes.json()
                    };

                    // Hide loading state
                    document.getElementById('loading-indicator').classList.add('hidden');
                    document.getElementById('problem-title').classList.remove('hidden');
                    document.getElementById('problem-description').classList.remove('hidden');
                    document.getElementById('test-cases').classList.remove('hidden');

                    // Update content
                    document.getElementById('problem-title').textContent = currentProblem.title;
                    document.getElementById('problem-description').innerHTML = currentProblem.description;

                    // Render test cases
                    document.getElementById('test-cases').innerHTML = currentProblem.test_cases
    .map((tc, i) => `
        <div class="cyber-testcase bg-white/80 backdrop-blur-sm p-4 rounded-lg border-2 border-cyan-400 shadow-glow mb-4">
            <div class="font-mono text-cyan-500 text-lg mb-3 flex items-center gap-2">
                <span class="text-cyan-300">➜</span>
                <span class="glow-text-cyan">TEST_CASE_${i + 1}</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 cyber-grid">
                <div class="cyber-input-group">
                    <label class="text-sm font-mono text-cyan-600/80 mb-1">INPUT</label>
                    <pre class="mt-1 p-3 bg-black/20 border border-cyan-400/30 rounded-md text-white-300 font-mono text-sm backdrop-blur-sm">${tc.input_data}</pre>
                </div>
                <div class="cyber-output-group">
                    <label class="text-sm font-mono text-cyan-600/80 mb-1">EXPECTED_OUTPUT</label>
                    <pre class="mt-1 p-3 bg-black/20 border border-cyan-400/30 rounded-md text-white-400 font-mono text-sm backdrop-blur-sm">${tc.output_data}</pre>
                </div>
            </div>
        </div>
    `).join('');


                    // Initialize editor
                    updateEditorLanguage();

                } catch(error) {
                    document.getElementById('loading-indicator').classList.add('hidden');
                    alert('Error loading problem: ' + error.message);
                }
            });
        });

        function updateEditorLanguage() {
            const lang = document.getElementById('language-select').value;
            const modes = {
                javascript: 'javascript',
                python: 'python',
                c: 'text/x-csrc',
                cpp: 'text/x-c++src',
                java: 'text/x-java'
            };
            editor.setOption('mode', modes[lang]);
            
            const template = currentProblem.templates.find(t => t.language === lang);
            editor.setValue(template?.code || `// Start coding here...\n`);
        }

        document.getElementById('language-select').addEventListener('change', updateEditorLanguage);
//run the code
async function runTests() {
    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '<div class="loading">Running tests...</div>';

    try {
        const lang = document.getElementById('language-select').value;
        const code = editor.getValue();
        const testCases = currentProblem.test_cases;

        // Map language to Judge0 language_id
        const languageMap = {
            'python': 71,
            'cpp': 54,
            'java': 62,
            'c': 50,
            'javascript': 63
        };

        const languageId = languageMap[lang];
        if (!languageId) throw new Error('Unsupported language');

        const results = await Promise.all(testCases.map(async (tc, index) => {
            try {
                // Submit the code for execution
                const submitRes = await fetch("https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=false&wait=true", {
                    method: "POST",
                    headers: {
                        "content-type": "application/json",
                        "x-rapidapi-host": "judge0-ce.p.rapidapi.com",
                        "x-rapidapi-key": "b49593be1bmshc00f82367955e2ap13b966jsn197d311f2af2"
                    },
                    body: JSON.stringify({
                        language_id: languageId,
                        source_code: code,
                        stdin: tc.input_data
                    })
                });

                if (!submitRes.ok) throw new Error("API submission failed ");
                const data = await submitRes.json();

                return {
                    testCase: index + 1,
                    input: tc.input_data,
                    expected: tc.output_data,
                    received: data.stdout ? data.stdout.trim() : "",
                    passed: data.stdout && data.stdout.trim() === tc.output_data.trim(),
                    error: data.stderr || data.compile_output
                };
            } catch (err) {
                return {
                    testCase: index + 1,
                    error: err.message,
                    passed: false
                };
            }
        }));

        // Display Results
        resultsDiv.innerHTML = results.map(result => `
            <div class="test-case ${result.passed ? 'passed' : 'failed'}">
                <h4>Test Case ${result.testCase}</h4>
                ${result.error ? `
                    <div class="error">Error: ${result.error}</div>
                ` : `
                    <div class="input">Input: ${result.input}</div>
                    <div class="expected">Expected: ${result.expected}</div>
                    <div class="received">Received: ${result.received}</div>
                `}
                <div class="status">${result.passed ? '✅ Passed' : '❌ Failed'}</div>
            </div>
        `).join('');

        
        // Submit if all passed
if (results.every(r => r.passed)) {
    const response = await fetch('submit_solution.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            problem_id: currentProblem.id,
            code: code,
            language: lang
        })
    });

    const result = await response.json();

    if (result.success) {
        alert(`All tests passed! 🎉 You've earned ${result.points} points!`);
    } else {
        alert('Points not updated: ' + result.message);
    }
}


    } catch (error) {
        resultsDiv.innerHTML = `
            <div class="error">
                System Error: ${error.message}
            </div>
        `;
    }
}




        function showProblemList() {
            document.getElementById('editor-panel').classList.add('hidden');
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    </script>
        <style>
    .cyber-testcase {
    position: relative;
    transition: all 0.3s ease;
    background: linear-gradient(145deg, rgba(255,255,255,0.9) 0%, rgba(224,255,255,0.95) 100%);
}

.shadow-glow {
    box-shadow: 0 0 20px rgba(0, 247, 255, 0.15);
}

.cyber-testcase:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(0, 247, 255, 0.3);
}

.glow-text-cyan {
    text-shadow: 0 0 12px rgba(0, 247, 255, 0.4);
}

.cyber-grid {
    border-top: 1px solid rgba(0, 247, 255, 0.2);
    padding-top: 1rem;
}

.cyber-input-group pre {
    border-left: 3px solid #00f7ff;
}

.cyber-output-group pre {
    border-left: 3px solid #00ff4c;
}
    </style>

<?php include 'D:/XAMPP/htdocs/coding-platform/includes/footer.php'; ?>
</body>
</html>