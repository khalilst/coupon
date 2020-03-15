<?php

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, rand(2, 10))->create()
            ->each(function ($category) {
                $brands = factory(Brand::class, rand(2, 10))->make();

                $category->brands()->createMany($brands->toArray());
            });
    }
}
