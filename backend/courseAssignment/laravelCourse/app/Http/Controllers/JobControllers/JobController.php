<?php
namespace App\Http\Controllers\JobControllers;
use App\Http\Controllers\Controller;
use App\Models\table_jobs_uploading;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\user_application;
use Illuminate\Support\Facades\DB;
use App\Models\user_application;

class JobController extends Controller
{
 
    public function getRecruiterId(Request $request)
    {
        $email = $request->query('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['creator_id' => $user->id]);
    }

    public function index()
    {
        try {
            $jobs = table_jobs_uploading::all();

            if ($jobs->count() > 0) {
                return response()->json([
                    'status' => 200,
                    'length' => $jobs->count(),
                    'jobs' => $jobs
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'jobs' => 'No jobs found'
                ], 404);
            }

        } catch (\Exception $e) {
            // Log the exact error for debugging
            \Log::error('Error fetching jobs: ' . $e->getMessage());

            // Return the error message in the response
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getJobsByCreator(Request $request)
    {
        $email = $request->query('email');
        $status = $request->query('status'); // optional

        // Email is required
        if (!$email) {
            return response()->json([
                'status' => 400,
                'message' => 'Email is required as a query parameter.',
            ], 400);
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found.',
            ], 404);
        }

        // Build query
        $query = table_jobs_uploading::where('creator', $user->id);

        // If status is provided, filter by it
        if ($status) {
            $query->where('status', $status);
        }

        $jobs = $query->get();

        return response()->json([
            'status' => 200,
            'message' => 'Jobs retrieved successfully.',
            'jobs' => $jobs,
        ]);
    }

    // public function getJobsByCreator(Request $request)
    // {
    //     $email = $request->query('email');
    //     $status = $request->query('status');

    //     if (!$email || !$status) {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Email and status are required as query parameters.',
    //         ], 400);
    //     }

    //     $user = User::where('email', $email)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'User not found.',
    //         ], 404);
    //     }

    //     $jobs = table_jobs_uploading::where('creator', $user->id)
    //         ->where('status', $status)
    //         ->get();

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Jobs retrieved successfully.',
    //         'jobs' => $jobs,
    //     ]);
    // }



    public function store(Request $request)
    {
        try {
            $request->validate([
                // 'email' => 'required|email|exists:user,email',
                // 'jobTitle' => $request->jobTitle,
                // 'employmentType' => $request->employmentType,
                // 'location' => $request->location,
                // 'salary' => $request->salary,
                // 'description' => $request->description,
                // 'companyName' => $request->companyName,
                // 'requirements' => $request->requirements,

                // 'status' => $request->status,
                'email' => 'required|email|exists:user,email',
                'jobTitle' => 'required|string',
                'employmentType' => 'required|string',
                'location' => 'required|string',
                'salary' => 'required|string',
                'description' => 'required|string',
                'companyName' => 'required|string',
                'requirements' => 'required|string',
                'status' => 'required|string|in:open,closed'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || $user->role !== 'recruiter') {
                return response()->json([
                    'status' => 403,
                    'message' => 'Only recruiters can create jobs.',
                ], 403);
            }

            $job = table_jobs_uploading::create([
                'jobTitle' => $request->jobTitle,
                'employmentType' => $request->employmentType,
                'location' => $request->location,
                'salary' => $request->salary,
                'description' => $request->description,
                'creator' => $user->id,
                'status' => $request->status,
                'companyName' => $request->companyName,
                'requirements' => $request->requirements,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Job created successfully',
                'data' => $job,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    // public function updateJob(Request $request, $jobId)
    // {
    //     $request->validate([
    //         'creator_id' => 'required|integer|exists:user,id',
    //         'jobTitle' => 'nullable|string',
    //         'employmentType' => 'nullable|string',
    //         'location' => 'nullable|string',
    //         'salary' => 'nullable|string',
    //         'description' => 'nullable|string',
    //         'status' => 'nullable|string',
    //     ]);

    //     $user = User::find($request->creator_id);

    //     if (!$user || $user->role !== 'recruiter') {
    //         return response()->json([
    //             'status' => 403,
    //             'message' => 'Only recruiters can update jobs.',
    //         ], 403);
    //     }

    //     $job = table_jobs_uploading::find($jobId);

    //     if (!$job) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Job not found.',
    //         ], 404);
    //     }

    //     if ($job->creator !== $user->id) {
    //         return response()->json([
    //             'status' => 403,
    //             'message' => 'You are not the creator of this job.',
    //         ], 403);
    //     }

    //     $job->update($request->only([
    //         'jobTitle',
    //         'employmentType',
    //         'location',
    //         'salary',
    //         'description',
    //         'status',
    //     ]));

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Job updated successfully.',
    //         'data' => $job,
    //     ], 200);
    // }


    public function allJobs(Request $request)
    {
        try {
            $status = $request->query('status');

            $query = table_jobs_uploading::query();

            if ($status && strtolower($status) !== 'all') {
                $query->where('status', $status);
            }

            $jobs = $query->get();

            return response()->json([
                'status' => 200,
                'length' => $jobs->count(),
                'jobs' => $jobs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateJob(Request $request, $jobId)
    {
        $request->validate([
            'creator_id' => 'required|integer|exists:user,id',
            'jobTitle' => 'nullable|string',
            'employmentType' => 'nullable|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|string',
            'description' => 'nullable|string',
            'companyName' => 'nullable|string',
            'requirements' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $user = User::find($request->creator_id);

        if (!$user || $user->role !== 'recruiter') {
            return response()->json([
                'status' => 403,
                'message' => 'Only recruiters can update jobs.',
            ], 403);
        }

        $job = table_jobs_uploading::find($jobId);

        if (!$job) {
            return response()->json([
                'status' => 404,
                'message' => 'Job not found.',
            ], 404);
        }

        if ($job->creator !== $user->id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not the creator of this job.',
            ], 403);
        }

        $job->update($request->only([
            'jobTitle',
            'employmentType',
            'location',
            'salary',
            'description',
            'companyName',
            'requirements',
            'status',
        ]));

        return response()->json([
            'status' => 200,
            'message' => 'Job updated successfully.',
            'data' => $job,
        ], 200);
    }
    // public function updateJob(Request $request, $jobId)
    // {
    //     $request->validate([
    //         'creator_id' => 'required|integer|exists:user,id',
    //         'jobTitle' => 'nullable|string',
    //         'employmentType' => 'nullable|string',
    //         'location' => 'nullable|string',
    //         'salary' => 'nullable|string',
    //         'description' => 'nullable|string',
    //         'companyName' => 'nullable|string',
    //         'requirements' => 'nullable|string',
    //         'status' => 'nullable|string',
    //     ]);

    //     $user = User::find($request->creator_id);

    //     if (!$user || $user->role !== 'recruiter') {
    //         return response()->json([
    //             'status' => 403,
    //             'message' => 'Only recruiters can update jobs.',
    //         ], 403);
    //     }

    //     $job = table_jobs_uploading::find($jobId);

    //     if (!$job) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Job not found.',
    //         ], 404);
    //     }

    //     if ($job->creator !== $user->id) {
    //         return response()->json([
    //             'status' => 403,
    //             'message' => 'You are not the creator of this job.',
    //         ], 403);
    //     }

    //     $job->update($request->only([
    //         'jobTitle',
    //         'employmentType',
    //         'location',
    //         'salary',
    //         'description',
    //         'companyName',
    //         'requirements',
    //         'status',
    //     ]));

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Job updated successfully.',
    //         'data' => $job,
    //     ], 200);
    // }

    // public function applyForJob(Request $request)
    // {
    //     try {
    //         // Validate incoming request data
    //         $request->validate([
    //             'job_id' => 'required|exists:postejob,id', // Validate job exists
    //             'email' => 'required|email|exists:user,email', // Validate email exists
    //         ]);

    //         // Fetch user by email
    //         $user = User::where('email', $request->email)->first();

    //         // Check if user is not found
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'User not found.',
    //             ], 404);
    //         }

    //         // Fetch job by job_id
    //         $job = table_jobs_uploading::find($request->job_id);

    //         // Check if job is not found
    //         if (!$job) {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'Job not found.',
    //             ], 404);
    //         }

    //         // Check if user has already applied for this job
    //         $existingApplication = user_application::where('applicant', $user->id)
    //             ->where('jobApplied', $job->id)
    //             ->first();

    //         if ($existingApplication) {
    //             return response()->json([
    //                 'status' => 400,
    //                 'message' => 'You have already applied for this job.',
    //             ], 400);
    //         }

    //         // Create a new application entry
    //         $application = user_application::create([
    //             'applicant' => $user->id,
    //             'jobApplied' => $job->id,
    //             'appliedAt' => now(), // or Carbon::now()
    //             'status' => 'pending',
    //         ]);

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'You have successfully applied for the job.',
    //             'application' => $application,
    //         ], 200);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Handle validation errors
    //         return response()->json([
    //             'status' => 422,
    //             'message' => 'Validation failed.',
    //             'errors' => $e->errors(),
    //         ], 422);

    //     } catch (\Exception $e) {
    //         // Catch any other exceptions
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'An error occurred.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    // Update with your actual Job model class

    // public function deleteJob(Request $request)
    // {
    //     try {
    //         // Validate input
    //         $request->validate([
    //             'job_id' => 'required|exists:,id',
    //             'email' => 'required|email|exists:user,email',
    //         ]);

    //         // Find user by email
    //         $user = User::where('email', $request->email)->first();

    //         // Check if user is a recruiter
    //         if ($user->role !== 'recruiter') {
    //             return response()->json([
    //                 'status' => 403,
    //                 'message' => 'Only recruiters are allowed to delete jobs.',
    //             ], 403);
    //         }

    //         // Find the job
    //         $job = table_jobs_uploading::find($request->job_id);

    //         // Check if the job was created by this user
    //         if ($job->creator != $user->id) {
    //             return response()->json([
    //                 'status' => 403,
    //                 'message' => 'You are not the creator of this job.',
    //             ], 403);
    //         }

    //         // Delete the job
    //         $job->delete();

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Job deleted successfully.',
    //         ]);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => 422,
    //             'message' => 'Validation failed.',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'An error occurred.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    public function applyForJob(Request $request)
    {
        // Log all incoming request data
        \Log::info('Job Application Request Data:', $request->all());

        try {
            // Fetch user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found with provided email.',
                ], 404);
            }

            // Fetch job by job_id
            $job = table_jobs_uploading::find($request->job_id);

            if (!$job) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Job not found with provided job_id.',
                ], 404);
            }

            // Check if user has already applied for this job
            $existingApplication = user_application::where('applicant', $user->id)
                ->where('jobApplied', $job->id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'status' => 400,
                    'message' => 'You have already applied for this job.',
                ], 400);
            }

            // Create a new application entry
            $application = user_application::create([
                'applicant' => $user->id,
                'jobApplied' => $job->id,
                'appliedAt' => now(),
                'name' => $request->name,
                'experience' => $request->experience,
                'contactNumber' => $request->contactNumber,
                'resumeLink' => $request->resumeLink,
                'expectations' => $request->expectations,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'You have successfully applied for the job.',
                'application' => $application,
            ], 200);

        } catch (\Exception $e) {
            // Log the error too, for debugging
            \Log::error('Error in applyForJob:', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function deleteJob(Request $request)
    {
        try {

            Log::info('Delete Job Request:', $request->all());

            // Validate input: job_id must exist in jobs, email must exist in users table
            $request->validate([
                'job_id' => 'required|exists:table_jobs_uploading,id',
                'email' => 'required|email|exists:user,email',
            ]);

            // Find the job
            $job = table_jobs_uploading::find($request->job_id);
            if (!$job) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Job not found.',
                ], 404);
            }

            // Find user by email
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found.',
                ], 404);
            }

            // Check if this user is the creator of the job
            if ($job->creator !== $user->id) {
                return response()->json([
                    'status' => 403,
                    'message' => 'You are not authorized to delete this job.',
                ], 403);
            }

            // Delete the job
            $job->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Job deleted successfully.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());

