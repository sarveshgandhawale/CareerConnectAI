<?php
/**
 * CareerConnect AI - Gemini AI Service Integration & Fallback Engine
 * Provides intelligent analysis for Resumes, Mock Interviews, and Career Roadmaps.
 */

require_once __DIR__ . '/../config/config.php';

if (!function_exists('getGeminiApiKey')) {
    function getGeminiApiKey() {
        global $gemini_api_key;
        if (!empty($gemini_api_key)) {
            return trim($gemini_api_key);
        }
        $envKey = $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY') ?? '';
        return trim($envKey);
    }
}

/**
 * Executes a Gemini API generateContent call
 */
if (!function_exists('callGeminiAPI')) {
    function callGeminiAPI($prompt, $systemInstruction = '') {
        $apiKey = getGeminiApiKey();
        if (empty($apiKey)) {
            return null;
        }

        // Available models to attempt
        $models = ['gemini-1.5-flash', 'gemini-1.5-pro', 'gemini-2.0-flash'];
        
        $requestData = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 2048,
            ]
        ];

        if (!empty($systemInstruction)) {
            $requestData['systemInstruction'] = [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ];
        }

        $jsonData = json_encode($requestData);

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . urlencode($apiKey);

            $response = false;
            $httpCode = 0;

            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $jsonData,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json'
                    ],
                    CURLOPT_TIMEOUT => 15,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false
                ]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            } else {
                $options = [
                    'http' => [
                        'header'  => "Content-Type: application/json\r\n",
                        'method'  => 'POST',
                        'content' => $jsonData,
                        'timeout' => 15,
                        'ignore_errors' => true
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    ]
                ];
                $context = stream_context_create($options);
                $response = @file_get_contents($url, false, $context);
                if (isset($http_response_header) && is_array($http_response_header)) {
                    foreach ($http_response_header as $header) {
                        if (preg_match('#HTTP/\S+\s+(\d+)#', $header, $matches)) {
                            $httpCode = (int)$matches[1];
                            break;
                        }
                    }
                }
            }

            if ($response && ($httpCode === 200 || $httpCode === 0)) {
                $decoded = json_decode($response, true);
                if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($decoded['candidates'][0]['content']['parts'][0]['text']);
                }
            }
        }

        return null;
    }
}

/**
 * Extracts JSON content from response text
 */
if (!function_exists('extractJsonFromText')) {
    function extractJsonFromText($text) {
        if (empty($text)) return null;
        
        // Remove markdown ```json ``` blocks if present
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $text, $matches)) {
            $json = json_decode($matches[1], true);
            if ($json) return $json;
        }

        // Attempt direct json decode
        $direct = json_decode($text, true);
        if ($direct) return $direct;

        // Search for outermost { ... }
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $jsonStr = substr($text, $start, $end - $start + 1);
            $parsed = json_decode($jsonStr, true);
            if ($parsed) return $parsed;
        }

        return null;
    }
}

/**
 * Analyze Resume Text with Gemini AI and intelligent fallback
 */
