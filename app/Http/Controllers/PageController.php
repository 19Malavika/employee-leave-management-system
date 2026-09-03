<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\Service;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function services()
    {
        $services = Service::all();

        return view('services', compact('services'));
    }

    public function caseStudies()
    {
        $caseStudies = CaseStudy::all();

        return view('case-studies', compact('caseStudies'));
    }

    public function contact()
    {
        return view('contact');
    }
}