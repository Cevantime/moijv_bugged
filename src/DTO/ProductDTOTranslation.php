<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 19/12/2018
 * Time: 12:43
 */

namespace App\DTO;

use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTOTranslation
{
    use Translation;

    /**
     * @Assert\Length(min=3, max=50)
     */
    public $title;

    /**
     * @Assert\Length(min=15,max=65000)
     */
    public $description;

    public function setId($id)
    {
        $this->id = $id;
    }
}