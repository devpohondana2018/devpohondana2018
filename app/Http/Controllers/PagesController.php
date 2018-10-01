<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function pinjaman()
    {
        return view('pages.pinjaman');
    }

    public function simulasi()
    {
        return view('pages.simulasi');
    }

    public function privacy_policy()
    {
        return view('pages.privacy-policy');
    }

  	public function pendanaan()
    {
        return view('pages.pendanaan');
    }
    public function tentang_kami()
    {
        return view('pages.tentang-kami');
    }

       public function syarat_dan_ketentuan()
    {
        return view('pages.syarat-dan-ketentuan');
    }

       public function kontak_kami()
    {
        return view('pages.kontak-kami');
    }

}
