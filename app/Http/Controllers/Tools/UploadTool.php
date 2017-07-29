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
               return $avatar = Storage::url($file->store($store_path));
            }else{
                return redirect()->back()->with('upload', '只支持 jpg, png, gif');
            }
        }else{
           return '';
        }
    }




}
