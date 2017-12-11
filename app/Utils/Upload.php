<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/22
 * Time: 10:07
 */
namespace App\Utils;

use Illuminate\Support\Facades\Input;

class Upload
{


    //头像图片上传
    public static function upload($path,$uid)
    {
        $file = Input::file('file');
        if($file -> isValid()){
            $entension = strtolower($file -> getClientOriginalExtension()); //上传文件的后缀.
            $path120 = $path.'/120/';

            if(!is_dir($path120)){
                mkdir($path120,0777,true);
            }

            $path30 = $path.'/30/';
            if(!is_dir($path30)){
                mkdir($path30,0777,true);
            }
            $path60 = $path.'/60/';
            if(!is_dir($path60)){
                mkdir($path60,0777,true);
            }
            $newName = date('YmdHis').rand(1000,9999).'.'.$entension;
            $path = $file -> move($path120,$newName);

            $file30 = file_get_contents($path120.'/'.$newName);
            file_put_contents($path30.'/'.$newName,$file30);
            file_put_contents($path60.'/'.$newName,$file30);

            $serverPathName120 =$path120.'/'.$newName;
            $serverPathName90 =$path30.'/'.$newName;
            $serverPathName60 =$path60.'/'.$newName;
//            self::img2thumb($serverPathName120,$path120.'/'.$newName,120,120,1,0,$entension);
//            self::img2thumb($serverPathName90,$path30.'/'.$newName,30,30,1,0,$entension);
//            self::img2thumb($serverPathName60,$path60.'/'.$newName,60,60,1,0,$entension);
            return $newName;
        }
    }
    //头像图片上传
    public static function resumeupload($path)
    {
        $file = Input::file('file');
        if($file -> isValid()){
            $entension = strtolower($file -> getClientOriginalExtension()); //上传文件的后缀.
            $path120 = $path.'/';
            if(!is_dir($path120)){
                mkdir($path120,0777,true);
            }
            $newName = date('YmdHis').rand(1000,9999).'.'.$entension;
            $file -> move($path120,$newName);
            file_get_contents($path120.'/'.$newName);
            $serverPathName120 =$path120.'/'.$newName;
            self::img2thumb($serverPathName120,$path120.'/'.$newName,120,120,1,0,$entension);
            return $newName;
        }
    }
    /**
     * 生成缩略图
     * @author yangzhiguo0903@163.com
     * @param string     源图绝对完整地址{带文件名及后缀名}
     * @param string     目标图绝对完整地址{带文件名及后缀名}
     * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
     * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
     * @param int        是否裁切{宽,高必须非0}
     * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
     * @return boolean
     */
    public static function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0,$ot)
    {
        if(!is_file($src_img))
        {
            return false;
        }
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
        $srcinfo = getimagesize($src_img);
        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);

        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;

        /**
         * 缩略图不超过源图尺寸（前提是宽或高只有一个）
         */
        if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
        {
            $proportion = 1;
        }
        if($width> $src_w)
        {
            $dst_w = $width = $src_w;
        }
        if($height> $src_h)
        {
            $dst_h = $height = $src_h;
        }

        if(!$width && !$height && !$proportion)
        {
            return false;
        }
        if(!$proportion)
        {
            if($cut == 0)
            {
                if($dst_w && $dst_h)
                {
                    if($dst_w/$src_w> $dst_h/$src_h)
                    {
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    }
                    else
                    {
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                }
                else if($dst_w xor $dst_h)
                {
                    if($dst_w && !$dst_h)  //有宽无高
                    {
                        $propor = $dst_w / $src_w;
                        $height = $dst_h  = $src_h * $propor;
                    }
                    else if(!$dst_w && $dst_h)  //有高无宽
                    {
                        $propor = $dst_h / $src_h;
                        $width  = $dst_w = $src_w * $propor;
                    }
                }
            }
            else
            {
                if(!$dst_h)  //裁剪时无高
                {
                    $height = $dst_h = $dst_w;
                }
                if(!$dst_w)  //裁剪时无宽
                {
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int)round($src_w * $propor);
                $dst_h = (int)round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        }
        else
        {
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width  = $dst_w = $src_w * $proportion;
        }

        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        if(function_exists('imagecopyresampled'))
        {
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        else
        {
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }


    //文章列表图上传
    public static function uploadArticles($path)
    {

        $file = Input::file('file');
        if($file -> isValid()){

            $entension = strtolower($file -> getClientOriginalExtension()); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
            $serverPath = '/public/'.$path.'/'.date('Y',time()).date('m',time());
            $showPath = '/'.$path.'/'.date('Y',time()).date('m',time());
            $file -> move(base_path().$serverPath,$newName);
            $filepath = $showPath.'/'.$newName;
            return $filepath;
        }
    }

    /**
     * 简历模板上传接口
     * @param $path
     * @return string
     */
    public static function uploadTemplate($path)
    {
        $file = Input::file('file');
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
            $serverPath = '/public/'.$path;
            $file -> move(base_path().$serverPath,$newName);
            return $newName;
        }
    }

    /**
     * 擅长领域图标上传接口
     * @param $path
     * @return string
     */
    public static function uploadCategory($path)
    {
        $file = Input::file('file');
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.strtolower($entension);
            $serverPath = '/public/'.$path;
            $file -> move(base_path().$serverPath,$newName);
            return $newName;
        }
    }

    //文章和问题图片上传
    public static function img($path)
    {
        $file = Input::file('file');
        $im = @imagecreatefromjpeg($file -> getRealPath());
        if($file -> isValid()){
            $entension = strtolower($file -> getClientOriginalExtension()); //上传文件的后缀.
            if(in_array($entension,array('bmp','jpg','jpeg','png','ico'))){
                $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
                $serverPath = '/public/'.$path.'/'.date('Y',time()).date('m',time());
                $showPath = '/'.$path.'/'.date('Y',time()).date('m',time());
                $file -> move(base_path().$serverPath,$newName);
//            if(in_array($entension,array('jpg','png'))){
//                self::resaveToJpeg(base_path().$serverPath.'/'.$newName,'90',$entension,$newName);
//            }
                $filepath = $showPath.'/'.$newName;
                return $filepath;
            }else{
                return false;
            }

        }
    }
    /**
     * 将图片以自定义品质，将会删除源图片
     *
     * @param string $filename 图片名称，包含路径
     * @param int    $quality  图片品质，0到100，默认90，100为最高品质
     */
    public static function resaveToJpeg($filename, $quality = 90,$entension,$newName) {

        $path       = dirname($filename);
        $path       = rtrim($path, '/').'/';
        switch($entension) {
            case 'jpg':
                $im = imagecreatefromjpeg($filename);
                imagejpeg($im, $path.$newName, $quality);
                break;
            case 'png':
                $im = imagecreatefrompng($filename);
                imagepng($im, $path.$newName, 9);
                break;
        }
        imagedestroy($im);
    }
}