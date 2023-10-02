<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\File;
use Image;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function changeImage($name, $width, $height, $request, $model = null, $crop = false) {
        if ($request->hasFile($name)) {
            $file = $request->file($name);
            $filepath = $file->path();
            $extension = $file->extension();
        } elseif ($filepath = $request->input($name)) {
            $imginfo = @getimagesize($filepath);
            $extension = null;
            if ($imginfo) {
                switch($imginfo['mime']) {
                    case 'image/png': $extension = 'png'; break;
                    case 'image/jpeg': $extension = 'jpeg'; break;
                    case 'image/webp': $extension = 'webp'; break;
                }
            }
        }

        if($filepath && $extension) {
            $value = '/data/'.$name.'/'.auth()->id().time().'.'.$extension;
            $img = Image::make($filepath);
            if ($crop) {
                $img->fit($width, $height, function ($const) {
                    $const->upsize();
                });
            } else {
                $img->resize($width, $height, function ($const) {
                    $const->aspectRatio();
                });
            }
            $img->save(public_path($value));

            if ($model) {
                File::delete(public_path($model->$name));
                $model->update([$name => $value]);
            }
            return $value;
        }
        return null;
    }
}
