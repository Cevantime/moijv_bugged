<?php


namespace App\DataTransformers;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    
    /**
     *
     * @var TagRepository
     */
    private $tagRepo;
    
    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }
    
    public function reverseTransform($tagString)
    {
        $tagArray = array_unique(explode(',', $tagString));
        $tagCollection = new ArrayCollection();
        foreach($tagArray as $tagName) {
            $tag = $this->tagRepo->getCorrespondingTag($tagName);
            $tagCollection->add($tag);
        }
        return $tagCollection;
    }

    public function transform($tagCollection)
    {
        // array_map (function, array)
        $tagArray = $tagCollection->toArray();
        $nameArray = array_map(function($tag){ return $tag->getName(); }, $tagArray);
//        foreach ($tagArray as $tag)
//        {
//            $nameArray[] = $tag->getName();
//        }
        return implode(',', $nameArray);
    }

}
