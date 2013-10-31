<?php
/**
 * @package Newscoop\TopicManagementPluginBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\TopicManagementPluginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{
    /**
     * @Route("/topics-management/subscribe-topics")
     */
    public function subscribeTopics(Request $request)
    {
        try {
            $user = $this->container->getService('user')->getCurrentUser();
            $this->container->getService('newscoop_topicmanagement_plugin.service')
                ->updateTopics($user, $request->get('topics'));
            return new Response(json_encode(array('status' => true)));
        } catch (\Exception $e) {
            return new Response(json_encode(array('error' => $e->getMessage())));
        }
    }
}
