<?php






function uploadimages($images, $destinationPath)
{

    $uploadedImages = [];
    if (is_array($images)) {
        // for array image
        foreach ($images as $image) {
            $uploadedImages[] = uploadImage($image, $destinationPath);
        }
    } else {
        // for single image
        return uploadImage($images, $destinationPath);
    }

    

    return $uploadedImages;

}



function uploadImage($file, $destinationPath = 'uploads')
{
    if ($file && $file->isValid()) {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($destinationPath), $fileName);
        return $destinationPath . '/' . $fileName;
    }

    return null;
}