if (!function_exists('analyzeResumeText')) {
    function analyzeResumeText($resumeContent, $targetRole = 'Full Stack Developer') {
        $prompt = "You are a professional ATS (Applicant Tracking System) and Senior Technical Hiring Recruiter.\n\n"
                . "Analyze the following resume for the target role: \"{$targetRole}\".\n"
                . "Provide an evaluation in strictly valid JSON format with the following keys:\n"
                . "- \"ats_score\": integer between 45 and 95 representing compatibility.\n"
                . "- \"strengths\": 2 to 4 bullet points outlining key strengths found.\n"
                . "- \"improvements\": 2 to 4 actionable suggestions for improvement.\n"
                . "- \"missing_keywords\": comma-separated string of important industry keywords missing for {$targetRole}.\n"
                . "- \"ai_feedback\": 2 to 3 sentences of personalized executive recruiter advice.\n\n"
                . "Resume Text:\n" . substr($resumeContent, 0, 4000) . "\n\n"
                . "Return ONLY the JSON object.";

        $systemInstruction = "You are an ATS parser and recruitment advisor. Return strictly valid JSON.";
        $aiResponse = callGeminiAPI($prompt, $systemInstruction);
        $parsed = extractJsonFromText($aiResponse);

        if ($parsed && isset($parsed['ats_score'])) {
            return [
                'ats_score' => max(40, min(98, (int)$parsed['ats_score'])),
                'strengths' => is_array($parsed['strengths']) ? implode("\n• ", $parsed['strengths']) : (string)$parsed['strengths'],
                'improvements' => is_array($parsed['improvements']) ? implode("\n• ", $parsed['improvements']) : (string)$parsed['improvements'],
                'missing_keywords' => is_array($parsed['missing_keywords']) ? implode(', ', $parsed['missing_keywords']) : (string)$parsed['missing_keywords'],
                'ai_feedback' => (string)$parsed['ai_feedback']
            ];
        }

        // Fallback rule-based ATS evaluation
        $roleKeywords = [
            'Full Stack Developer' => ['React', 'Node.js', 'Express', 'MySQL', 'REST API', 'Git', 'HTML5', 'CSS3', 'JavaScript', 'TypeScript', 'Docker', 'AWS'],
            'Frontend Developer' => ['HTML5', 'CSS3', 'JavaScript', 'React', 'Vue', 'Redux', 'Responsive Design', 'Tailwind', 'Bootstrap', 'Webpack'],
            'Backend Developer' => ['PHP', 'Node.js', 'Python', 'Java', 'MySQL', 'PostgreSQL', 'MongoDB', 'REST APIs', 'Docker', 'Redis', 'Unit Testing'],
            'Data Scientist' => ['Python', 'Pandas', 'NumPy', 'Scikit-Learn', 'TensorFlow', 'SQL', 'Data Visualization', 'Tableau', 'Machine Learning', 'Statistics'],
            'DevOps Engineer' => ['Linux', 'Docker', 'Kubernetes', 'CI/CD', 'Jenkins', 'AWS', 'Terraform', 'Git', 'Bash', 'Prometheus', 'Grafana'],
            'Cyber Security Analyst' => ['Network Security', 'Penetration Testing', 'SIEM', 'Firewalls', 'Vulnerability Assessment', 'Wireshark', 'ISO 27001', 'Cryptography']
        ];

        $matchedRole = 'Full Stack Developer';
        foreach (array_keys($roleKeywords) as $k) {
            if (stripos($targetRole, $k) !== false || stripos($k, $targetRole) !== false) {
                $matchedRole = $k;
                break;
            }
        }

        $expectedKeywords = $roleKeywords[$matchedRole] ?? $roleKeywords['Full Stack Developer'];
        $found = [];
        $missing = [];

        foreach ($expectedKeywords as $kw) {
            if (stripos($resumeContent, $kw) !== false) {
                $found[] = $kw;
            } else {
                $missing[] = $kw;
            }
        }

        $baseScore = 60 + count($found) * 4;
        $wordCount = str_word_count($resumeContent);
        if ($wordCount > 150) $baseScore += 5;
        if (stripos($resumeContent, 'project') !== false) $baseScore += 5;
        if (stripos($resumeContent, 'experience') !== false || stripos($resumeContent, 'education') !== false) $baseScore += 5;

        $atsScore = min(92, max(52, $baseScore));

        return [
            'ats_score' => $atsScore,
            'strengths' => "• Clear structure highlighting educational background and core technical foundations.\n• Relevant project experiences aligning with industry development standards.\n• Identified proficiency with: " . (empty($found) ? 'Foundational Computing Concepts' : implode(', ', $found)) . ".",
            'improvements' => "• Add measurable outcome metrics and KPI results to project descriptions (e.g., 'improved performance by 25%').\n• Include missing industry-standard tooling keywords for {$targetRole}.\n• Ensure standard reverse chronological formatting in project timelines.",
            'missing_keywords' => implode(', ', array_slice($missing, 0, 7)),
            'ai_feedback' => "Your resume exhibits solid technical grounding for {$targetRole}. To boost your ATS visibility past 85%, integrate the identified missing keywords into your skills section and emphasize real-world project outcomes with quantitative metrics."
        ];
    }
}

