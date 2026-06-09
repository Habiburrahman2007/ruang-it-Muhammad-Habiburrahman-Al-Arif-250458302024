<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $db->query("SELECT id, title, content FROM articles ORDER BY id DESC");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($articles as $article) {
        echo "ID: " . $article['id'] . " | TITLE: " . $article['title'] . "\n";
        echo "CONTENT: " . substr($article['content'], 0, 100) . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
