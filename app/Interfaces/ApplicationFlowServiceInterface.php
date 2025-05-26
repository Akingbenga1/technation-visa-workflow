<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\UserApplication;
use App\Models\ApplicationStep;
use App\Models\ApplicationResponse;

interface ApplicationFlowServiceInterface
{
    public function startApplication(User $user): UserApplication;
    public function getCurrentStep(UserApplication $application): ?ApplicationStep;
    public function getNextStep(UserApplication $application): ?ApplicationStep;
    public function getPreviousStep(UserApplication $application): ?ApplicationStep;
    public function completeStep(UserApplication $application, ApplicationStep $step, array $responseData): ApplicationResponse;
    public function moveToStep(UserApplication $application, ApplicationStep $targetStep): bool;
    public function updateApplicationStatus(UserApplication $application, string $status): bool;
} 