/**
 * Evaluate Mock Interview Answer with Gemini AI and intelligent fallback
 */
if (!function_exists('evaluateInterviewAnswer')) {
    function evaluateInterviewAnswer($interviewType, $question, $userAnswer) {
        $prompt = "You are a Chief Technical Officer and HR Director conducting a {$interviewType} mock interview.\n\n"
                . "Question: \"{$question}\"\n"
                . "Candidate Answer: \"{$userAnswer}\"\n\n"
                . "Evaluate the answer and return strictly valid JSON with these keys:\n"
                . "- \"overall_score\": integer between 40 and 95.\n"
                . "- \"clarity_score\": integer between 40 and 95.\n"
                . "- \"technical_score\": integer between 40 and 95.\n"
                . "- \"communication_score\": integer between 40 and 95.\n"
                . "- \"strengths\": 2 to 3 bullet points highlighting positive elements.\n"
                . "- \"improvements\": 2 to 3 actionable areas for improvement.\n"
                . "- \"ai_feedback\": 2 to 3 sentences of constructive hiring manager feedback.\n"
                . "- \"ideal_answer\": A comprehensive 3 to 4 sentence model answer using the STAR method.\n\n"
                . "Return ONLY JSON.";

        $systemInstruction = "You are an expert technical interviewer and executive talent coach. Output strictly valid JSON.";
        $aiResponse = callGeminiAPI($prompt, $systemInstruction);
        $parsed = extractJsonFromText($aiResponse);

        if ($parsed && isset($parsed['overall_score'])) {
            return [
                'overall_score' => max(40, min(98, (int)$parsed['overall_score'])),
                'clarity_score' => max(40, min(98, (int)($parsed['clarity_score'] ?? $parsed['overall_score']))),
                'technical_score' => max(40, min(98, (int)($parsed['technical_score'] ?? $parsed['overall_score']))),
                'communication_score' => max(40, min(98, (int)($parsed['communication_score'] ?? $parsed['overall_score']))),
                'strengths' => is_array($parsed['strengths']) ? implode("\n• ", $parsed['strengths']) : (string)$parsed['strengths'],
                'improvements' => is_array($parsed['improvements']) ? implode("\n• ", $parsed['improvements']) : (string)$parsed['improvements'],
                'ai_feedback' => (string)$parsed['ai_feedback'],
                'ideal_answer' => (string)$parsed['ideal_answer']
            ];
        }

        // Fallback rule-based interview evaluation
        $length = str_word_count($userAnswer);
        $score = 65;
        if ($length > 25) $score += 8;
        if ($length > 60) $score += 10;
        if ($length > 120) $score += 5;

        $clarity = min(90, max(55, $score + rand(-3, 4)));
        $tech = min(92, max(50, $score + rand(-4, 3)));
        $comm = min(90, max(55, $score + rand(-2, 5)));
        $overall = (int)(($clarity + $tech + $comm) / 3);

        return [
            'overall_score' => $overall,
            'clarity_score' => $clarity,
            'technical_score' => $tech,
            'communication_score' => $comm,
            'strengths' => "• Direct and confident response addressing the core question prompt.\n• Good articulation of conceptual understanding and approach.\n• Clear progression in explaining reasoning.",
            'improvements' => "• Structure your response using the STAR technique (Situation, Task, Action, Result).\n• Include specific metrics, technologies, or real-life project examples to validate your points.\n• Conclude with a succinct takeaway tying back to the role's requirements.",
            'ai_feedback' => "Good foundation and confidence. To stand out in competitive recruitment rounds, elevate your answer by anchoring your explanation in a concrete project scenario and quantifying the measurable impact.",
            'ideal_answer' => "In my previous experience, I encountered a scenario where {$question} was critical. I systematically identified the core constraints, implemented best-practice solutions using modern tooling, and collaborated closely with team members to deliver a reliable outcome with measurable efficiency improvements."
        ];
    }
}

/**
 * Analyze Student Career Assessment with Gemini AI and intelligent fallback
 */
