<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Search\SearchRequest;
use App\Services\Mobile\SearchService;

class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function __invoke(SearchRequest $request)
    {
        $search = strtolower($request->input('search'));

        $result = $this->searchService->search($search);

        return response()->json($result, 200);
    }
}
