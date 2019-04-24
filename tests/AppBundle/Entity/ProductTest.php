<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @test
     * @group Product
     * @dataProvider pricesForFoodProduct
     */
    public function testComputeTVAFoodProduct($price, $expectedTva)
    {
        $product = new Product('Un produit', Product::FOOD_PRODUCT, $price);

        $this->assertSame($expectedTva, $product->computeTVA());
    }

    public function pricesForFoodProduct()
    {
        return [
            [0, 0.0],
            [20, 1.1],
            [100, 5.5]
        ];
    }

    /**
     * @test
     * @group Product
     */
    public function testComputeTVAOtherProduct()
    {
        $product = new Product('Un autre produit', 'Un autre type de produit', 20);

        $this->assertSame(3.92, $product->computeTVA());
    }

    /**
     * @test
     * @group Product
     */
    public function testNegativePriceComputeTVA()
    {
        $product = new Product('Un produit', Product::FOOD_PRODUCT, -20);

        $this->setExpectedException('LogicException');

        $product->computeTVA();
    }
}