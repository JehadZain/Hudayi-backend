<?php

namespace App\Http\Controllers\App\V1;

use App\Models\Users\Admin;
use App\Models\Users\Student;
use App\Models\Users\Teacher;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AppAuthController extends Controller
{
    private $min;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
        $this->middleware('jwt.refresh', ['only' => ['refresh']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Wrong Email or Password'], 401);
        }

        $user = auth()->user();
        $current_user_id = $user->id;
        $student = Student::where('user_id', $current_user_id)->first();
        $teacher = Teacher::where('user_id', $current_user_id)->first();
        $admin = Admin::where('user_id', $current_user_id)->first();

        $orgAdmin = $admin?->organizationAdmins()->first();
        $branchAdmin = $admin?->branchAdmins()->first();
        $propertyAdmin = $admin?->propertyAdmins()->first();

        if ($student) {
            $classRoom = $student->classRooms->first(function ($classRoom) {
                return $classRoom->pivot->left_at === null;
            });
            $grade = $classRoom?->grade;
            $property = $grade?->property;
            $branch = $property?->branch;

            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => 'student',
                    'student_id' => $student->id,
                    'Belongs_to' => [
                        'class_room_id' => $classRoom?->id,
                        'grade_id' => $grade?->id,
                        'property_id' => $property?->id,
                        'branch_id' => $property?->branch_id,
                        'organization_id' => $branch?->organization_id,

                    ],
                ],
            ]);
        }

        if ($teacher) {
            $classRoom = $teacher->classRooms->first(function ($classRoom) {
                return $classRoom->pivot->left_at === null;
            });
            $grade = $classRoom?->grade;
            $property = $grade?->property;
            $branch = $property?->branch;

            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => 'teacher',
                    'teacher_id' => $teacher?->id,
                    'Belongs_to' => [
                        'class_room_id' => $classRoom?->id,
                        'grade_id' => $grade?->id,
                        'property_id' => $property?->id,
                        'branch_id' => $property?->branch_id,
                        'organization_id' => $branch?->organization_id,
                    ],
                ],
            ]);
        }

        if ($propertyAdmin) {
            $branch = $propertyAdmin?->property?->branch()->first();
            $org = $branch?->organization;

            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => 'property_admin',
                    'admin_id' => $admin?->id,
                    'Belongs_to' => [
                        'class_room_id' => null, //class_room_id
                        'grade_id' => null,
                        'property_id' => $propertyAdmin?->property_id, //property_id
                        'branch_id' => $branch?->id,
                        'organization_id' => $org?->id,
                    ],
                ],
            ]);
        }

        if ($branchAdmin) {
            $org = $branchAdmin->branch->organization()->first();

            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => 'branch_admin',
                    'admin_id' => $admin?->id,
                    'Belongs_to' => [
                        'class_room_id' => null, //class_room_id
                        'grade_id' => null,
                        'property_id' => null, //property_id
                        'branch_id' => $branchAdmin?->branch_id,
                        'organization_id' => $org?->id,
                    ],
                ],
            ]);
        }

        if ($orgAdmin) {
            return response()->json([
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'role' => 'org_admin',
                    'admin_id' => $admin?->id,
                    'Belongs_to' => [
                        'class_room_id' => null, //class_room_id
                        'grade_id' => null,
                        'property_id' => null, //property_id
                        'branch_id' => null,
                        'organization_id' => $orgAdmin?->organization_id,
                    ],
                ],
            ]);
        }

        //        if ($admin) {
        //            return response()->json([
        //                'data' => [
        //                    'user' => $user,
        //                    'token' => $token,
        //                    'role' => 'admin',
        //                    'admin_id' => $admin?->id,
        //                    'Belongs_to' => [
        //                        'class_room_id' => null, //class_room_id
        //                        'grade_id' => null,
        //                        'property_id' => null, //property_id
        //                        'branch_id' => null,
        //                    ]
        //                ]
        //            ]);
        //        }

        return response()->json(['message' => 'Unknown Role'], 400);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);

    }

    public function refresh()
    {
        $user = auth('api')->user();

        return response()->json(['data' => $user]);

    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
