<?php

namespace App\Http\Controllers;

class HalamanController extends Controller
{
    public function cerita()
    {
        return view('halaman.cerita');
    }

    public function cara()
    {
        return view('halaman.cara');
    }
}