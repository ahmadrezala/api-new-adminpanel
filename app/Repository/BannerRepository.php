<?php

namespace App\Repository;

use App\Models\Banner;
use App\Base\ServiceResult;
use App\Base\ServiceWrapper;

class BannerRepository
{

    public function __construct(private Banner $banner)
    {



    }




    public function createBanner(array $inputs): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs) {


            $inputs['image'] && $image = uploadimages($inputs['image'], 'banner');
            Banner::create([
                'image' => $image,
                'title' => $inputs['title'],
                'text' => $inputs['text'],
                'priority' => $inputs['priority'],
                'is_active' => $inputs['is_active'],
                'type' => $inputs['type'],
                'button_text' => $inputs['button_text'],
                'button_link' => $inputs['button_link'],
            ]);


        });

    }
    public function updateBanner(array $inputs, $banner): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($inputs, $banner) {



            if (isset($inputs['image'])) {

                $this->handleImageDelete($banner);

                $inputs['image'] && $image = uploadimages($inputs['image'], 'banner');

            }

            $banner->update([
                'image' => $image,
                'title' => $inputs['title'],
                'text' => $inputs['text'],
                'priority' => $inputs['priority'],
                'is_active' => $inputs['is_active'],
                'type' => $inputs['type'],
                'button_text' => $inputs['button_text'],
                'button_link' => $inputs['button_link'],
            ]);


        });

    }
    public function deleteBanner($banner): ServiceResult
    {

        return app(ServiceWrapper::class)(function () use ($banner) {

            $this->handleImageDelete($banner);
            return $banner->delete();


        });

    }
    public function getAllBanners(): ServiceResult
    {
        return app(ServiceWrapper::class)(function () {
            return $this->banner->orderBy('id', 'desc')->paginate(2);


        });

    }
    public function showBanner($banner): ServiceResult
    {
        return app(ServiceWrapper::class)(function () use ($banner) {
            return $banner;


        });

    }


    public function handleImageDelete($banner)
    {
        if (!empty($banner->image)) {
            $imagePath = public_path($banner->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


    }



}
