<?php

namespace App\Tests\Unit;

use App\DataTransformers\TagTransformer;
use App\Entity\Tag;
use App\Kernel;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TagTransformerTest extends KernelTestCase
{
    public function testTagTransform()
    {
        self::bootKernel();

        $tagTransformer = self::$container->get(TagTransformer::class);

        $tagCollection = new ArrayCollection();

        $tags = ['toto', 'foo', 'bar'];

        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $tag->setSlug($tagName);
            $tagCollection->add($tag);
        }

        $tagsTransformed = $tagTransformer->transform($tagCollection);

        $this->assertTrue($tagsTransformed === 'toto,foo,bar');
    }
}
