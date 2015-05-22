<?php
/*
 * 采用中顾法律网新闻ip库
 * 根据ip地址，获取相应的省市等信息
 * 
 * 
 * @作者 : Mien
 * @日期 : 2010-05-14 13:08
 * @描述 : IP操作类
 * 把IP范围的起始和结束地址以及相应省市的偏移量以无符号整数形式[小尾顺序]存储在ip.dat文件中
 * 以二分法搜索相应IP，并根据其省市在city.dat文件中的偏移获取省市信息
 * 
 * 
 * 示例：
 *  $q = new IPCity();
    $q->GetCityByIP($ip);

    // 设置变量
    $Province   = $q->Province;
    $City       = $q->City;
    $ProvinceID = $q->ProvinceID;
    $CityID     = $q->CityID;
    $ProvincePY = $q->ProvincePY;
    $CityPY     = $q->CityPY;
   
 */



// IP存储文件定义
define("IP_FILE", SYSTEM_PATH.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'ipdata'.DIRECTORY_SEPARATOR.'ip.dat');
define("CITY_FILE", SYSTEM_PATH.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'ipdata'.DIRECTORY_SEPARATOR.'city.dat');

// IP 操作类
class IPCity {
    public $StartIP	= 0;
    public $EndIP	= 0;
    private $CityOff	= 0;

    public $Province	= '';
    public $City		= '';
    public $ProvinceID	= '';
    public $CityID		= '';
    public $ProvincePY	= '';
    public $CityPY		= '';

    public $Count      = 0;

    private $fp;
    private $fc;

    private function ReadIP ( $RecNo ) {
        $offset = $RecNo * 12;
        @fseek ( $this->fp , $offset , SEEK_SET );
        $buf = fread ( $this->fp , 12 );
        $this->StartIP = ord($buf[0]) + (ord($buf[1]) * 256) + (ord($buf[2]) * 256 * 256) + (ord($buf[3]) * 256 * 256 * 256);
        $this->EndIP = ord($buf[4]) + (ord($buf[5]) * 256) + (ord($buf[6]) * 256 * 256) + (ord($buf[7]) * 256 * 256 * 256);
        $this->CityOff = ord($buf[8]) + (ord($buf[9]) * 256) + (ord($buf[10]) * 256 * 256) + (ord($buf[11]) * 256 * 256 * 256);
    }

    private function ReadCity() {
        @fseek ( $this->fc , $this->CityOff , SEEK_SET);
        $buf = fread( $this->fc, 3);
        $this->ProvinceID = ord($buf[0]);
        $this->CityID = ord($buf[1]) + (ord($buf[2]) * 256);
        $this->Province = $this->GetStr();
        $this->City = $this->GetStr();
        $this->ProvincePY = $this->GetStr();
        $this->CityPY = $this->GetStr();
    }

    // 读取字符串
    private function GetStr ( ) {
        $str = '';
        while ( 1 ) {
            $c = fgetc ( $this->fc );
            if ( ord ( $c[0] ) == 0 )
                break;
            $str .= $c;
        }
        return $str;
    }

    public function GetIPCount() {
        $this->Count = floor(filesize(IP_FILE) / 12);
    }

    public function GetCityByIP ($dotip) {
        $nRet = 3;
        $ip = ip2long ( $dotip );
        if($ip <= 0)
            $ip += 4294967296; // 如果为负数，则加上 2 的 32 次方 (PHP没有无符号整数的说法)

        $this->fp = @fopen(IP_FILE, "rb");
        if ($this->fp == NULL) {
            $this->Province = "OpenIPFileError";
            return 1;
        }

        $this->fc = @fopen(CITY_FILE, "rb");
        if ($this->fc == NULL) {
            $this->Province = "OpenCityFileError";
            return 2;
        }

        $this->GetIPCount();
 
        if ($this->Count < 1) {
            $this->Province = "FileDataError";
            fclose ( $this->fp );
            return 3;
        }

        $RangB = 0;
        $RangE = $this->Count;

        // 搜索
        while ($RangB < $RangE-1) {
            $RecNo = floor(($RangB + $RangE) / 2);
            $this->ReadIP ( $RecNo );

            if ( $ip == $this->StartIP ) {
                $RangB = $RecNo;
                break;
            }
            
            if ( $ip > $this->StartIP)
                $RangB = $RecNo;
            else
                $RangE = $RecNo;
        }

        $this->ReadIP ( $RangB );

        if ( ( $this->StartIP   <= $ip ) && ( $this->EndIP >= $ip ) ) {
            $nRet = 0;
            $this->ReadCity();
        }else {
            $nRet = 3;
            $this->Province = '未知';
        }

        fclose ( $this->fp );
        fclose ( $this->fc );

        return $nRet;
    }

    // 在调用此函数之前应该先调用 GetIPCount 取得IP范围数目
    public function GetCityByNo($RecNo) {
        if($RecNo < 0)
            return 1;

        $this->fp = @fopen(IP_FILE, "rb");
        if ($this->fp == NULL) {
            $this->Province = "OpenIPFileError";
            return 2;
        }

        $this->fc = @fopen(CITY_FILE, "rb");
        if ($this->fc == NULL) {
            $this->Province = "OpenCityFileError";
            return 3;
        }

        if ($this->Count < 1) {
            $this->Province = "FileDataError";
            fclose ( $this->fp );
            return 4;
        }

        if($RecNo >= $this->Count)
            return 5;

        $this->ReadIP ( $RecNo );
        $this->ReadCity();
        
        fclose ( $this->fp );
        fclose ( $this->fc );

        return 0;
    }
}
?>