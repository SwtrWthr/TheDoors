<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
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

    $color = new Color();
//    $contents = file_get_contents($request->image);
//    error_log($contents);
//    Log::info($contents);
//    $image = $request->get('image');
//    $imageName = time().'.' . explode('/', explode(':', substr($image, 0 , strpos($image, ';')))[1])[1];
//    Image::make($request->get('image'))->save(public_path('images/').$imageName);
    $color->name = $request->name;
    $color->save();
//    $request->image->move(public_path('images'), $imageName);

    return response()->json([
      'status' => true,
      'color' => $color,
//      'file'=>file_get_contents($request->image),
//      'file'=>$imageName
    ], 201);
  }

  public function show($id)
  {
    $color = Color::find($id);

    if (!isset($color->id)) {
      return response()->json([
        'status' => false,
        'message' => 'Не найдено!'
      ], 200);
    } else {
      return response()->json([
        'status' => true,
        'color' => $color
      ], 200);
    }

  }

  public function update(Request $request, $id)
  {
    $color = Color::find($id);

    if (isset($color->id)) {
      $color->name = $request->name;
      $color->save();
      return response()->json([
        'status' => true,
        'color' => $color
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
    $color = Color::find($id);

    if(isset($color->id)) {
      $color->delete();
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
