<?php

use App\Http\Controllers\Common\V1\AdminController;
use App\Http\Controllers\Common\V1\SelectableController;
use App\Http\Controllers\Common\V1\StudentController;
use App\Http\Controllers\Common\V1\TeacherController;
use App\Http\Controllers\Common\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(SelectableController::class)->group(function () {
    Route::get('selectables/book-subjects', 'selectBookSubjects');
    Route::get('selectables/grades', 'selectGrade');
    Route::get('selectables/admins', 'selectAdmin');
    Route::get('selectables/students', 'selectStudent');
    Route::get('selectables/teachers', 'selectTeacher');
});

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'getAllUsers');
    Route::get('users/{userId}', 'getUserById');
    Route::post('users', 'createUser');
    Route::put('users/{userId}', 'editUser');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('admins', 'getAllAdmins');
    Route::get('admins/{adminId}', 'getAdminById');
    Route::post('admins', 'createAdmin');
    Route::put('admins/{adminId}', 'editAdmin');
});

Route::controller(StudentController::class)->group(function () {
    Route::get('students', 'getAllStudents');
    Route::get('students/{studentId}', 'getStudentById');
    Route::post('students', 'createStudent');
    Route::put('students/{studentId}', 'editStudent');
});

Route::controller(TeacherController::class)->group(function () {
    Route::get('teachers', 'getAllTeachers');
    Route::get('teachers/{teacherId}', 'getTeacherById');
    Route::post('teachers', 'createTeacher');
    Route::put('teachers/{teacherId}', 'editTeacher');
});
