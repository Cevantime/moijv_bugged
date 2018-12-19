<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 19/12/2018
 * Time: 10:52
 */

namespace App\Factory;


use App\DTO\ProductDTO;
use App\DTO\ProductDTOTranslation;
use App\Entity\Product;
use App\Entity\ProductTranslation;
use Symfony\Component\HttpFoundation\File\File;

class ProductFactory
{
    /**
     * @param Product $product
     * @return ProductDTO
     */
    public function productToDTO(?Product $product = null)
    {

        $productDTO = new ProductDTO();
        if($product === null) {
            return $productDTO;
        }

        $productDTO->image = new File($product->getImage());
        $productDTO->loans = $product->getLoans();
        $productDTO->owner = $product->getOwner();
        $productDTO->tags = $product->getTags();
        foreach ($product->getTranslations() as $productTranslation){
            $productDTOTranslation = new ProductDTOTranslation();
            $productDTOTranslation->setLocale($productTranslation->getLocale());
            $productDTOTranslation->title = $productTranslation->getTitle();
            $productDTOTranslation->description = $productTranslation->getDescription();
            $productDTO->addTranslation($productDTOTranslation);
        }

        return $productDTO;
    }

    public function DTOToProduct(ProductDTO $productDTO, Product $product = null)
    {
        if($product === null) {
            $product = new Product();
        }
        if($productDTO->image !== null) {
            $product->setImage($productDTO->image->getPath() .'/'. $productDTO->image->getFileName());
        }
        $product->setLoans($productDTO->loans);
        $product->setOwner($productDTO->owner);
        $product->setTags($productDTO->tags);
        foreach ($productDTO->getTranslations() as $productDTOTranslation ) {
            $productTranslation = $product->translate($productDTOTranslation->getLocale(), false);
            $productTranslation->setTitle($productDTOTranslation->title);
            $productTranslation->setDescription($productDTOTranslation->description);
        }

        return $product;
    }
}