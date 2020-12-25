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
      'orders' => Order::with('user', 'cart')->get(),
      'status' => true
    ]);
  }

  public function store(Request $request)
  {
    $data = [
      'user_id' => 'required',
      'type' => 'required|numeric',
    ];

    $validator = Validator::make($request->all(), $data);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    $order = new Order();
    $order->user_id = $request->user_id;
    $order->type = $request->type;
    $order->status = 'В ожидании';
    $order->total_sum = $request->total_sum;
    $order->save();

    return response()->json([
      'status' => true,
      'order' => $order
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
    $order = Order::find($id);

    if (isset($order->id)) {
      $order->status = $request->status;
      $order->save();
      return response()->json([
        'status' => true,
        'order' => $order
      ], 200);
    } else {
      return response()->json([
        'status' => false,
        'message' => 'Не найдено!'
      ], 200);
    }
  }

  public function destroy($id)
  {
    $order = Order::find($id);
    $order->delete();
    if (!Order::find($id)) {
      return response()->json([
        'status' => true
      ], 200);
    } else {
      return response()->json([
        'status' => false
      ], 204);
    }
  }
}
