<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessProfile;

class BusinessProfileController extends Controller
{	
	public function index()
    {
        $profiles = BusinessProfile::where('approved', 1)->get();
		// Transformasi data
        $locations = $profiles->map(function($profile) {
            return [
                'lat' => $profile->location[1],
                'long' => $profile->location[0],
                'info' => $profile->name
            ];
        });
		$locationsJson =  json_encode($locations);
		
        return view('layout', compact('profiles', 'locationsJson'));
	}
	
	public function detail($slug)
    {
        $profile = BusinessProfile::where('slug', $slug)->where('approved', 1)->firstOrFail();
		$images = json_decode($profile->image, true);
        return view('profile', compact('profile', 'images'));
    }
}
