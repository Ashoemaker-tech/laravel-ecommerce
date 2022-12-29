<?php

namespace App\Models;

use Cknow\Money\Money;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements HasMedia
{
    use HasFactory;
	use InteractsWithMedia;


	public function formattedPrice()
	{
		return Money::USD($this->price);
	}

	public function variations()
	{
		return $this->hasMany(Variation::class);
	}
}
