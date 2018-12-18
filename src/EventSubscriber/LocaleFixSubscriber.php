<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 18/12/2018
 * Time: 16:22
 */

namespace App\EventSubscriber;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;

class LocaleFixSubscriber implements EventSubscriber
{
    private $classAnalyzer;

    public function __construct(ClassAnalyzer $classAnalyzer)
    {
        $this->classAnalyzer = $classAnalyzer;
    }

    /**
     * @return ClassAnalyzer
     */
    public function getClassAnalyzer(): ClassAnalyzer
    {
        return $this->classAnalyzer;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $classMetadata = $event->getClassMetadata();
        if (($this->getClassAnalyzer()->hasTrait($classMetadata->reflClass, 'Knp\DoctrineBehaviors\Model\Translatable\Translation'))
            &&!($classMetadata->hasField('locale') || $classMetadata->hasAssociation('locale'))) {
            $classMetadata->mapField(array(
                'fieldName' => 'locale',
                'type'      => 'string',
                'length' => 50
            ));
        }
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata
        ];
    }
}