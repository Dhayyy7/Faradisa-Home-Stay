<?php

namespace App\Http\Controllers;

use App\Models\ExtraFacility;
use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        $setting = Setting::first() ?? new Setting([
            'homestay_name' => 'Faradisa HomeStay',
            'logo' => null,
            'wa_number' => '6281234567890',
            'media_assets' => [],
        ]);

        $rooms = Room::with('facilities')->latest()->get();
        $extraFacilities = ExtraFacility::all();

        return view('landing.index', compact('setting', 'rooms', 'extraFacilities'));
    }
}
