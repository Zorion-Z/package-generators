<?php

namespace Generators\Helpers;

class FileHelper 
{

    protected static function getFacadeAccessor()
    {
        return __CLASS__;
    }

    public static function isExist($path = '')
    {
        return is_dir($path);
    }

    public static function createDir($path = '')
    {
        return self::isExist($path) 
               ? true 
               : mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
    }

    public static function getFileType($filename)
    {
        if (!is_file($filename)) 
        {
            return '';
        }

        $path = pathinfo($filename);
        return $path['extension'];
    }

    public static function getFileContent($filename)
    {
        if (!is_file($filename)) 
        {
            return array();
        }
        switch (self::getFileType($filename)) 
        {
            case 'csv':
            case 'CSV':
                $data = self::getCsvContent($filename);
                break;
            case 'json':
            case 'JSON':
                $data = self::getJsonContent($filename);
                break;
            case 'txt':
            case 'TXT':
                $data = self::getTxtContent($filename);
                break;
            case 'log':
            case 'LOG':
                $data = self::getLogContent($filename);
                break;
            case 'xml':
            case 'XML':
                $data = self::getXmlContent($filename);
                break;
            case 'yml':
            case 'YML':
                $data = self::getYmlContent($filename);
                break;
            default:
                $data = file_get_contents($filename);
                break;
        }
        return $data;
    }

    public static function getCsvContent($filename)
    {
        if (!is_file($filename))
        {
            return array();
        }

        $file = fopen($filename,'r');
        $titles = fgetcsv($file);
        while ($row = fgetcsv($file)) //每次读取CSV里面的一行内容
        { 
            $param = array();
            foreach ($titles as $key => $title) {
                $param[$title] = $row[$key];
            }
            $csv[] = $param;
        }

       return $csv;
    }

    public static function getJsonContent($filename)
    {
        if (!is_file($filename))
        {
            return array();
        }

        $json_raw_string = file_get_contents($filename);
        return json_decode($json_raw_string, true);
    }

    public static function getLogContent($filename)
    {
        if (!is_file($filename)) 
        {
            return array();
        }
        $file = fopen($filename, "r"); //打开txt文件

        while (!feof($file))
        {
             $log_raw_data[] = fgets($file);
        }    
        fclose($file);
        // $file = file($filename);
        // $log = [];
        // foreach ($file as $line) 
        // {
        //     if (strlen($line) == 0 || $line === "\r"|| $line ==="\n")
        //     {
        //         continue;
        //     }

        //     $log[] = trim($line);
        // }
        return $log_raw_data;
    }

    public static function getTxtContent($filename)
    {
        if (!is_file($filename)) 
        {
            return array();
        }
        $file = fopen($filename, "r"); //打开txt文件
        $title = explode('|', fgets($file));
        while (!feof($file))
        {
             $row = explode('|', fgets($file));//fgets()函数从文件指针中读取一行
             $param = array();
             foreach ($title as $key => $value) {
                 if (strlen(trim($value)) == 0)
                 {
                     continue;
                 }
                 $param[trim($value)] = trim($row[$key]);
             }
             $txt_raw_data[] = $param;
        }    
        fclose($file);

        return $txt_raw_data;
    }

    public static function getXmlContent($filename)
    {
        if (!is_file($filename)) 
        {
            return array();
        }

        $raw_xml = simplexml_load_file($filename);
        foreach($raw_xml as $key => $xml_child)
        {
            $xml[] = get_object_vars($xml_child);
        }

        return $xml;
    }

    public static function getYmlContent($filename)//needs to composer mustangostang/spyc
    {
        // if (!is_file($filename)) 
        // {
        //     return array();
        // }

        // $raw_yml = yaml_parse_file($filename);
        // var_dump($raw_yml);die;
        // foreach($raw_xml as $key => $xml_child)
        // {
        //     $xml[] = get_object_vars($xml_child);
        // }

        return $xml=[];
    }

    public static function writeToFile($filename, $content, $is_append = false)
    {
        if (!empty($filename))
        {
            return file_put_contents($filename, $content, $is_append ? FILE_APPEND : null);
        } else {
            return false;
        }
    }

}
