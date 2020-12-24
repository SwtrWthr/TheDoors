<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
  public function index()
  {
    return Brand::all();
  }

  public function brandCount($id)
  {

  }

  public function store(Request $request)
  {
    $data = [
      'name' => 'required|max:120'
    ];

    $validator = Validator::make($request->all(), $data);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    $brand = new Brand();
//    $contents = file_get_contents($request->image);
//    error_log($contents);
//    Log::info($contents);
//    $image = $request->get('image');
//    $imageName = time().'.' . explode('/', explode(':', substr($image, 0 , strpos($image, ';')))[1])[1];
//    Image::make($request->get('image'))->save(public_path('images/').$imageName);
    $brand->name = $request->name;
    $brand->save();
//    $request->image->move(public_path('images'), $imageName);

    return response()->json([
      'status'=>true,
      'brand' => $brand,
//      'file'=>file_get_contents($request->image),
//      'file'=>$imageName
    ], 201);
  }

  public function show($id)
  {
    $brand = Brand::find($id);

    if(!isset($brand->id)) {
      return response()->json([
        'status' => false,
        'message' => 'Не найдено!'
      ], 200);
    } else {
      return response()->json([
        'status' => true,
        'brand' => $brand
      ], 200);
    }

  }

  public function update(Request $request, $id)
  {
    $brand = Brand::find($id);

    if(isset($brand->id)) {
      $brand->name = $request->name;
      $brand->save();
      return response()->json([
        'status' => true,
        'brand' => $brand
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
    $brand = Brand::find($id);
    $brand->delete();

    if (!Brand::find($id)) {
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
