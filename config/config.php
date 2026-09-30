<?php
/**
 * CareerConnect AI - Application Configuration & Environment Loader
 */

// Function to load .env file
if (!function_exists('loadEnv')) {
    function loadEnv($path = null) {
        if ($path === null) {
            $possiblePaths = [
                __DIR__ . '/../.env',
                __DIR__ . '/../../.env',
                dirname(__DIR__) . '/.env',
                dirname(dirname(__DIR__)) . '/.env',
                ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/AI/.env',
                ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/AI/CareerConnectAI/.env'
            ];
            foreach ($possiblePaths as $p) {
                if (file_exists($p)) {
                    $path = $p;
                    break;
                }
            }
        }

        if ($path && file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || strpos($line, '#') === 0) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    [$name, $value] = explode('=', $line, 2);
                    $name = trim($name);
                    $value = trim($value, " \t\n\r\0\x0B\"'");
                    if (!isset($_ENV[$name])) {
                        $_ENV[$name] = $value;
                        putenv("$name=$value");
                    }
                }
            }
        }
    }
}

loadEnv();

// Configuration Constants & Variables
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? 'root';
$db_pass = $_ENV['DB_PASS'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? 'careerconnect';

$gemini_api_key = $_ENV['GEMINI_API_KEY'] ?? '';

// App metadata
if (!defined('APP_NAME')) define('APP_NAME', 'CareerConnect AI');
if (!defined('APP_TAGLINE')) define('APP_TAGLINE', 'Your Career, Our Guidance');
if (!defined('APP_YEAR')) define('APP_YEAR', date('Y'));

// Auto-detect project base URL dynamically for root & subfolder execution
if (!defined('BASE_URL')) {
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';
    $projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)));
    
    if ($docRoot && strpos($projectRoot, $docRoot) === 0) {
        $sub = trim(substr($projectRoot, strlen($docRoot)), '/');
        $baseUrl = $sub ? '/' . $sub . '/' : '/';
    } else {
        // Fallback standard XAMPP path
        $baseUrl = '/AI/CareerConnectAI/';
    }
    define('BASE_URL', $baseUrl);
}

// URL & Asset Helpers
if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path = '') {
        return BASE_URL . 'assets/' . ltrim($path, '/');
    }
}
?>