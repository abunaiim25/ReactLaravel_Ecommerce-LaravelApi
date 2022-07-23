<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //================addToCart===============================
    public function addToCart(Request $request)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            $productCheck = Product::where('id', $product_id)->first();
            if ($productCheck) {
                if (Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()) {
                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name . ' Already Added to Cart',
                    ]);
                } else {
                    $cartitem = new Cart;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->product_qty = $product_qty;
                    $cartitem->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Added to Cart Successfully',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Add to Cart',
            ]);
        }
    }


    //================View Cart==================
    public function cart_view_item()
    {
        if (auth('sanctum')->check()) { //authantication checked
            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('user_id', $user_id)->get();

            return response()->json([
                'status' => 200,
                'cart' => $cartItems,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, Login to View Cart',
            ]);
        }
    }

    //===================update_quantity=====================
    public function update_quantity($cart_id, $scope)
    {
        if (auth('sanctum')->check()) { //authantication checked
            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();
            if ($scope == 'inc') {
                $cartItems->product_qty += 1;
            } else if ($scope == 'des') {
                $cartItems->product_qty -= 1;
            }
            $cartItems->update();

            return response()->json([
                'status' => 200,
                'message' => 'Quantity Updated',
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Continue',
            ]);
        }
    }

    //================= destroy page by Id ========================
    public function destroy_cart($cart_id)
    {
        if (auth('sanctum')->check()) { //authantication checked
            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();
            if ($cartItems) {

                $cartItems->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Cart Item Removed Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cart Item Not Found'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Continue',
            ]);
        }
    }
}
