<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Events\CommentWritten;

class CommentController extends Controller
{
    public function write(Request $request)
    {
        // Retrieve the lesson_id and comment from the request data
        $validated = $request->validate([
            'comment' => 'required',
        ]);

        $body = $request->input('comment');
        // $userId = $request->user_id;

        // Retrieve the authenticated user
        $user = auth()->user();
        // $user = User::find($userId);
        if(!$user){
            return response()->json(['error' => 'User not authorized.'],401);
        }
        
        
        // Implement logic to store the comment for the authenticated user
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = $body;
        $comment->save();
        

        // Dispatch the CommentWritten event
        event(new CommentWritten($comment, $user));

        // Return a response indicating success
        return response()->json(['message' => 'Comment written successfully']);
    }
}
