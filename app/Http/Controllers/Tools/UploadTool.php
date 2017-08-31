<?php

namespace App\Http\Controllers\Tools;
use Illuminate\Http\Request;
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
        $ClientMimeType=['video/mp4'];
        if ($request->hasFile($field)) {
            $file=$request->file($field);
            if(in_array($file->getClientMimeType(),$ClientMimeType)){
                return $avatar = env('APP_URL').Storage::url($file->store($store_path));
            }else{
                return redirect()->back()->with('upload', '只支持 mp4');
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
                    $avatar[] = env('APP_URL').Storage::url($file->store($store_path));
                }else{
                    return redirect()->back()->with('upload', '只支持 jpg, png, gif');
                }
            }
            return $avatar;

        }else{
            return '';
        }
    }

    //单图片上传-表单
    static function UploadImgForm(Request $request,$field){
        $file=$request->file($field);
        //是否有效
        if(!$file->isValid()){
            return ['error'=>'上传图片无效'];
        }
        //是否是支持的图片类型
        $ClientMimeType=['image/jpeg','image/gif','image/png'];
        if(!in_array($file->getClientMimeType(),$ClientMimeType)){
            return ['error'=>'只支持 jpg, png, gif'];
        }
        //上传大小限制
        if($file->getClientSize()>1024*1024){
            return ['error'=>'图片大小限制1M'];
        }
        //存储格式-日期存储
        $storage_path=config('admin.upload_img_path').'/'.date('Y-m-d');
        $url=env('APP_URL').Storage::url($file->store($storage_path));

        return ['url'=>$url];

    }




}
