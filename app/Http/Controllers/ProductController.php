<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Image;
use App\Models\Meal;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        foreach($products as $product){
            $image = Image::find($product->image_id);
            $product->img = $image->src;
        }
        return $products;
    }
    public function getMealSelectableProduct($id){
        //Get ID products of the current Category,
        //Query except using those Ids
        $meal = Meal::find($id);
        $meal_products = $meal->products;
        $meal_product_ids = [];
        foreach($meal_products as $meal_product){
            array_push($meal_product_ids, $meal_product->id);
        }
        $products = Product::all()->except($meal_product_ids);
        return $products;
    }
    public function getCategorySelectableProduct($id){
        //Get ID products of the current Category,
        //Query except using those Ids
        $category = Category::find($id);
        $category_products = $category->products;
        $category_product_ids = [];
        foreach($category_products as $category_product){
            array_push($category_product_ids, $category_product->id);
        }
        $products = Product::all()->except($category_product_ids);
        return $products;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Inputs
        //Seperate File name and Extension
        //Create Product
        //Generate Unique File Name concatenate with Product ID
        //Store in storage ('images') and save Image
        //Update Product with Image ID
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'name' => 'required|min:3',
            'price' => 'required|numeric|between:1,99.99'
        ]);
        $file_name = $request->file->getClientOriginalName();
        $file_names = explode('.', $file_name);
        $file_ext = $file_names[count($file_names)-1];

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'created_at' => Carbon::now()->format('Y-m-d'),
            'image_id' => 0
        ]);
        $image_name = str_replace("." . $file_ext, "" ,$file_name);
        $unique_image_name = $image_name . "-" .$product->id . "." . $file_ext;


        //Take Note This will store in storage/app/public/images
        //Dont forget to use php artisan storage:link
        //But this can be access in public/images folder in public folder
        $distination = "public/images";
        $path = $request->file->storeAs($distination, $unique_image_name);

        $newImg = Image::create([
            'src' => $unique_image_name
        ]);

        $product->update([
            'image_id' => $newImg->id
        ]);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    public function productDisplay($id){
        //IMPORTANT NOTE, THIS SHOULD not be deleted in the table and its relation
        //So you should edit in the front end if not it is available or not
        $product = Product::find($id);
        $display = $product->display === 1 ? 0 : 1;
        $product->update([
            'display' => $display,
        ]);
        return response()->json(['res' => 'ok', 'msg' => "Success"]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->hasFile('file')){
            //Find Product
            //Update Product Value
            //Update Image Value
            //Delete Image in storage
            //Save new Image in storage
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'name' => 'required|min:3',
                'price' => 'required|numeric|between:1,99.99'
            ]);
            $product = Product::find($id);
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);
        

            $file_name = $request->file->getClientOriginalName();
            $file_names = explode('.', $file_name);
            $file_ext = $file_names[count($file_names)-1];

            $image_name = str_replace("." . $file_ext, "" ,$file_name);
            $unique_image_name = $image_name . "-" .$product->id . "." . $file_ext;
            

            $image = Image::find($product->image_id);
            $distination = "public/images";
            Storage::delete( $distination.'/'. $image->src);

            $image->update([
                'src' => $unique_image_name
            ]);
        
            //Take Note This will store in storage/app/public/images
            //Dont forget to use php artisan storage:link
            //But this can be access in public/images folder in public folder
            $request->file->storeAs($distination, $unique_image_name);

            self::calculateBooking();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        }else {
            $request->validate([
                'name' => 'required|min:3',
                'price' => 'required|numeric|between:1,99.99'
            ]);
            $product = Product::find($id);
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);
            self::calculateBooking();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            //Detach Meal, Booking, and Delete Image from database and storage public/storage/image
            $image = Image::find($product->image_id);
            $distination = "public/images";
            Storage::delete( $distination.'/'. $image->src);
            $image->delete();
            $bookings = $product->bookings;
            foreach($bookings as $book){
                $temp_book = Booking::find($book->id);
                $temp_book->products()->detach($product->id);
            }
            $meals = $product->meals;
            foreach($meals as $meal){
                $temp_meal = Meal::find($meal->id);
                $temp_meal->products()->detach($product->id);
            }
            $product->delete();
            return response()->json(['res' => 'ok', 'msg' => "Success"]);
        } catch (\Throwable $th) {
            return response()->json(['res' => 'fail', 'msg' => "Something Went Wrong"]);
        }
        
    }
    private function calculateBooking(){
        $bookings = Booking::all();
        foreach($bookings as $book){
            $current_book = Booking::find($book->id);
            $total = 0;
            foreach($current_book->products as $product){
                $total += $product->price * $product->pivot->qty;
            }
            $current_book->total = $total;
        };
    }
}
