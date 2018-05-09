<?php

namespace App\Repositories;

use Config;
use App;
use AWS;

class CDNService
{
    /**
     * Sube un archivo al CDN (s3) y retorna la url
     *
     * @param $fileName
     * @param $filePath
     * @param $contentType
     * @return string
     */
    public static function upload($fileName, $filePath, $contentType)
    {
        $s3 = AWS::createClient('s3');
        $s3->putObject(array(
            'ACL'           => 'public-read',
            'Bucket'        => Config::get('aws.cdn_bucket'),
            'Key'           => $fileName,
            'SourceFile'    => $filePath,
            'ContentType'   => $contentType
        ));
      /*
        $s3 = App::make('aws')->createClient('s3');

        $s3->putObject(
            array(
                'key'       => Config::get('aws.access_key'),
                'secret'    => Config::get('aws.secret_key'),
                'region'    => Config::get('aws.region'),
                'ACL'           => 'public-read',
                'Bucket'        =>  Config::get('aws.cdn_bucket'),
                'Key'           =>  $fileName,
                'SourceFile'    =>  $filePath,
                'ContentType'   =>  $contentType
            )
        );
*/
        return Config::get('aws.cdn_url') . $fileName;
    }

    public static function uploadBase64Image($fileName, $base64)
    {
        $s3 = AWS::createClient('s3');
        $s3->putObject(
            array(
                'ACL'               => 'public-read',
                'Bucket'            =>  Config::get('aws.cdn_bucket'),
                'Key'               =>  $fileName,
                'Body'              =>  $base64,
                'ContentType'       =>  "image/jpg",
                'ContentEncoding'   =>  "base64",
            )
        );

        return Config::get('aws.cdn_url') . $fileName;
    }
}
