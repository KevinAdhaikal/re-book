<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SocialController extends Controller
{
    /**
     * Toggle Like / Reaksi pada Ulasan Member
     */
    public function toggleReviewLike(Request $request, $reviewId)
    {
        $user = Auth::user();
        $review = Review::findOrFail($reviewId);

        $existing = DB::table('review_likes')
            ->where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();

        if ($existing) {
            DB::table('review_likes')
                ->where('user_id', $user->id)
                ->where('review_id', $review->id)
                ->delete();
            $liked = false;
            $message = 'Batal menyukai ulasan.';
        } else {
            DB::table('review_likes')->insert([
                'user_id' => $user->id,
                'review_id' => $review->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $liked = true;
            $message = 'Ulasan berhasil disukai!';
        }

        $likesCount = DB::table('review_likes')->where('review_id', $review->id)->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle Follow / Unfollow antar Member
     */
    public function toggleFollow(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $targetUser = User::findOrFail($userId);

        if ($currentUser->id === $targetUser->id) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengikuti diri sendiri.',
                ], 422);
            }
            return back()->with('error', 'Anda tidak dapat mengikuti diri sendiri.');
        }

        $isFollowing = $currentUser->isFollowing($targetUser->id);

        if ($isFollowing) {
            $currentUser->following()->detach($targetUser->id);
            $following = false;
            $message = "Berhenti mengikuti {$targetUser->name}.";
        } else {
            $currentUser->following()->attach($targetUser->id);
            $following = true;
            $message = "Sekarang Anda mengikuti {$targetUser->name}!";
        }

        $followersCount = $targetUser->followers()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'following' => $following,
                'followers_count' => $followersCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
