<?php
namespace App\View\Helper;

class InlineStyleHelper extends \Cake\View\Helper
{
    public function afterLayout(\Cake\Event\Event $event)
    {
        $content = $event->subject->Blocks->get('content');
        $parser = new \InlineStyle\InlineStyle();
        libxml_use_internal_errors(true);
        $parser->loadHTML($content);
        libxml_use_internal_errors(false);
        $parser->applyStylesheet($parser->extractStylesheets());
        $content = $parser->getHtml();
        $event->subject->Blocks->set('content', $content);
    }
}
