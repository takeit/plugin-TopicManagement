<?php
/**
 * @package Newscoop\TopicManagementPluginBundle
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\TopicManagementPluginBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Newscoop\Entity\Topic;
use Newscoop\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="plugin_topicmanagement_user_topics")
 */
class UserTopics
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Newscoop\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="Id")
     * @var Newscoop\Entity\User
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="topic_id")
     * @var int
     */
    private $topic_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="topic_language")
     * @var int
     */
    private $topic_language;

    /**
     * @ORM\ManyToOne(targetEntity="Newscoop\Entity\Topic")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="topic_id", referencedColumnName="fk_topic_id"),
     *      @ORM\JoinColumn(name="topic_language", referencedColumnName="fk_language_id")
     *  })
     * @var Newscoop\Entity\Topic
     */
    private $topic;


    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var datetime
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     * @var boolean
     */
    private $is_active;

    /**
     * @param Newscoop\Entity\User $user
     * @param Newscoop\Entity\Topic $topic
     */
    public function __construct(User $user, Topic $topic)
    {
        $this->user = $user;
        $this->topic = $topic;
        $this->topic_id = $topic->getTopicId();
        $this->topic_language = $topic->getLanguageId();
        $this->setCreatedAt(new \DateTime());
        $this->setIsActive(true);
    }

    /**
     * Get topic
     *
     * @return Newscoop\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set status
     *
     * @param boolean $is_active
     * @return boolean
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
        
        return $this;
    }
    
    /**
     * Get create date
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set create date
     *
     * @param datetime $created_at
     * @return datetime
     */
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;
        
        return $this;
    }
}