            return response()->json([
                'status' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Delete job failed:', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while deleting the job.',
                'error' => $e->getMessage(), // only for debugging
            ], 500);
        }
    }
    public function getAllApplications()
    {
        try {
            // Fetch all job applications from the database
            $applications = user_application::all();

            return response()->json([
                'status' => 200,
                'message' => 'Applications retrieved successfully.',
                'length' => $applications->count(),
                'applications' => $applications
            ], 200);

        } catch (\Throwable $e) {
            // Log the full error in Laravel logs (storage/logs/laravel.log)
            \Log::error('Error fetching applications', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return error in response body (visible in Postman)
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching applications.',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // public function getApplicationsByEmail(Request $request)
    // {
    //     try {
    //         // Validate the email input
    //         $request->validate([
    //             'email' => 'required|email|exists:user,email', // Ensure email exists in the user table
    //         ]);

    //         // Fetch the user by email
    //         $user = User::where('email', $request->email)->first();

    //         // Start the query to fetch applications for this user
    //         $query = user_application::where('applicant', $user->id);

    //         // Check if 'status' is provided in the query parameters
    //         if ($request->has('status') && $request->status) {
    //             // If a status is provided, filter applications by that status
    //             $query->where('status', $request->status);
    //         }

    //         // Fetch the applications based on the query
    //         $applications = $query->get();

    //         // Return the response
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Applications retrieved successfully.',
    //             'applications' => $applications
    //         ], 200);

    //     } catch (\Throwable $e) {
    //         // Log the full error in Laravel logs (storage/logs/laravel.log)
    //         \Log::error('Error fetching applications by email', [
    //             'message' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         // Return error in response body (visible in Postman)
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'An error occurred while fetching applications.',
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine()
    //      


    // public function getApplicationsByEmail(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|email|exists:user,email',
    //         ]);

    //         $user = User::where('email', $request->email)->first();

    //         $query = user_application::where('applicant', $user->id);

    //         if ($request->has('status') && $request->status) {
    //             $query->where('status', $request->status);
    //         }

    //         // Eager load related job and creator
    //         $applications = $query->with(['job.creator', 'applicant'])->get();

    //         $formatted = $applications->map(function ($app) {
    //             return [
    //                 'id' => $app->id,
    //                 'job_title' => $app->job->jobTitle ?? 'N/A',
    //                 'job_creator' => $app->job->creator->name ?? 'N/A',
    //                 'applicant_name' => $app->applicant->name ?? 'N/A',
    //                 'appliedAt' => $app->appliedAt,
    //                 'status' => $app->status,
    //                 'created_at' => $app->created_at,
    //                 'updated_at' => $app->updated_at,
    //             ];
    //         });

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Applications retrieved successfully.',
    //             'applications' => $formatted,
    //         ]);

    //     } catch (\Throwable $e) {
    //         \Log::error('Error fetching applications by email', [
    //             'message' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //         ]);

    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'An error occurred while fetching applications.',
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //         ]);
    //     }
    // }


    public function getApplicationsByEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:user,email',
            ]);

            $user = User::where('email', $request->email)->first();

            $query = user_application::where('applicant', $user->id);

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Eager load related job and job.creator and user (applicant)
            $applications = $query->with(['job.creator', 'user'])->get();

            $formatted = $applications->map(function ($app) {
                return [
                    'id' => $app->id,
                    'job_title' => $app->job->jobTitle ?? 'N/A',
                    'job_creator' => $app->job->creator->name ?? 'N/A',
                    'applicant_name' => $app->user->name ?? 'N/A',  // <-- use 'user' here
                    'appliedAt' => $app->appliedAt,
                    'status' => $app->status ?? 'N/A',
                    'created_at' => $app->created_at,
                    'updated_at' => $app->updated_at,
                ];
            });

            return response()->json([
                'status' => 200,
                'message' => 'Applications retrieved successfully.',
                'applications' => $formatted,
            ]);

        } catch (\Throwable $e) {
            \Log::error('Error fetching applications by email', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching applications.',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

}
