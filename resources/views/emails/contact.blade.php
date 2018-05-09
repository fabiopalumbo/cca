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
                                                                <p style="margin:1em 0;padding:0;color:#606060;font-family:Helvetica;font-size:15px;line-height:150%;text-align:left"><span style="font-family:arial,helvetica neue,helvetica,sans-serif">{{trans('emails.contact.title')}} </span>
                                                                    </p>
                                                                <strong>{{trans('emails.contact.name')}}</strong>
                                                                {{ $name }}
                                                                <br>
                                                                <strong>{{trans('emails.contact.mail')}}</strong>
                                                                {{ $email }}
                                                                <br>
                                                                <strong>{{trans('emails.contact.message')}}</strong>
                                                                {{ $message }}

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