<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->query("SELECT id, title, content FROM articles ORDER BY id DESC LIMIT 5");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $output = "";
    foreach ($articles as $article) {
        $output .= "========================================\n";
        $output .= "ID: " . $article['id'] . "\n";
        $output .= "TITLE: " . $article['title'] . "\n";
        $output .= "CONTENT (raw):\n" . $article['content'] . "\n";
        $output .= "CONTENT (hex):\n" . bin2hex($article['content']) . "\n";
    }
    
    file_put_contents('dump.txt', $output);
    echo "Success!\n";
} catch (Exception $e) {
    file_put_contents('dump.txt', "Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}
