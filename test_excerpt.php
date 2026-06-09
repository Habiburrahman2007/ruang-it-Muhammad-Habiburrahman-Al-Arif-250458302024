<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$article = App\Models\Article::find(11);
if ($article) {
    $content = $article->content;
    $decoded = \App\Helpers\ContentHelper::decodeRecursively($content);
    $excerpt = \App\Helpers\ContentHelper::excerpt($content, 120);

    echo "Article ID: " . $article->id . "\n";
    echo "Raw DB Content: " . var_export($content, true) . "\n";
    echo "Decoded: " . var_export($decoded, true) . "\n";
    echo "Excerpt: " . var_export($excerpt, true) . "\n";
} else {
    echo "Article ID 11 not found.\n";
}
