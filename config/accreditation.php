<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pendaftaran publik
    |--------------------------------------------------------------------------
    |
    | Jika false, hanya admin yang dapat membuat akun program studi (unit kerja)
    | dan asesor melalui panel admin. Prodi tidak dapat mendaftar sendiri.
    |
    */

    'allow_public_registration' => env('ALLOW_PUBLIC_REGISTRATION', false),

];
