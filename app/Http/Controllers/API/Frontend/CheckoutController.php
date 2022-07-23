<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //add

class CheckoutController extends Controller
{
    public function place_order(Request $request)
    {
        if (auth('sanctum')->check()) { //authantication checked
            //validation
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'phone' => 'required|max:191',
                'email' => 'required|max:191|email',
                'address' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|max:191',
            ]);

            //post fail or post Database
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ]);
            } else {
                $user_id = auth('sanctum')->user()->id; //auth 

                $order = new Order;
                $order->user_id = $user_id;
                $order->firstname = $request->input('firstname');
                $order->lastname = $request->input('lastname');
                $order->phone = $request->input('phone');
                $order->email = $request->input('email');
                $order->address = $request->input('address');
                $order->city = $request->input('city');
                $order->state = $request->input('state');
                $order->zipcode = $request->input('zipcode');
                //$order->payment_mode = "COD";
                $order->payment_mode = $request->input('payment_mode');
                $order->payment_id = $request->input('payment_id');
                $order->tracking_no = 'fundaecom' . rand(1111, 9999);
                $order->save();

                //CartItems
                $cart = Cart::where('user_id', $user_id)->get();
                $orderItems = [];
                foreach ($cart as $item) {
                    $orderItems[] = [
                        'product_id' => $item->product_id,
                        'qty' => $item->product_qty,
                        'price' => $item->product->selling_price,
                    ];

                    //decrecing Quantity from Product table
                    $item->product->update([ // Product=quantity, checkout front=product_qty
                        'quantity' => $item->product->quantity - $item->product_qty //joined
                    ]);
                }

                //Cart Items deleted
                $order->order_items()->createMany($orderItems); //joined
                Cart::destroy($cart);

                return response()->json([
                    'status' => 200,
                    'message' => 'Order Placed Successfully'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, Login to Continue',
            ]);
        }
    }


    //===========validate_order==========================
    public function validate_order(Request $request)
    {
        if (auth('sanctum')->check()) { //authantication checked
            //validation
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'phone' => 'required|max:191',
                'email' => 'required|max:191|email',
                'address' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|max:191',
            ]);

            //post fail or post Database
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ]);
            }else{
                return response()->json([
                    'status' => 200,
                    'message' => 'Form Validated Successfully'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, Login to Continue',
            ]);
        }
    }
}


//auth checked
/**
 *         if (auth('sanctum')->check()) { 
        
     
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please, Login to View Cart',
            ]);
        }
    }
 */
