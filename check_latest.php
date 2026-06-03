<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->query("SELECT id, title, content FROM articles ORDER BY id DESC LIMIT 5");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($articles as $article) {
        echo "========================================\n";
        echo "ID: " . $article['id'] . "\n";
        echo "TITLE: " . $article['title'] . "\n";
        echo "CONTENT: " . var_export($article['content'], true) . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
