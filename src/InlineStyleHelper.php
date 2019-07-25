<?php

namespace App\View\Helper;

class InlineStyleHelper extends \Cake\View\Helper
{
    public function afterLayout(\Cake\Event\Event $event)
    {
        $content = $event->subject->Blocks->get('content');

        // replace stylesheet links with style tags
        $content = preg_replace_callback('/<link (.+?)>/', function ($matches) {
            if (preg_match('/href="(.+?)"/', $matches[1], $matches2)) {
                return '<style type="text/css">' . file_get_contents($matches2[1]) . '</style>';
            }
            return '';
        }, $content);

        // extract css from style tags
        $css = '';
        if (preg_match_all('/<style[^>]*>(.+?)<\/style>/is', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $style) {
                $css .= $style[1];
            }
        }

        // strip css comments
        $content = preg_replace('#/\*(.+?)\*/#s', '', $content);

        // apply inline styles to css
        $emogrifier = new \Pelago\Emogrifier($content, $css);
        $emogrifier->disableInvisibleNodeRemoval();
        $emogrifier->disableStyleBlocksParsing();

        // set new html content 
        $content = $emogrifier->emogrify();
        $event->subject->Blocks->set('content', $content);
    }
}
