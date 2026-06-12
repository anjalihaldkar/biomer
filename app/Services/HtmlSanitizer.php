<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    private static ?HTMLPurifier $purifier = null;

    public static function clean(?string $html): string
    {
        return self::purifier()->purify((string) $html);
    }

    private static function purifier(): HTMLPurifier
    {
        if (self::$purifier instanceof HTMLPurifier) {
            return self::$purifier;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set(
            'HTML.Allowed',
            'p,br,strong,b,em,i,u,ul,ol,li,blockquote,h2,h3,h4,' .
            'a[href|title],img[src|alt|width|height],figure,figcaption,span,' .
            'table,thead,tbody,tr,th,td'
        );
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
        ]);
        $config->set('HTML.SafeIframe', false);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('HTML.DefinitionID', 'bharat-biomer-content');
        $config->set('HTML.DefinitionRev', 1);

        $cachePath = storage_path('framework/cache/htmlpurifier');
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0755, true);
        }
        $config->set('Cache.SerializerPath', $cachePath);

        $definition = $config->maybeGetRawHTMLDefinition();
        if ($definition !== null) {
            $definition->addElement('figure', 'Block', 'Flow', 'Common');
            $definition->addElement('figcaption', 'Block', 'Flow', 'Common');
        }

        return self::$purifier = new HTMLPurifier($config);
    }
}
