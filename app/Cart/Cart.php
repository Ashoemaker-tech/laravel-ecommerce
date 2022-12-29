<?php

namespace App\Cart;

use App\Cart\Contracts\CartInterface;
use Illuminate\Session\SessionManager;

Class Cart implements CartInterface
{

    public function __construct  (protected SessionManager $session) { }

    public function create() 
    {
            dd($this->session);
    }
}