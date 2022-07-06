<?php

namespace App\Http\Controllers\API;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\File; //for delete image
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //===========view category on product page================
    public function all_category()
    {
        $category = Categories::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }

    //=================Store on DB========================
    public function store(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'meta_title' => 'required|max:191',
            'selling_price' => 'required|max:20',
            'orginal_price' => 'required|max:20',
            'quantity' => 'required|max:4',
            //'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'brand' => 'required|max:20',
        ]);

        //post fail or post Database
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = new Product();

            $product->category_id = $request->input('category_id');
            $product->slug = $request->input('slug');
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->meta_title = $request->input('meta_title');
            $product->meta_keywords = $request->input('meta_keywords');
            $product->meta_description = $request->input('meta_description');
            $product->selling_price = $request->input('selling_price');
            $product->orginal_price = $request->input('orginal_price');
            $product->quantity = $request->input('quantity');
            $product->brand = $request->input('brand');
            $product->status = $request->input('status');
            $product->popular = $request->input('popular');
            $product->featured = $request->input('featured');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('img_DB/product/', $filename);
                $product->image = 'img_DB/product/' . $filename;
            }
            $product->save();

            return response()->json([
                'status' => 200,
                'message' => 'Product Added Successfully'
            ]);
        }
    }


    //=================View Product========================
    public function view()
    {
        $product = Product::all();
        return response()->json([
            'status' => 200,
            'product' => $product,
        ]);
    }


    //================= Edit page by Id ========================
    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        } else {
            return response()->json([ //not found
                'status' => 404,
                'message' => "No Product Id Found",
            ]);
        }
    }

    //===============Update by Id==================
    public function update(Request $request, $id)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'meta_title' => 'required|max:191',
            'selling_price' => 'required|max:20',
            'orginal_price' => 'required|max:20',
            'quantity' => 'required|max:4',
            'brand' => 'required|max:20',
        ]);

        //post fail or post Database
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = Product::find($id);
            if ($product) {
                $product->category_id = $request->input('category_id');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->description = $request->input('description');
                $product->meta_title = $request->input('meta_title');
                $product->meta_keywords = $request->input('meta_keywords');
                $product->meta_description = $request->input('meta_description');
                $product->selling_price = $request->input('selling_price');
                $product->orginal_price = $request->input('orginal_price');
                $product->quantity = $request->input('quantity');
                $product->brand = $request->input('brand');
                $product->status = $request->input('status');
                $product->popular = $request->input('popular');
                $product->featured = $request->input('featured');

                if ($request->hasFile('image')) {
                    $path = $product->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $ext = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $ext;
                    $file->move('img_DB/product/', $filename);
                    $product->image = 'img_DB/product/' . $filename;
                }
                $product->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Product Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Product Id Found'
                ]);
            }
        }
    }

     //================= destroy page by Id ========================
     public function destroy($id)
     {
         $product = Product::find($id);
         if ($product) {
            //image delete
            if($product -> image) {
                $path = $product->image;
                if(File::exists($path))//avobe import class
                {
                    File::delete($path);
                }
            }
             $product->delete();

             return response()->json([
                 'status' => 200,
                 'message' => 'Product Deleted Successfully'
             ]);
         }
         else
         {
             return response()->json([
                 'status' => 404,
                 'message' => 'No Product Id Found'
             ]);
         }
     }
}
