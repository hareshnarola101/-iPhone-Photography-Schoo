<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UnlockAchievementHandler
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event)
    {
        
        $achievementName = $event->achievementName;
        $user = $event->user;
       
        $user->achievements()->create([
            'achievement_name' => $achievementName,
        ]);

        $user->updateBadges();
    }
}
