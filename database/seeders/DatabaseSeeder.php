<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => "user"
        ]);
        Role::create([
            'name' => "admin"
        ]);
        Role::create([
            'name' => "employee"
        ]);
      
        $number_of_product = 10;
        $number_of_category = 5;
        $number_of_meal = 4;
        $number_of_table = 5;
        $number_of_seat = [1,2,3,5,10];
        $number_of_image = $number_of_product;

        \App\Models\User::factory(35)->create();
        \App\Models\Product::factory($number_of_product)->create();

        for ($i=1; $i <= $number_of_image; $i++) { 
            Image::create([
                'src' => 'restaurant-logo-'.$i.'.png'
            ]);
        }
        
        //Product Image
        for ($i=1; $i <= $number_of_product; $i++) { 
            $product = \App\Models\Product::find($i);
            $product->image_id = $i;
            $product->save();
        }
        //Generate Category
        for ($i=1; $i <= $number_of_category; $i++) { 
            \App\Models\Category::create([
                'name' => "Category ".$i 
            ]);
        }
        
        //Category_Product Seeder
        for ($i=1; $i <= $number_of_product; $i++) {
            $category_size_id = rand(1,$number_of_category);
            $product = \App\Models\Product::find($i);
            $category_random_id_array = array(rand(1, $category_size_id),rand(1, $category_size_id));
            $product->categories()->sync($category_random_id_array);
        }
        //Generate Meal
        $temp_img_id = $number_of_product+1;
        for ($i=1; $i <= $number_of_meal; $i++) { 
            \App\Models\Meal::create([
                'name' => "Meal ".$i 
            ]);
            $temp_img_id++;
        }
        //Generate Meal_products
        for ($meal_index=1; $meal_index <= $number_of_meal; $meal_index++) { 
            $meal = \App\Models\Meal::find($meal_index);
            $random_product_array= [];
            $product_number= rand(1, $number_of_product);
            for ($product_id=1; $product_id <= $product_number; $product_id++) { 
               array_push($random_product_array, rand($product_id, $product_number));
            }
            $meal->products()->sync($random_product_array);
        }
        $seat_index = 0;
        $table_type = ['dinner', 'lunch', 'breakfast'];
        for ($table_index=0; $table_index < $number_of_table ; $table_index++) { 
            \App\Models\Table::create([
                'name' => 'Table ' .($table_index + 1),
                'type' => $table_type[rand(0,2)],
                'status' => true,
                'created_at' => Carbon::now()->format('Y-m-d'),
                'number_of_seats' => $number_of_seat[$seat_index]
            ]);
            if($seat_index >= count($number_of_seat)){
                $seat_index = 0;
            }
            $seat_index++;
            
        }
        //Booking
        //Take note of product and meal number
        $number_of_booking = 20;
        \App\Models\Booking::factory($number_of_booking)->create();
        //Tables and Product for Booking
        for ($i=1; $i <= $number_of_booking; $i++) { 
            //Attaching random table
            $booking = \App\Models\Booking::find($i);
            $booking->tables()->attach(rand(1, $number_of_table));
            //Attaching random product
            $total = 0;
            for ($product_index=0; $product_index < rand(1,5); $product_index++) { 
                //Add random qty in pivot
                $random_product_id = rand(1, $number_of_product);
                $product = \App\Models\Product::find($random_product_id);
                $qty = rand(1,3);
                $booking->products()->attach($product, ['qty'=> $qty]);

                //Setting Price
                //Flooring to two decimal places
                $product_total = (($product->price * $qty) *100)/100;
                $total += $product_total;
            }
            $booking->total = ($total*100)/100;
            $booking->save();
        }
    }
}
