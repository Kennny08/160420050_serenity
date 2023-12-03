<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function mailBatalReservasiAdmin($email, $pelanggan, $pesan, $nomorNota)
    {

        $details = ['pelanggan' => $pelanggan, 'pesan' => $pesan, 'nomor_nota' => $nomorNota];
        // dd($details, $email);
        Mail::to($email)->send(new SendMail('batalreservasiadmin', $details));
    }

    public static function mailUsernamePasswordTambahKaryawan($email, $karyawan, $username, $password)
    {

        $details = ['karyawan' => $karyawan, 'username' => $username, 'password' => $password ];
        Mail::to($email)->send(new SendMail('kirimusernamepasswordkaryawan', $details));
    }
}
