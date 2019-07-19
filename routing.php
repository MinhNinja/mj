<?php
/**
 * @package mj - PHP mini applciation for fast integration and implement
 * @version 0.1
 * @author Pham Minh
 * @website http://minh.ninja
 * @github
 * 
 */

namespace mj;

use mj\libraries\application as App;
use mj\config;

class routing{

    public static function execute(){

        $found = 0;

        // The current page URL
        $uri = App::use('env')->getCurrentUriPath();
        $sitemap = App::use('sm')->getMap();

        // Loop all routes
        foreach ($sitemap as $node) {
            // Replace all curly braces matches {} into word patterns (like Laravel)
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $node->getRegSlug());

            /*var_dump(
                 '#^' . $pattern . '$#', $uri, 
                preg_match_all('#^' . $pattern . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)
        
        );*/

            // we have a match!
            if (preg_match_all('#^' . $pattern . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)) {
                // Rework matches to only contain the matches, not the orig string
                $matches = array_slice($matches, 1);

                // Extract the matched URL parameters (and only the parameters)
                $params = array_map(function ($match, $index) use ($matches) {

                    // We have a following parameter: take the substring from the current param position until the next one's position (thank you PREG_OFFSET_CAPTURE)
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                    } // We have no following parameters: return the whole lot

                    return isset($match[0][0]) ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $tmp = [
                    'app' => APP_NAME,
                    'task' => $node->getTask(),
                ];

                $node->mapInput($tmp, $params);

                App::use('input')->setUrlVar($tmp);

                ++$found;

                // If we need to quit, then quit
                if ($found) {
                    break;
                }
            }
        }

        // not found, raise error
        if(!$found){
            App::use('ss')->set('_msg', '404 - Page not found');
            App::use('input')->setUrlVar([
                'app' => APP_NAME,
                'task' => 'displayError',
            ]);
        }
        
        App::process();

    }
}