<?php
/**
 * @package Newscoop\TopicManagementPluginBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Newscoop topic_subscribe block plugin
 *
 * Type:     block
 * Name:     topic_subscribe
 * Purpose:  Displays form to subscribe topic
 *
 * @param string
 *     $params
 * @param string
 *     $p_smarty
 * @param string
 *     $content
 *
 * @return
 *
 */
function smarty_block_topic_subscribe($params, $content, &$smarty, &$repeat)
{
    if (!isset($content)) {
        return '';
    }

    $smarty->smarty->loadPlugin('smarty_shared_escape_special_chars');
    $context = $smarty->getTemplateVars('gimme');
    $html = '';
    $html .= "
    <script type=\"text/javascript\">
    $(document).ready(function() {

        $('#topic-subscribe-content .button').click(function(e) {
            e.preventDefault();
            var context = $(this).closest('.follow-topics-popup');
            var topics = {};
            $('input:checkbox', context).each(function() {
                topics[$(this).attr('value')] = this.checked;
            });

          $.post(\"/topics-management/subscribe-topics\", 
                {'topics': topics, 'format': 'json'}, 
            function(data, textStatus, jqXHR) {
                            $('> *', context).not('h3').hide();
                $('h3', context).after('<p class=\"after\">Successfully subscribed to selected topics.</p>');
                
            }, 'json');
        });

    });
    </script>";
    
    $topicService = \Zend_Registry::get('container')->getService('newscoop_topicmanagement_plugin.service');
    $articleId = 64;//$context->article->articleNumber
    $topics = $topicService->getTopics($articleId);

    $html .= "<a href=\"#topic-subscribe-content\" id=\"follow-topics\" class=\"topic-subscribe topic-subscribe-trigger follow-topics-link\">Subscribe topics</a>";
    $html .= "<div style=\"display:block\"><div class=\"follow-topics-popup popup-box\" id=\"topic-subscribe-content\">
    <h3>Subscribe for topics</h3>";
    if ($topics) {
    $html .= "<p>Please choose from the topics that you want to pursue. Then you will find everything on these topics under your heading \"My Topics\".</p>
    <ul class=\"topics form check-list\">";
        foreach ($topics as $topic) {
            $html .= "<li><input type=\"checkbox\" class=\"topic-to-follow\" id=\"ft_".$topic->getTopic()->getTopicId()."\" name=\"topic[]\" value=\"
                ".$topic->getTopic()->getTopicId()."\" checked=\"checked\" /> <label for=\"ft_".$topic->getTopic()->getTopicId()."\">".$topic->getTopic()->getName()."</label></li>";
        } 

    $html .= "<li class=\"clearfix\"><input type=\"submit\" value=\"Subscribe\" class=\"button right\"></li>";
    } else {
        $html .= "<li class=\"clearfix\">No topics found.</li>";
    }
    $html .= "</ul></div></div>";

    return $html;
}