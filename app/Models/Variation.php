<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Variation extends Model implements HasMedia
{
    use HasFactory;

	use HasRecursiveRelationships;

	use InteractsWithMedia;

	public function registerMediaConversions(?Media $media = null): void
	{
		$this->addMediaConversion('thumbnail')->fit(Manipulations::FIT_CROP, 200, 200);
		
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('default')->useFallbackUrl('/storage/notAvailable.jpg');

	}

	public function formattedPrice() 
	{
		return money($this->price); 
	}

	public function inStock() 
	{
		return $this->stockCount() > 0;
	}

	public function outOfStock  () 
	{
			return !$this->inStock();
	}
	public function lowStock  () {
			return !$this->outOfStock() && $this->stockCount() <= 5;
		}

	public function stockCount() 
	{
		return $this->descendantsAndSelf->sum(fn ($variation) => $variation->stocks->sum('amount'));
	}
	
	public function stocks() 
	{
		return $this->hasMany(Stock::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
