<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
     public function category()
     {
        $category = Categories::where('status', '0')->get();//0=show
        return response()->json([
            'status' => 200,
            'category' => $category
        ]);
     }

     //================Product page==================
     public function product($slug)//product_slug = slug
     {
        $category = Categories::where('slug', $slug)->where('status', '0')->first();//0=show
       if($category)
       {
        $product = Product::where('category_id', $category->id)->where('status', '1')->get();//1=show
        if($product)
        {
            return response()->json([
                'status' => 200,
                'product_data' => [
                    'product' => $product,
                    'category' => $category,
                ]
            ]);
        }
        else{
            return response()->json([ //not found
                'status' => 400,
                'message' => "No Product Available",
            ]);
        }
       }
       else
       {
        return response()->json([ //not found
            'status' => 404,
            'message' => "No Such Category Found",
        ]);
       }
     }

     //========================productDetails==========================
     public function productDetails($category_slug, $product_slug)
     {
        $category = Categories::where('slug', $category_slug)->where('status', '0')->first();//0=show
       if($category)
       {
        $product = Product::where('category_id', $category->id)
                          ->where('slug', $product_slug)
                          ->where('status', '1')->first();//1=show
        if($product)
        {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        }
        else{
            return response()->json([ //not found
                'status' => 400,
                'message' => "No Product Available",
            ]);
        }
       }
       else
       {
        return response()->json([ //not found
            'status' => 404,
            'message' => "No Such Product Found",
        ]);
       }
     }
}
