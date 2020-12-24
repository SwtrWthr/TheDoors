<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Door;
use App\Models\Material;
use App\Models\Color;
use Faker\Provider\Image;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Str;

class DoorController extends Controller
{
  public function index()
  {
    $doors = Door::orderByDesc('created_at')->with('country', 'brand', 'materials', 'colors')->get();
    $brands = Brand::all();
    $countries = Country::all();
    $materials = Material::all();
    $colors = Color::all();

    return response()->json([
      'doors' => $doors,
      'brands' => $brands,
      'countries' => $countries,
      'materials' => $materials,
      'colors' => $colors
    ]);
  }

  public function store(Request $request)
  {
    $data = [
      'name' => 'required|max:120',
      'price' => 'numeric|required|min:1000',
      'quantity' => 'required|numeric|min:1',
      'garant' => 'required|numeric',
      'description' => 'required|max:1024',
    ];

    $validator = Validator::make($request->all(), $data);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    }

//    Log::info($request->all());
    $exploded = explode(',', $request->image);
    if(isset($exploded[1])) {
      $decoded = base64_decode($exploded[1]);
      if (str_contains($exploded[0], 'jpeg')) {
        $extension = 'jpeg';
      } else {
        $extension = 'png';
      }
      $fileName = \Illuminate\Support\Str::random() . '.' . $extension;
      $path = public_path() . '/images/' . $fileName;
      file_put_contents($path, $decoded);
    } else {
      $fileName = $request->image;
    }
    $door = new Door();
//    $contents = file_get_contents($request->image);
//    error_log($contents);
//    Log::info($contents);
//    $image = $request->get('image');
//    $imageName = time().'.' . explode('/', explode(':', substr($image, 0 , strpos($image, ';')))[1])[1];
//    Image::make($request->get('image'))->save(public_path('images/').$imageName);
    $door->name = $request->name;
    $door->price = $request->price;
    $door->quantity = $request->quantity;
    $door->garant = $request->garant;
    $door->description = $request->description;
    $door->image = $fileName;
    $door->size = $request->size;
    $door->brand_id = $request->brand_id;
    $door->country_id = $request->country_id;
    $door->save();
//    $request->image->move(public_path('images'), $imageName);

    $door->colors()->sync($request->colors);
    $door->materials()->sync($request->materials);
    return response()->json([
      'status'=>true,
      'door'=>$door,
//      'file'=>file_get_contents($request->image),
//      'file'=>$imageName
      ], 201);

  }

  public function show($id)
  {
    if ($door = Door::find($id)) {
      $door = Door::with('country', 'brand', 'colors', 'materials')->findOrFail($id);
      return response()->json([
        'status' => true,
        'door' => $door
      ]);
    } else {
      return response()->json([
        'status' => false
      ]);
    }

  }

  public function update(Request $request, $id)
  {
    $door = Door::find($id);

    if(!isset($door->id)) {
      return response()->json([
        'status'=>false,
        'message'=>'Дверь не найдена!'
      ], 200);
    }

    if($request->image != $door->image) {
      $exploded = explode(',', $request->image);
      $decoded = base64_decode($exploded[1]);
      if(str_contains($exploded[0], 'jpeg')) {
        $extension = 'jpeg';
      } else {
        $extension = 'png';
      }
      $fileName = \Illuminate\Support\Str::random().'.'.$extension;
      $path = public_path().'/images/'.$fileName;
      file_put_contents($path, $decoded);
    } else {
      $fileName = $door->image;
    }
    $door->name = $request->name;
    $door->price = $request->price;
    $door->quantity = $request->quantity;
    $door->garant = $request->garant;
    $door->description = $request->description;
    if(isset($door->image)) {
      $door->image = $fileName;
    }
//    $door->image = $request->file('image')->store('images');
    $door->size = $request->size;
    $door->brand_id = $request->brand_id;
    $door->country_id = $request->country_id;

    $door->save();

    $door->colors()->detach();
    $door->materials()->detach();
    $door->colors()->sync($request->colors);
    $door->materials()->sync($request->materials);

    return response()->json([
      'status'=>true,
      'door'=>$door
    ],200);
  }

  public function destroy($id)
  {
    $door = Door::find($id);
    $door->delete();
    if (!Door::find($id)) {
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
