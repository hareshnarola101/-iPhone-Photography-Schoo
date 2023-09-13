<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\User;
use App\Events\LessonWatched;

class LessonController extends Controller
{
    public function watch(Request $request)
    {
        
        // Retrieve the lesson_id from the request data
        $validated = $request->validate([
            'lesson_id' => 'required',
        ]);

        $lessonId = $request->input('lesson_id');
        // $userId = $request->user_id;
        
        // Retrieve the authenticated user
        $user = auth()->user();
        // $user = User::find($userId);

        if(!$user){
            return response()->json(['error' => 'User not authorized.'],401);
        }
        
        // Check if the lesson exists
        $lesson = Lesson::where('id', $lessonId)->first();
        
        if (!$lesson) {
            return response()->json(['error' => 'Lesson not found'], 404);
        }
        
        $user->watched()->attach($lessonId,['watched' => 1]);

        event(new LessonWatched($lesson, $user));

        
        // Return a response indicating success
        return response()->json(['message' => 'Lesson watched successfully']);
    }
}
