<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Mobile\CartService;

class CartController extends Controller
{
    public function getCarts(CartService $cartService)
    {
        return $cartService->getCarts();
    }

    public function addToCart($id, CartService $cartService)
    {
        return $cartService->addToCart($id);
    }

    public function deleteFromCart($id, CartService $cartService)
    {
        return $cartService->deleteFromCart($id);
    }

    public function incrementQuantity($id, CartService $cartService)
    {
        return $cartService->incrementQuantity($id);
    }

    public function decrementQuantity($id, CartService $cartService)
    {
        return $cartService->decrementQuantity($id);
    }
}