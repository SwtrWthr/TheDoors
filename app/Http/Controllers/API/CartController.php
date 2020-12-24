<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

  public function add_to_cart(Request $request)
  {
    $data = [
      'user_id' => 'required',
      'door_id' => 'required',
      'quantity' => 'required|numeric|min:1',
    ];

    $validator = Validator::make($request->all(), $data);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    $cart = Cart::where('user_id', $request->user_id)->where('door_id', $request->door_id)->first();

    if($cart) {
      $cart->quantity += $request->quantity;
      $cart->save();
    } else {
      $cart = new Cart();
      $cart->user_id = $request->user_id;
      $cart->door_id = $request->door_id;
      $cart->quantity = $request->quantity;
      $cart->save();
    }

    return response()->json([
      'status'=>true,
      'cart'=>$cart
    ], 201);
  }

  public function delete_from_cart($id) {
    $cart = Cart::find($id);

    if(!isset($cart->id)) {
      return response()->json([
        'status'=>false,
        'message'=>'Не найдено!'
      ], 200);
    }

    $cart->delete();
    if (!Cart::find($id)) {
      return response()->json([
        'status' => true
      ], 200);
    } else {
      return response()->json([
        'status' => false,
        'message' => 'Не удалено!'
      ], 204);
    }
  }

}
