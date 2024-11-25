<?php

namespace App\Http\Controllers;

use App\Services\ComplianceService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected $complianceService;

    // Inject the ComplianceService into the controller
    public function __construct(ComplianceService $complianceService)
    {
        $this->complianceService = $complianceService;
    }

    public function index()
    {
        $projections = $this->complianceService->monthlyProjections();

        // dd($projections);

        // return $projections;

        return view('module.calendar', compact('projections'));
    }
}
