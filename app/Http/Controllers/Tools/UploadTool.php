<?php

namespace App\Http\Controllers\Tools;
use Illuminate\Support\Facades\Storage;

class UploadTool
{

    static function UploadImg($request,$field,$store_path)
    {
        $ClientMimeType=['image/jpeg','image/gif','image/png'];
        if ($request->hasFile($field)) {
            $file=$request->file($field);
            if(in_array($file->getClientMimeType(),$ClientMimeType)){

               return $avatar = env('APP_URL').Storage::url($file->store($store_path));
            }else{
                return redirect()->back()->with('upload', '只支持 jpg, png, gif');
            }
        }else{
           return '';
        }
    }
    static function UploadVideo($request,$field,$store_path)
    {
        //todo
        $ClientMimeType=['video/mp4','image/gif','image/png'];
        if ($request->hasFile($field)) {
            $file=$request->file($field);
            if(in_array($file->getClientMimeType(),$ClientMimeType)){
                return $avatar = Storage::url($file->store($store_path));
            }else{
                return redirect()->back()->with('upload', '只支持 mp4, png, gif');
            }
        }else{
            return '';
        }
    }

    static function UploadMultipleImg($request,$field,$store_path)
    {
        $ClientMimeType=['image/jpeg','image/gif','image/png'];

        if ($request->hasFile($field)) {

            $files=$request->file($field);
            $avatar=[];
            foreach ($files as $file){
                if(in_array($file->getClientMimeType(),$ClientMimeType)){
                    $avatar[] = Storage::url($file->store($store_path));
                }else{
                    return redirect()->back()->with('upload', '只支持 jpg, png, gif');
                }
            }
            return $avatar;

        }else{
            return '';
        }
    }




}
