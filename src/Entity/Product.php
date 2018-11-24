<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("title")
 */
class Product
{
    use ORMBehaviors\Translatable\Translatable;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"insertion"})
     * @Assert\Image(maxSize = "2M",minWidth="200", minHeight="200")
     * @var object
     */
    private $image;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @var User owner
     */
    private $owner;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="products",cascade="persist")
     * @var Collection
     */
    private $tags;
    
    /**
     * @ORM\OneToMany(targetEntity="Loan", mappedBy="product")
     * @var Collection<Loan>
     */
    private $loans;
    
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }
    
    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner)
    {
        $this->owner = $owner;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getImage() : string
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag($tag)
    {
        if($this->tags->contains($tag)){
            return;
        }
        $this->tags->add($tag);
        $tag->getProducts()->add($this);
    }
    
    public function setTags(Collection $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function setLoans(Collection $loans)
    {
        $this->loans = $loans;
        return $this;
    }

    public function isLoaned()
    {
        foreach ($this->loans as $loan) {
            if($loan->getDateEnd() > date('Y-m-d H:i:s')) {
                return true;
            }
        }

        return false;
    }

    public function __call($method, $arguments)
    {
        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }

}
