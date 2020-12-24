<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Door extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'quantity',
    'image',
    'size',
    'price',
    'garant',
    'brand_id',
    'country_id'
  ];

  protected $hidden = [
    'brand_id',
    'country_id'
  ];

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  public function colors()
  {
    return $this->belongsToMany(Color::class, 'door_color');
  }

  public function materials()
  {
    return $this->belongsToMany(Material::class, 'door_material');
  }
}
