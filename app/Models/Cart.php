<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'door_id',
    'quantity'
  ];

  public function door() {
    return $this->belongsTo(Door::class);
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

}
