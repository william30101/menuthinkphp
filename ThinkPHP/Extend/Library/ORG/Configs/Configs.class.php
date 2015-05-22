<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-9-9
 * Time: 下午1:31
 * To change this template use File | Settings | File Templates.
 */

class Configs {//类定义开始
    /**
     * author Tony
     * 加载配置文件
     * @param string $file 配置文件
     * @param string $key  要获取的配置荐
     * @param string $default  默认配置。当获取配置项目失败时该值发生作用。
     * @param boolean $reload 强制重新加载。
     */
    public static function load_config($file, $key = '', $default = '', $reload = false) {
        static $configs = array();
        if (!$reload && isset($configs[$file])) {
            if (empty($key)) {
                return $configs[$file];
            } elseif (isset($configs[$file][$key])) {
                return $configs[$file][$key];
            } else {
                return $default;
            }
        }
        $path = CACHE_PATH.'configs'.DIRECTORY_SEPARATOR.$file.'.php';
        if (file_exists($path)) {
            $configs[$file] = include $path;
        }
        if (empty($key)) {
            return $configs[$file];
        } elseif (isset($configs[$file][$key])) {
            return $configs[$file][$key];
        } else {
            return $default;
        }
    }

    /**
     * author Tony
     * 2013-10-09
     * @param $file_name
     * @return bool
     */
    public function  get_config($file_name)
    {
        $get_config = getcache($file_name,'setting');
        if(!$get_config)
        {
            return false;
        }
        else
        {
            return $get_config;
        }
    }

    /**
     * author Tony
     * 2013-10-08
     * @param string $keyname
     * @return string
     */
    public  function  get_base_config($keyname='')
    {
        $site_base_config = $this->get_config('site_base');
        $return_array='';
        if($keyname!='' && is_array($site_base_config) && count($site_base_config)>0)
        {
            $optionvalue = $site_base_config['optionvalue'];
            foreach($optionvalue as $key=>$val)
            {
                if($key==$keyname)
                {
                    $return_array=$val;
                }
            }
        }
        else{
            $return_array=$site_base_config['optionvalue'];
        }
        return $return_array;
    }

    /**
     * author Tony
     * 2013-10-08
     * @param string $keyname
     * @return string
     */
    public  function  get_user_config($keyname='')
    {
        $user_config = $this->get_config('user_config');
        $return_array='';
        if($user_config)
        {
            $return_array = $user_config['optionvalue'];
        }
        return $return_array;
    }

}