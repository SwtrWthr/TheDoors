<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    public function index()
    {
        return response()->json([
          'orders'=>Order::all(),
          'status'=>true
        ]);
    }

    public function store(Request $request)
    {
      $data = [
        'user_id' => 'required',
        'cart_id' => 'required',
        'type' => 'required|numeric',
      ];

      $validator = Validator::make($request->all(), $data);

      if ($validator->fails()) {
        return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
      }

      $order = new Order();
      $order->user_id = $request->user_id;
      $order->cart_id = $request->cart_id;
      $order->type = $request->type;
      $order->save();

      return response()->json([
        'status'=>true,
        'order'=>$order
      ], 201);
    }

    public function show($id)
    {
      if ($order = Order::find($id)) {
        $order = Order::with('cart', 'user')->findOrFail($id);
        return response()->json([
          'status' => true,
          'order' => $order
        ]);
      } else {
        return response()->json([
          'status' => false
        ]);
      }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
