<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$article = App\Models\Article::latest()->first();
if ($article) {
    echo "ID: " . $article->id . "\n";
    echo "TITLE: " . $article->title . "\n";
    echo "CONTENT (raw):\n";
    echo $article->content . "\n";
    echo "CONTENT (hex):\n";
    echo bin2hex($article->content) . "\n";
    echo "CONTENT (var_dump):\n";
    var_dump($article->content);
} else {
    echo "No articles found.\n";
}
