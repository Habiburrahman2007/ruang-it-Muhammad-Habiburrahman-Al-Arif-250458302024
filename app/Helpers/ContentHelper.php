<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ContentHelper
{
    /**
     * Generate a preview of HTML content with list processing
     * 
     * This method processes HTML content for preview display by:
     * - Converting ordered lists to numbered text
     * - Converting unordered lists to bulleted text  
     * - Stripping remaining HTML tags
     * - Limiting text length
     * 
     * @param string $content The HTML content to preview
     * @param int $limit Maximum characters for the preview (default: 120)
     * @return string Clean text preview
     * 
     * @example
     * ```php
     * $preview = ContentHelper::preview('<ol><li>Item 1</li></ol><p>Text</p>', 100);
     * // Returns: "1. Item 1 Text" (limited to 100 chars)
     * ```
     */
    public static function preview(string $content, int $limit = 120): string
    {
        $clean = $content;
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

    /**
     * Strip all HTML tags and return plain text
     * 
     * @param string $content HTML content
     * @return string Plain text without HTML tags
     */
    public static function stripTags(string $content): string
    {
        return strip_tags($content);
    }

    /**
     * Get excerpt from content (plain text preview)
     * 
     * @param string $content HTML or plain text content
     * @param int $length Maximum length of excerpt
     * @return string Excerpt text
     */
    public static function excerpt(string $content, int $length = 150): string
    {
        $plain = self::stripTags($content);
        return Str::limit($plain, $length);
    }
}
