<?php
namespace App\Http\Controllers\UserControllers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $Users = User::all();
        if ($Users->count() > 0) {
            return response()->json([
                'status' => 200,
                'length' => $Users->count(),
                'users' => $Users
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'users' => 'not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        Log::info('Request Data:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6',
            'gender' => 'required|string',
            'role' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),  // Make sure to hash passwords!
                'gender' => $request->gender,
                'role' => $request->role,
            ]);

            // Generate random 9-digit number
            $randomNumber = mt_rand(100000000, 999999999);

            Log::info('User created successfully', ['user' => $user, 'random_number' => $randomNumber]);

            return response()->json([
                'message' => 'User created successfully!',
                'status' => 200,
                'data' => $user,
                'token' => $randomNumber
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     Log::info('Request Data:', $request->all());

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:user,email',
    //         'password' => 'string',
    //         'gender' => 'string',
    //         'role' => 'string'
    //     ]);

    //     if ($validator->fails()) {
    //         Log::error('Validation failed:', $validator->errors()->toArray());
    //         return response()->json([
    //             'errors' => $validator->errors()
    //         ], 400);
    //     }

    //     try {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => $request->password,
    //             'gender' => $request->gender,
    //             'role' => $request->role,
    //         ]);

    //         Log::info('User created successfully', ['user' => $user]);

    //         return response()->json([
    //             'message' => 'User created successfully!',
    //             "status" => 200,
    //             'data' => $user
    //         ], 201);
    //     } catch (\Exception $e) {
    //         Log::error('Error creating user: ' . $e->getMessage());
    //         return response()->json([
    //             'message' => 'Error creating user',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    // public function store(Request $request)
    // {
    //     Log::info('Request Data:', $request->all());

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:user,email',
    //         'password' => 'required|string|min:6',
    //         'gender' => 'required|string',
    //         'role' => 'required|string'
    //     ]);

    //     if ($validator->fails()) {
    //         Log::error('Validation failed:', $validator->errors()->toArray());
    //         return response()->json([
    //             'errors' => $validator->errors()
    //         ], 400);
    //     }

    //     try {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => $request->password,
    //             'gender' => $request->gender,
    //             'role' => $request->role,
    //         ]);

    //         Log::info('User created successfully', ['user' => $user]);

    //         return response()->json([
    //             'message' => 'User created successfully!',
    //             'status' => 200,
    //             'data' => $user
    //         ], 201);
    //     } catch (\Exception $e) {
    //         Log::error('Error creating user: ' . $e->getMessage());
    //         return response()->json([
    //             'message' => 'Error creating user',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function update(Request $request, $id)
    {
        Log::info('Request Data for Update:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:user,email,' . $id, // Ensure the email is unique but allow the current one to remain unchanged
            'password' => 'nullable|string|min:8', // Password should be string and has a min length of 8
            'gender' => 'nullable|string',
            'role' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::find($id);

            if (!$user) {
                // Return 404 if the user is not found
                return response()->json([
                    'message' => 'User not found',
                    'status' => 404
                ], 404);
            }

            // Update the user's data, only updating fields provided in the request
            $user->name = $request->name ?: $user->name;
            $user->email = $request->email ?: $user->email;
            $user->password = $request->password ? bcrypt($request->password) : $user->password;
            $user->gender = $request->gender ?: $user->gender;
            $user->role = $request->role ?: $user->role;

            // Save the updated user
            $user->save();

            Log::info('User updated successfully', ['user' => $user]);

            return response()->json([
                'message' => 'User updated successfully!',
                'status' => 200,
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function destroy($id)
    {
        try {

            $user = User::find($id);


            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $user->delete();


            Log::info('User deleted successfully', ['user_id' => $id]);

            return response()->json([
                'message' => 'User deleted successfully',
                'status' => 200,
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error deleting user: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            // Log the successful retrieval
            Log::info('User retrieved successfully', ['user_id' => $id]);

            return response()->json([
                'message' => 'User retrieved successfully',
                'status' => 200,
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            // Log any errors
            Log::error('Error retrieving user: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error retrieving user',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // public function login(Request $request)
    // {
    //     // Validate email and password inputs
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     // Attempt to find the user
    //     $user = User::where('email', $credentials['email'])->first();
    //     $randomNumber = mt_rand(100000000, 999999999);
    //     // If user not found or password mismatch, return error
    //     if (
    //         !$user
    //         //  || !Hash::check($credentials['password'], $user->password)
    //     ) {
    //         return response()->json([
    //             'message' => 'Invalid email or password.'
    //         ], 401);
    //     }

    //     // On success, return a JSON success message (you could also create a token here)
    //     return response()->json([
    //         'message' => 'Login successful.',
    //         'data' => $user,
    //         'token' => $randomNumber
    //     ], 200);
    // }


    public function login(Request $request)
    {
        // Validate email and password inputs
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to find the user
        $user = User::where('email', $credentials['email'])->first();

        // If user not found or password mismatch, return error
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $randomNumber = mt_rand(100000000, 999999999);

        // On success, return a JSON success message (you could also create a token here)
        return response()->json([
            'message' => 'Login successful.',
            'data' => $user,
            'token' => $randomNumber
        ], 200);
    }

    public function logout()
    {
        return response()->json([
            'message' => 'Logged out successfully.'
        ], 200);
    }


}
