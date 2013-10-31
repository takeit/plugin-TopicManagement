<?php
/**
 * @package Newscoop\TopicManagementBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\TopicManagementPluginBundle\Services;

use Doctrine\ORM\EntityManager;
use Newscoop\TopicManagementPluginBundle\Entity\UserTopics;
use Newscoop\Entity\User;

/**
 * Topic management service
 */
class TopicManagementService
{   
    private $em;
    
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTopics($articleId) {
        $topics = $this->em->getRepository('Newscoop\Entity\ArticleTopic')
            ->createQueryBuilder('t')
            ->select('t', 'tt')
            ->innerJoin('t.article', 'a', 'WITH', 'a.number = ?1')
            ->leftJoin('t.topic', 'tt')
            ->setParameter(1, $articleId)
            ->getQuery()
            ->getResult();

        return $topics;
    }

    public function getUserTopics($userId) {
        $userTopics = $this->em->getRepository('Newscoop\TopicManagementPluginBundle\Entity\UserTopics')
            ->createQueryBuilder('u')
            ->where('u.user = ?1')
            ->setParameter(1, $userId)
            ->getQuery()
            ->getResult();

        return $userTopics;
    }

    /**
     * Update user topics
     *
     * @param  Newscoop\Entity\User $user
     * @param  array                $topics
     * @return void
     */
    public function updateTopics(User $user, array $topics)
    {
        $repository = $this->em->getRepository('Newscoop\TopicManagementPluginBundle\Entity\UserTopics');
        foreach ($topics as $topicId => $status) {
            $matches = $repository->findBy(array(
                'user' => $user->getId(),
                'topic_id' => $topicId,
            ));

            if ($status === 'false' && !empty($matches)) {
                foreach ($matches as $match) {
                    $this->em->remove($match);
                }
            } else if ($status === 'true' && empty($matches)) {
                $topic = $this->findTopic($topicId);
                if ($topic) {
                    $this->em->persist(new UserTopics($user, $topic));
                }
            }
        }

        $this->em->flush();
    }

    /**
     * Find topic
     *
     * @param  int $id
     * @return Newscoop\Entity\Topic
     */
    public function findTopic($id)
    {
        $topic = $this->em->getRepository('Newscoop\Entity\Topic')
            ->findOneBy(array(
                'id' => (int)$id,
            ));

        if (empty($topic)) {
            return null;
        }

        return $topic;
    }
}