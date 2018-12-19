<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 19/12/2018
 * Time: 10:35
 */

namespace App\DTO;


use App\Entity\Loan;
use App\Entity\ProductTranslation;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    use Translatable;
    /**
     * @Assert\NotBlank(groups={"insertion"})
     * @Assert\Image(maxSize = "2M",minWidth="200", minHeight="200")
     * @var object
     */
    public $image;

    /**
     * @var User owner
     */
    public $owner;

    /**
     * @var Collection
     */
    public $tags;

    /**
     * @var Collection<Loan>
     */
    public $loans;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }
}