if (!function_exists('analyzeCareerAssessment')) {
    function analyzeCareerAssessment($data) {
        $studentName = $data['name'] ?? 'Student';
        $course = $data['course'] ?? 'Computer Science';
        $programming = $data['programming'] ?? 'Intermediate';
        $interest = $data['interest'] ?? 'Web Development';
        $communication = $data['communication'] ?? 'Intermediate';
        $problem = $data['problem'] ?? 'Medium';

        $prompt = "You are an AI Career Counselor for tech students.\n\n"
                . "Student Profile:\n"
                . "- Name: {$studentName}\n"
                . "- Course: {$course}\n"
                . "- Interest Field: {$interest}\n"
                . "- Programming Proficiency: {$programming}\n"
                . "- Communication Skill: {$communication}\n"
                . "- Problem Solving Aptitude: {$problem}\n\n"
                . "Provide a career recommendation in strictly valid JSON with keys:\n"
                . "- \"career\": Title of recommended career path (e.g., 'Full Stack Web Developer', 'AI/ML Engineer').\n"
                . "- \"score\": integer match percentage (70 to 96).\n"
                . "- \"skills\": Recommended current & future technical skills required (comma-separated or listed).\n"
                . "- \"missing_skills\": Key skills to master over the next 6 months.\n"
                . "- \"roadmap\": Step-by-step 4-phase milestone roadmap (Phase 1, Phase 2, Phase 3, Phase 4).\n"
                . "- \"ai_advice\": Comprehensive personalized guidance paragraph.\n\n"
                . "Return ONLY JSON.";

        $systemInstruction = "You are an AI Career Advisor specializing in software engineering and technology careers. Output strictly valid JSON.";
        $aiResponse = callGeminiAPI($prompt, $systemInstruction);
        $parsed = extractJsonFromText($aiResponse);

        if ($parsed && isset($parsed['career'])) {
            return [
                'career' => (string)$parsed['career'],
                'score' => max(60, min(98, (int)($parsed['score'] ?? 85))),
                'skills' => is_array($parsed['skills']) ? implode(", ", $parsed['skills']) : (string)$parsed['skills'],
                'missing_skills' => is_array($parsed['missing_skills']) ? implode(", ", $parsed['missing_skills']) : (string)$parsed['missing_skills'],
                'roadmap' => is_array($parsed['roadmap']) ? implode("\n", $parsed['roadmap']) : (string)$parsed['roadmap'],
                'ai_advice' => (string)$parsed['ai_advice']
            ];
        }

        // Rule-based career guidance mapping fallback
        $careers = [
            'Web Development' => [
                'role' => 'Full Stack Web Developer',
                'skills' => 'HTML5, CSS3, JavaScript (ES6+), React.js, PHP, Node.js, MySQL, REST APIs, Git & GitHub',
                'missing' => 'TypeScript, Docker, TailwindCSS, State Management (Redux/Zustand), CI/CD Pipelines',
                'roadmap' => "Phase 1 (Months 1-2): Master modern JavaScript (ES6+), responsive layouts, and Git version control.\nPhase 2 (Months 3-4): Build full-stack applications with React frontend, PHP/Node.js backend, and relational databases.\nPhase 3 (Month 5): Implement secure authentication, JWT, RESTful API design, and automated testing.\nPhase 4 (Month 6): Deploy scalable projects on Cloud (AWS/Vercel), optimize SEO, and prepare portfolio.",
                'advice' => "Your high interest in Web Development coupled with your {$programming} programming level makes Full Stack Development an exceptional career path. Focus on shipping 2 production-ready capstone projects with full user authentication and database persistence."
            ],
            'Artificial Intelligence' => [
                'role' => 'AI & Machine Learning Engineer',
                'skills' => 'Python, NumPy, Pandas, Scikit-Learn, TensorFlow, PyTorch, Linear Algebra, SQL',
                'missing' => 'Deep Learning Architectures, LLM Fine-tuning, LangChain, Hugging Face, Vector DBs',
                'roadmap' => "Phase 1 (Months 1-2): Advanced Python, NumPy, Pandas, Data Wrangling, and Statistics.\nPhase 2 (Months 3-4): Classical Machine Learning algorithms and model evaluation metrics.\nPhase 3 (Month 5): Deep Neural Networks, Computer Vision, and Natural Language Processing.\nPhase 4 (Month 6): GenAI architectures, Prompt Engineering, LangChain pipelines, and FastAPI deployment.",
                'advice' => "AI and ML is one of the fastest growing fields. Harness your analytical curiosity to build hands-on predictive models and integrate modern Generative AI APIs into real-world applications."
            ],
            'Data Science' => [
                'role' => 'Data Scientist & Analyst',
                'skills' => 'Python, SQL, Pandas, Matplotlib, Seaborn, Power BI, Statistics, Predictive Modeling',
                'missing' => 'Tableau, Advanced A/B Testing, Cloud Data Warehouses (Snowflake/BigQuery), Feature Engineering',
                'roadmap' => "Phase 1 (Months 1-2): Relational SQL queries, data manipulation, and exploratory data analysis.\nPhase 2 (Months 3-4): Interactive dashboarding with Power BI/Tableau and statistical hypothesis testing.\nPhase 3 (Month 5): Supervised and unsupervised machine learning algorithms.\nPhase 4 (Month 6): End-to-end data pipeline automation and business storytelling.",
                'advice' => "Data Science demands combining strong SQL query optimization with compelling data storytelling. Build a public Kaggle and GitHub repository showcasing business insight dashboards."
            ],
            'Cyber Security' => [
                'role' => 'Cyber Security & SOC Analyst',
                'skills' => 'Networking Protocols (TCP/IP, DNS), Linux, Wireshark, Cryptography, Vulnerability Assessment',
                'missing' => 'SIEM Tooling (Splunk/ELK), Python Automation, PenTesting Tools (Metasploit), CEH Preparation',
                'roadmap' => "Phase 1 (Months 1-2): Networking fundamentals, OS internals (Linux/Windows), and port scanning.\nPhase 2 (Months 3-4): Network traffic inspection with Wireshark and OWASP Top 10 vulnerabilities.\nPhase 3 (Month 5): Incident response, log analysis with Splunk, and threat intelligence.\nPhase 4 (Month 6): Security certification prep (Security+, CEH) and capture-the-flag (CTF) labs.",
                'advice' => "Cyber Security requires relentless curiosity and strong networking foundations. Practice on platforms like TryHackMe and HackTheBox to demonstrate real hands-on triage capability."
            ],
            'Cloud & DevOps' => [
                'role' => 'Cloud & DevOps Engineer',
                'skills' => 'Linux Administration, Bash Scripting, Docker, Git, Networking, AWS / Azure Fundamentals',
                'missing' => 'Kubernetes, Terraform (IaC), CI/CD (GitHub Actions/Jenkins), Monitoring (Prometheus/Grafana)',
                'roadmap' => "Phase 1 (Months 1-2): Linux shell scripting, server administration, and Git workflows.\nPhase 2 (Months 3-4): Containerization with Docker and container orchestration with Kubernetes.\nPhase 3 (Month 5): Infrastructure as Code (Terraform) and automated CI/CD deployment pipelines.\nPhase 4 (Month 6): Cloud certification (AWS Solutions Architect) and observability setup.",
                'advice' => "DevOps engineers bridge software development with cloud reliability. Focus on automating repetitive deployment workflows and managing containerized microservices."
            ]
        ];

        $matchedKey = 'Web Development';
        foreach (array_keys($careers) as $cKey) {
            if (stripos($interest, $cKey) !== false || stripos($cKey, $interest) !== false) {
                $matchedKey = $cKey;
                break;
            }
        }

        $cData = $careers[$matchedKey];
        $matchScore = 82;
        if ($programming === 'Advanced') $matchScore += 8;
        if ($problem === 'High') $matchScore += 5;

        return [
            'career' => $cData['role'],
            'score' => min(96, $matchScore),
            'skills' => $cData['skills'],
            'missing_skills' => $cData['missing'],
            'roadmap' => $cData['roadmap'],
            'ai_advice' => "Hi {$studentName}! " . $cData['advice']
        ];
    }
}
?>
