<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\ServiceModel;
use App\Models\ProjectModel;
use App\Models\Testominal;
use App\Models\Team;

class AdminDashController extends Controller
{
    public function index()
    {
        // Fetch all data from the models
        $blogs = BlogModel::all();
        $services = ServiceModel::all();
        $projects = ProjectModel::all();
        $testimonials = Testominal::all();
        $teams = Team::all();

        // Return the data as a JSON response
        return response()->json([
            'blogs' => $blogs,
            'services' => $services,
            'projects' => $projects,
            'testimonials' => $testimonials,
            'teams' => $teams
        ]);
    }

}
