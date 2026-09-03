<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;

class CaseStudyController extends Controller
{
    public function index()
    {
        $caseStudies = CaseStudy::all();

        return view('case-studies', compact('caseStudies'));
    }
}