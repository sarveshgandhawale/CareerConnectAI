<?php
header('Content-Type: application/json; charset=utf-8');
http_response_code(404);
echo json_encode(['success' => false, 'error' => 'Chatbot endpoint has been disabled.']);
exit();
