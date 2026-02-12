<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\HistoryRepository;
use App\Repositories\TeamRepository;
use App\Repositories\MissionRepository;

class ProfileController extends Controller
{
    public function __construct(
        protected HistoryRepository $historyRepository,
        protected TeamRepository $teamRepository,
        protected MissionRepository $missionRepository
    ) {}

    public function index()
    {
        $history = $this->historyRepository->findFirst();
        $teams = $this->teamRepository->getAll();
        $missions = optional($this->missionRepository->getMission()->first())->content ?? '';
        $vision = optional($this->missionRepository->getVision()->first())->content ?? '';

        return view('profile', compact('history', 'teams', 'missions', 'vision'));
    }
}
