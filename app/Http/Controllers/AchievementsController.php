<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(Request $request, User $user)
    {
        
        // Fetch user's unlocked achievements
        $unlockedAchievements = $user->achievements->pluck('achievement_name')->toArray();

        // Implement logic to calculate next available achievements
        $nextAvailableAchievements = $this->calculateNextAvailableAchievements($unlockedAchievements);
        
        // Calculate the user's current badge
        $currentBadge = $user->badge;

        // Implement logic to calculate the next badge and remaining achievements
        list($nextBadge, $remainingToUnlockNextBadge) = $this->calculateNextBadge($unlockedAchievements);

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaing_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }

    private function calculateNextAvailableAchievements($unlockedAchievements)
    {
        // Define an array of all available achievements
        $availableAchievements = [
            'First Lesson Watched',
            '5 Lessons Watched',
            '10 Lessons Watched',
            '25 Lessons Watched',
            '50 Lessons Watched',
            'First Comment Written',
            '3 Comments Written',
            '5 Comments Written',
            '10 Comments Written',
            '20 Comments Written',
        ];

        // Calculate the next available achievements by filtering out those already unlocked
        $nextAvailableAchievements = array_diff($availableAchievements, $unlockedAchievements);
        
        return $nextAvailableAchievements;
    }

    private function calculateNextBadge($unlockedAchievements)
    {
        // Define badge criteria and their corresponding achievement counts
        $badgeCriteria = [
            'Beginner' => 0,
            'Intermediate' => 4,
            'Advanced' => 8,
            'Master' => 10,
        ];

        // Determine the user's current badge based on unlocked achievements
        $currentBadge = 'Beginner';
        foreach ($badgeCriteria as $badge => $achievementCount) {
            if (count($unlockedAchievements) >= $achievementCount) {
                $currentBadge = $badge;
            } else {
                break; // Exit loop when the user's count no longer matches the badge criteria
            }
        }

        // Calculate the next badge
        $nextBadge = null;
        foreach ($badgeCriteria as $badge => $achievementCount) {
            if (count($unlockedAchievements) < $achievementCount) {
                $nextBadge = $badge;
                break; // The first badge not yet achieved is the next badge
            }
        }

        // Calculate remaining achievements to unlock the next badge
        $remainingToUnlockNextBadge = $badgeCriteria[$nextBadge] - count($unlockedAchievements);

        return [$nextBadge, $remainingToUnlockNextBadge];
    }
}
