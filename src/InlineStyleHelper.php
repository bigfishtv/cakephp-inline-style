<?php
namespace App\View\Helper;

use Cake\Event\Event;
use Cake\View\View;
use Pelago\Emogrifier\CssInliner;

class InlineStyleHelper extends \Cake\View\Helper
{
    public function afterLayout(Event $event)
    {
        /** @var View */
        $view = $event->getSubject();
        
        $content = $view->fetch('content');

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
        $content = CssInliner::fromHtml($content)->inlineCss($css)->render();
        
        // set new html content 
        $view->assign('content', $content);
    }
}
