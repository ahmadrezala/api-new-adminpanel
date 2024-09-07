<?php

namespace App\Repository;

use App\Models\Tag;
use App\Base\ServiceResult;
use App\Base\ServiceWrapper;

class TagRepository
{

    public function __construct(private Tag $tag)
    {



    }




    public function createTag(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {
            return $this->tag->create($inputs);


        });

    }
    public function updateTag(array $inputs, $Tag): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $Tag) {

            return $Tag->update($inputs);


        });

    }
    public function deleteTag($Tag): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($Tag) {

            return $Tag->delete();


        });

    }
    public function getAllTags($search): ServiceResult
    {
        return app(ServiceWrapper::class)(function () use ($search) {
            return $this->tag->where('name', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);



        });

    }




}
