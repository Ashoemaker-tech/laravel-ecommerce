<?php

namespace App\Cart;

use App\Models\User;
use App\Models\Variation;
use App\Models\Cart as ModelsCart;
use App\Cart\Contracts\CartInterface;
use Illuminate\Session\SessionManager;

Class Cart implements CartInterface
{

    protected $instance;


    public function __construct  (protected SessionManager $session) { }

    /**
     * 
     * check for cart existence & create 
     * 
     * 
     */

    public function exists()
    {
        return $this->session->has(config('cart.session.key'));
    }

    public function create(?User $user = null) 
    {
            $instance = ModelsCart::make();

            if ($user) {
                $instance->user()->associate($user);
            }

            $instance->save();

            $this->session->put(config('cart.session.key'), $instance->uuid);

    }

    /**
     * 
     * Cart set up for global uses such as cart contents, quantity count, display of variations
     * 
     */

     public function getVariation(Variation $variation)
    {
        return $this->instance()->variations->find($variation->id);
    }

    public function contents()
    {
        return $this->instance()->variations;
    }

    public function contentsCount()
    {
        return $this->contents()->count();
    }

    public function IsEmpty()
    {
        return $this->contents()->count() === 0;
    }

    /**
     * 
     * Manipulating Cart state
     * Component Usage: CartItem
     * 
     */

    public function add(Variation $variation, $quantity = 1)

    {

        if ($existingVariation = $this->getVariation($variation)) {
            $quantity += $existingVariation->pivot->quantity;
        }

        $this->instance()->variations()->syncWithoutDetaching([
            $variation->id => [
                'quantity' => min($quantity, $variation->stockCount()),
            ]
        ]);
    }

    public function changeQuantity(Variation $variation, $quantity)
    {
        $this->instance()->variations()->updateExistingPivot($variation->id, [
            'quantity' => min($quantity, $variation->stockCount())
        ]);
    }

    public function remove(Variation $variation)
    {
        $this->instance()->variations()->detach($variation);

    }

    /**
     * 
     * DB Cart Queries 
     * 
     */

    protected function instance()
    {
        if ($this->instance) {
            return $this->instance;
        }
        // cleaning up N+1 issues as items are added to cart
        return $this->instance = ModelsCart::query()
        ->with('variations.product', 'variations.ancestorsAndSelf', 'variations.descendantsAndSelf.stocks', 'variations.media')
        ->whereUuid($this->session->get(config('cart.session.key')))
        ->first();
    }
}