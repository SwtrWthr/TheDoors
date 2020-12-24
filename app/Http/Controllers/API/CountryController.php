<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
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

    $country = new Country();
//    $contents = file_get_contents($request->image);
//    error_log($contents);
//    Log::info($contents);
//    $image = $request->get('image');
//    $imageName = time().'.' . explode('/', explode(':', substr($image, 0 , strpos($image, ';')))[1])[1];
//    Image::make($request->get('image'))->save(public_path('images/').$imageName);
    $country->name = $request->name;
    $country->save();
//    $request->image->move(public_path('images'), $imageName);

    return response()->json([
      'status' => true,
      'country' => $country,
//      'file'=>file_get_contents($request->image),
//      'file'=>$imageName
    ], 201);
  }

  public function show($id)
  {
    $country = Country::find($id);

    if (!isset($country->id)) {
      return response()->json([
        'status' => false,
        'message' => 'Не найдено!'
      ], 200);
    } else {
      return response()->json([
        'status' => true,
        'country' => $country
      ], 200);
    }

  }

  public function update(Request $request, $id)
  {
    $country = Country::find($id);

    if (isset($country->id)) {
      $country->name = $request->name;
      $country->save();
      return response()->json([
        'status' => true,
        'country' => $country
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
    $country = Country::find($id);

    if(isset($country->id)) {
      $country->delete();
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
