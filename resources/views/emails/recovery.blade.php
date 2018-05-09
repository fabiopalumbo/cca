@extends('emails.layouts.master')

@section('content')


<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
    <tbody>

    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;background-color:#ffffff;border-top:0;border-bottom:0">
                <tbody>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse:collapse">
                            <tbody>
                            <tr>
                                <td valign="top" style="padding-top:10px;padding-bottom:10px">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse">
                                        <tbody>
                                        <tr>
                                            <td valign="top">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse:collapse">
                                                    <tbody>
                                                    <tr>
                                                        <td valign="top" style="padding:9px 18px;text-align:center;color:#606060;font-family:Helvetica;font-size:15px;line-height:150%">
                                                            <h1 style="text-align:left;margin:0;padding:0;display:block;font-family:Helvetica;font-size:40px;font-style:normal;font-weight:bold;line-height:125%;letter-spacing:-1px;color:#606060!important"><span style="font-family:lucida sans unicode,lucida grande,sans-serif"><span style="font-size:32px"><span style="color:#a9a9a9">{{trans('emails.recovery.title')}}</span></span></span></h1>

                                                            <p style="margin:1em 0;padding:0;color:#606060;font-family:Helvetica;font-size:15px;line-height:150%;text-align:left"><span style="font-family:arial,helvetica neue,helvetica,sans-serif">{{trans('emails.recovery.text1')}} </span>
                                                                <!-- href="<?php /*echo URL::to('api/user/recovery/reset-password/'.$token);*/ ?>"   esta asi para probar -->
                                                                <span style="font-family:arial,helvetica neue,helvetica,sans-serif; color:#101010;"><a style="word-wrap:break-word;color:#f4b215;font-weight:normal;text-decoration:underline"  href="http://1b246c78.ngrok.io/api/user/recovery/reset-password/<?php /*echo $token */?>">{{trans('emails.recovery.clickhere')}}</a></span>
                                                                {{trans('emails.recovery.text2')}}
                                                            </p>
                                                            </p>


                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
@endsection