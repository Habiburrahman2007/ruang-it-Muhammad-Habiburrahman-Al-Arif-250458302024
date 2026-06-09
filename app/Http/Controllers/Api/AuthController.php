<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'profession' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profession' => $request->profession,
            'password' => Hash::make($request->password),
            'slug' => Str::slug($request->name) . '-' . uniqid(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial yang diberikan tidak cocok dengan data kami.'
            ], 401);
        }

        if ($user->banned) {
            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->loadCount('articles');

        $articleIds = \App\Models\Article::where('user_id', $user->id)->pluck('id');

        $totalLikesReceived = \App\Models\Like::whereIn('article_id', $articleIds)->count();
        $totalCommentsReceived = \App\Models\Comment::whereIn('article_id', $articleIds)->count();

        // Gunakan toArray() dengan append agar photo_profile_url ikut terkirim
        $userData = $user->toArray();
        $userData['photo_profile_url'] = $user->photo_profile_url;

        return response()->json([
            'data' => array_merge($userData, [
                'total_likes'    => $totalLikesReceived,
                'comments_count' => $totalCommentsReceived,
            ])
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'profession' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo_profile' => $request->hasFile('photo_profile') ? 'image|mimes:jpg,jpeg,png,webp|max:2048' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only('name', 'profession', 'bio');

        $shouldRemovePhoto = $request->input('remove_photo') === '1' 
            || $request->input('delete_photo') === '1' 
            || $request->input('remove_photo_profile') === '1';

        if ($shouldRemovePhoto) {
            // Hapus foto lama jika ada
            if ($user->photo_profile && !filter_var($user->photo_profile, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $data['photo_profile'] = null;
        } elseif ($request->hasFile('photo_profile')) {
            // Hapus foto lama jika ada
            if ($user->photo_profile && !filter_var($user->photo_profile, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->photo_profile);
            }

            $extension = $request->file('photo_profile')->getClientOriginalExtension();
            $filename = uniqid('profile_', true) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $data['photo_profile'] = $request->file('photo_profile')->storeAs('profile-photos', $filename, 'public');
        }

        $user->update($data);
        $user->refresh(); // reload agar photo_profile_url ter-generate ulang

        return response()->json([
            'message' => 'Profile updated successfully',
            'data'    => array_merge($user->toArray(), [
                'photo_profile_url' => $user->photo_profile_url,
            ]),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
