<?php

use App\Entity\ProductEntity;
use App\Hydrate\ProductHyd;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductSeeder
 */
class ProductSeeder extends Seeder
{
    private ProductHyd $hyd;

    /**
     * ProductSeeder constructor.
     * @param ProductHyd $hyd
     */
    public function __construct(ProductHyd $hyd)
    {
        $this->hyd = $hyd;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Product');
        $entities = [];

        for ($i = 0; $i < 1000; $i++) {
            $entity = new ProductEntity();

            $entity->setCategory($faker->word())
                ->setProductName($faker->word())
                ->setPrice($faker->numberBetween(1000, 999999))
                ->setDescription($faker->sentence())
                ->setQuantity($faker->numberBetween(1, 1000));

            $entities[] = $entity;
        }

        DB::table('products')
            ->insert(
            $this->hyd->arrayOfEntitiesToArrayOfArrays($entities)
        );
    }
}
