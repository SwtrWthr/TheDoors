<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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

    $material = new Material();
//    $contents = file_get_contents($request->image);
//    error_log($contents);
//    Log::info($contents);
//    $image = $request->get('image');
//    $imageName = time().'.' . explode('/', explode(':', substr($image, 0 , strpos($image, ';')))[1])[1];
//    Image::make($request->get('image'))->save(public_path('images/').$imageName);
    $material->name = $request->name;
    $material->save();
//    $request->image->move(public_path('images'), $imageName);

    return response()->json([
      'status' => true,
      'material' => $material,
//      'file'=>file_get_contents($request->image),
//      'file'=>$imageName
    ], 201);
  }

  public function show($id)
  {
    $material = Material::find($id);

    if (!isset($material->id)) {
      return response()->json([
        'status' => false,
        'message' => 'Не найдено!'
      ], 200);
    } else {
      return response()->json([
        'status' => true,
        'material' => $material
      ], 200);
    }

  }

  public function update(Request $request, $id)
  {
    $material = Material::find($id);

    if (isset($material->id)) {
      $material->name = $request->name;
      $material->save();
      return response()->json([
        'status' => true,
        'material' => $material
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
    $material = Material::find($id);

    if(isset($material->id)) {
      $material->delete();
      return response()->json([
        'status' => true
      ], 200);
    } else {
      return response()->json([
        'status' => false,
        'message' => 'Не найдено!'
      ], 200);
    }
  }
}
