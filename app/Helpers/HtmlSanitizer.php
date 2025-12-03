<?php

namespace App\Helpers;

class HtmlSanitizer
{
    /**
     * Sanitize HTML content to prevent XSS attacks
     * Allows only safe HTML tags and attributes
     */
    public static function sanitize($html)
    {
        if (empty($html)) {
            return '';
        }

        // Configuration for allowed tags and attributes
        $config = [
            'allowed_tags' => [
                'p',
                'br',
                'strong',
                'em',
                'u',
                's',
                'span',
                'div',
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'ul',
                'ol',
                'li',
                'a',
                'img',
                'blockquote',
                'code',
                'pre',
                'hr',
                'iframe'
            ],
            'allowed_attributes' => [
                'a' => ['href', 'title', 'target', 'rel'],
                'img' => ['src', 'alt', 'title', 'width', 'height'],
                'span' => ['style'],
                'div' => ['style', 'class'],
                'iframe' => ['src', 'width', 'height', 'frameborder', 'allowfullscreen'],
                'p' => ['style'],
                'h1' => ['style'],
                'h2' => ['style'],
                'h3' => ['style'],
                'h4' => ['style'],
                'h5' => ['style'],
                'h6' => ['style'],
            ],
            'allowed_protocols' => ['http', 'https'],
        ];

        // Load HTML with DOMDocument
        $dom = new \DOMDocument('1.0', 'UTF-8');

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        // Load HTML with UTF-8 encoding
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        // Process all elements
        self::sanitizeNode($dom->documentElement, $config);

        // Return sanitized HTML
        return $dom->saveHTML();
    }

    /**
     * Recursively sanitize DOM nodes
     */
    private static function sanitizeNode($node, $config)
    {
        if (!$node) {
            return;
        }

        // Process child nodes first (reverse order to handle removals)
        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->nodeName);

                // Remove disallowed tags
                if (!in_array($tagName, $config['allowed_tags'])) {
                    $child->parentNode->removeChild($child);
                    continue;
                }

                // Sanitize attributes
                $attributes = [];
                foreach ($child->attributes as $attr) {
                    $attributes[] = $attr->nodeName;
                }

                foreach ($attributes as $attrName) {
                    $allowed = $config['allowed_attributes'][$tagName] ?? [];

                    if (!in_array($attrName, $allowed)) {
                        $child->removeAttribute($attrName);
                        continue;
                    }

                    // Validate URLs in href and src attributes
                    if (in_array($attrName, ['href', 'src'])) {
                        $value = $child->getAttribute($attrName);
                        if (!self::isValidUrl($value, $config['allowed_protocols'])) {
                            $child->removeAttribute($attrName);
                        }
                    }

                    // Sanitize style attribute to prevent CSS-based attacks
                    if ($attrName === 'style') {
                        $style = $child->getAttribute($attrName);
                        $child->setAttribute($attrName, self::sanitizeStyle($style));
                    }
                }

                // Recursively process child elements
                self::sanitizeNode($child, $config);
            }
        }
    }

    /**
     * Validate URL protocols
     */
    private static function isValidUrl($url, $allowedProtocols)
    {
        if (empty($url)) {
            return false;
        }

        // Allow relative URLs
        if (strpos($url, '/') === 0 || strpos($url, '#') === 0) {
            return true;
        }

        // Validate protocol
        $protocol = parse_url($url, PHP_URL_SCHEME);
        return in_array(strtolower($protocol), $allowedProtocols);
    }

    /**
     * Sanitize inline CSS styles
     */
    private static function sanitizeStyle($style)
    {
        // Remove potentially dangerous CSS properties
        $dangerous = ['behavior', 'expression', 'moz-binding', 'javascript', 'import', '@import'];

        foreach ($dangerous as $prop) {
            $style = preg_replace('/' . preg_quote($prop, '/') . '/i', '', $style);
        }

        return $style;
    }
}
