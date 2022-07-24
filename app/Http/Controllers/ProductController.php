<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Image;
use App\Models\Meal;
use App\Models\Product;
use Carbon\Carbon;
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
        //
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
}
