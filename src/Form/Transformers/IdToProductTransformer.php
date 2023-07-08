<?php

namespace App\Form\Transformers;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToProductTransformer implements DataTransformerInterface
{
    public function __construct(
        protected ProductRepository $productRepository
    )
    {}

    /**
     * @param Product $value
     * @return string
     */
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    /**
     * @param string $value
     * @return Product|null
     */
    public function reverseTransform(mixed $value): ?Product
    {
        $product = $this->productRepository->find($value);

        if (null === $product) {
            $exception = new TransformationFailedException("Product with id '$value' has not been found");
            $exception->setInvalidMessage(
                "Invalid productId '{{ productId }}'", ['{{ productId }}' => $value]
            );

            throw $exception;
        }

        return $product;
    }
}
