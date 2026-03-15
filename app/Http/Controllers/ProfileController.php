<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\HistoryRepository;
use App\Repositories\MissionRepository;
use App\Repositories\TeamRepository;

class ProfileController extends Controller
{
    protected $historyRepository;
    protected $teamRepository;
    protected $missionRepository;
    protected $visionRepository;

    public function __construct() {
        $this->historyRepository = app(HistoryRepository::class);
        $this->teamRepository = app(TeamRepository::class);
        $this->missionRepository = app(MissionRepository::class); 
    }

    public function index()
    {
        $history = $this->historyRepository->findFirst();
        $teams = $this->teamRepository->getAll();
        $getMissions = $this->missionRepository->getMission()->all();
        $getVision = $this->missionRepository->getVision()->all();

        $missions = [];
        foreach ($getMissions as $mission) {
            $missions[] = $mission['content'];
        }

        $visions = [];
        foreach ($getVision as $vision) {
            $visions[] = $vision['content'];
        }

        return view('profile', compact('history', 'teams', 'missions', 'visions'));
    }
}
