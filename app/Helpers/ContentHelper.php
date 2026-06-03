<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ContentHelper
{
    
    public static function decodeRecursively(?string $content): string
    {
        if (is_null($content)) {
            return '';
        }
        $prev = '';
        $curr = $content;
        $depth = 0;
        // Decode recursively up to 5 times or until the string stops changing
        while ($curr !== $prev && $depth < 5) {
            $prev = $curr;
            $curr = html_entity_decode($prev, ENT_QUOTES, 'UTF-8');
            $depth++;
        }
        return $curr;
    }

    public static function preview(string $content, int $limit = 120): string
    {
        $clean = self::decodeRecursively($content);
        $clean = preg_replace_callback(
            '/<ol>(.*?)<\/ol>/s',
            function ($matches) {
                preg_match_all('/<li>(.*?)<\/li>/s', $matches[1], $items);
                $result = '';
                foreach ($items[1] as $i => $text) {
                    $result .= ($i + 1) . '. ' . strip_tags($text) . ' ';
                }
                return $result;
            },
            $clean
        );

        $clean = preg_replace('/<ul>(.*?)<\/ul>/s', '', $clean);
        $clean = preg_replace('/<li>(.*?)<\/li>/s', '• $1 ', $clean);
        return Str::limit(strip_tags($clean, '<b><strong><i><em><s><strike><del>'), $limit);
    }

    
    public static function stripTags(string $content): string
    {
        return strip_tags(self::decodeRecursively($content));
    }

    
    public static function excerpt(string $content, int $length = 150): string
    {
        
        return strip_tags(self::decodeRecursively($content), '<b><strong><i><em><s><strike><del><u><mark><sub><sup><span><br>');
    }
}
