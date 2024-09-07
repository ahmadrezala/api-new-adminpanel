<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Repository\TagRepository;
use App\Http\Controllers\Controller;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Admin\Tag\TagStoreApiRequest;
use App\Http\ApiRequests\Admin\Tag\TagUpdateApiRequest;
use App\Http\Resources\Admin\TagResource\TagListApiResource;

class TagController extends Controller
{
    public function __construct(private TagRepository $TagRepository)
    {
    }




    public function index(Request $request)
    {
        $result = $this->TagRepository->getAllTags($request->query('search'));
        return $result->ok
            ? ApiResponse::withData([
                'tags' => TagListApiResource::collection($result->data)->response()->getData()->data,
                'total_pages' => TagListApiResource::collection($result->data)->response()->getData()->meta->last_page,
                'current_page' => TagListApiResource::collection($result->data)->response()->getData()->meta->current_page,

            ])->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagStoreApiRequest $request)
    {

        $result = $this->TagRepository->createTag($request->validated());
        return $result->ok
            ? ApiResponse::withMessage('Tag create successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TagUpdateApiRequest $request, Tag $Tag)
    {
        $result = $this->TagRepository->updateTag($request->validated(), $Tag);
        return $result->ok
            ? ApiResponse::withMessage('Tag updated successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }

    public function show(Tag $tag)
    {

        return ApiResponse::withData($tag)->build()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $Tag)
    {
        $result = $this->TagRepository->deleteTag($Tag);
        return $result->ok
            ? ApiResponse::withMessage('Tag deleted successfully')->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();

    }
}
