<?php

namespace App\Models;

use Cknow\Money\Money;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

	public function registerMediaConversions(?Media $media = null): void
	{
		$this->addMediaConversion('thumbnail')->fit(Manipulations::FIT_CROP, 200, 200);
		
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('default')->useFallbackUrl('/storage/notAvailable.jpg');

	}
}
