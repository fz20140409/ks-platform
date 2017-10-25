<?php
/**
 * 自定义函数
 */

if (! function_exists('curl_request')) {
    /**
     * curl:支持get(默认)，post
     * @param $url
     * @param int $timeout 60s
     * @param bool $is_post
     * @param $data 请求体数组
     * @param $header 请求头数组
     * @return mixed|string
     */
    function curl_request($url,  $is_post=false,$data,$header=array(),$timeout = 60)
    {
        $ssl = stripos($url,'https://') === 0 ? true : false;
        $curlObj = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout,


        ];
        if ($ssl) {
            //support https
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        if($is_post){
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = http_build_query($data);

        }
        if(!empty($header)){
            $options[CURLOPT_HTTPHEADER]=$header;
        }
        curl_setopt_array($curlObj, $options);
        $returnData = curl_exec($curlObj);
        if (curl_errno($curlObj)) {
            //error message
            $returnData = curl_error($curlObj);
        }
        curl_close($curlObj);
        return $returnData;
    }

}

if (! function_exists('toLevel')) {
    //一维 array
     function toLevel($cates, $pid = 0, $delimiter = '--', $level = 0)
    {
        $data = array();
        foreach ($cates as $cate) {
            if ($cate['pid'] == $pid) {
                $cate['level'] = $level + 1;
                $cate['delimiter'] = str_repeat($delimiter, $level * 2);
                $data[] = $cate;
                $data = array_merge($data, toLevel($cates, $cate['id'], $delimiter, $cate['level']));
            }

        }
        return $data;
    }

}

if (! function_exists('toLayer')) {
    //组成多维数组
     function toLayer($cates, $name = 'child', $pid = 0)
    {
        $arr = array();
        foreach ($cates as $cate) {
            if ($cate['pid'] == $pid) {
                $cate[$name] = toLayer($cates, $name, $cate['id']);
                $arr[] = $cate;
            }
        }

        return $arr;
    }

}

if (! function_exists('objectToArray')) {
    /**
     * std object转数组
     * @param $data 需要转换的数据
     * @return array
     */
    function objectToArray($data){
        return  array_map('get_object_vars', $data);
    }

}
if (! function_exists('imHeader')) {
    /**
     * std object转数组
     * @param $data 需要转换的数据
     * @return array
     */
    function imHeader(){
        $appkey=config('admin.appkey');
        $nonce=uniqid();
        $curTime=time();
        $checkSum=sha1(config('admin.appSecret').$nonce.$curTime);

        return array(
            "AppKey:$appkey",
            "Nonce:$nonce",
            "CurTime:$curTime",
            "CheckSum:$checkSum"
        );


    }

}


