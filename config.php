<?php
// ==========================================
// PENGATURAN PATH & DIREKTORI
// ==========================================
define('BASE_DIR', 'C:/laragon/www/chatbot/');

// ==========================================
// PENGATURAN API AI (GOOGLE GEMINI)
// ==========================================
// Endpoint resmi untuk Google Gemini
define('API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/');

// Paste API Key Gemini Anda dari Google AI Studio di sini
define('API_KEY', 'AQ.Ab8RN6IloYUpKRZ6xvSs_TfFdDILV0oOR4Y75jlnE2j2w2b9SQ');

// ==========================================
// PENGATURAN KARAKTER CHATBOT
// ==========================================
define('SYSTEM_PROMPT', "Kamu adalah asisten travel ahli untuk liburan hemat atau 'healing murah'. 
Gaya bahasamu santai, asik, dan menggunakan bahasa gaul anak muda secukupnya. 
Kamu jago memberikan rekomendasi wisata alam, pantai tersembunyi, atau rute riding motor/touring yang seru yang ramah di kantong.");

// Gunakan model Gemini 1.5 Flash
define('AI_MODEL', 'gemini-2.5-flash');
define('AI_TEMPERATURE', 1);
define('AI_MAX_TOKENS', 1000);
?>