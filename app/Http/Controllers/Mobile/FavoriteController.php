<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Mobile\FavoriteService;

class FavoriteController extends Controller
{
    public function getFavorites(FavoriteService $favoriteService)
    {
        return $favoriteService->getFavorites();
    }

    public function addToFavorite($id, FavoriteService $favoriteService)
    {
        return $favoriteService->addToFavorite($id);
    }

    public function deleteFromFavorite($id, FavoriteService $favoriteService)
    {
        return $favoriteService->deleteFromFavorite($id);
    }
}