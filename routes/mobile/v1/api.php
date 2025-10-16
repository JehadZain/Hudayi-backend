<?php

use App\Http\Controllers\Mobile\V1\MobileActivityController;
use App\Http\Controllers\Mobile\V1\MobileActivityParticipantsController;
use App\Http\Controllers\Mobile\V1\MobileActivityTypeController;
use App\Http\Controllers\Mobile\V1\MobileAdminController;
use App\Http\Controllers\Mobile\V1\MobileAnalyticsController;
use App\Http\Controllers\Mobile\V1\MobileAttendanceController;
use App\Http\Controllers\Mobile\V1\MobileAuthController;
use App\Http\Controllers\Mobile\V1\MobileBookController;
use App\Http\Controllers\Mobile\V1\MobileBranchAdminController;
use App\Http\Controllers\Mobile\V1\MobileBranchController;
use App\Http\Controllers\Mobile\V1\MobileCalendarController;
use App\Http\Controllers\Mobile\V1\MobileCertificationController;
use App\Http\Controllers\Mobile\V1\MobileClassRoomController;
use App\Http\Controllers\Mobile\V1\MobileClassRoomStudentController;
use App\Http\Controllers\Mobile\V1\MobileClassRoomTeacherController;
use App\Http\Controllers\Mobile\V1\MobileGradeController;
use App\Http\Controllers\Mobile\V1\MobileInterviewController;
use App\Http\Controllers\Mobile\V1\MobileJobTitleController;
use App\Http\Controllers\Mobile\V1\MobileMosqueController;
use App\Http\Controllers\Mobile\V1\MobileNoteController;
use App\Http\Controllers\Mobile\V1\MobileOrganizationAdminController;
use App\Http\Controllers\Mobile\V1\MobileOrganizationController;
use App\Http\Controllers\Mobile\V1\MobilePatientController;
use App\Http\Controllers\Mobile\V1\MobilePatientTreatmentController;
use App\Http\Controllers\Mobile\V1\MobilePropertyAdminController;
use App\Http\Controllers\Mobile\V1\MobilePropertyController;
use App\Http\Controllers\Mobile\V1\MobileQuizController;
use App\Http\Controllers\Mobile\V1\MobileQuranQuizController;
use App\Http\Controllers\Mobile\V1\MobileRateController;
use App\Http\Controllers\Mobile\V1\MobileRateTypeController;
use App\Http\Controllers\Mobile\V1\MobileReferenceController;
use App\Http\Controllers\Mobile\V1\MobileReportContentController;
use App\Http\Controllers\Mobile\V1\MobileReportController;
use App\Http\Controllers\Mobile\V1\MobileReportReviewerController;
use App\Http\Controllers\Mobile\V1\MobileReportTypeController;
use App\Http\Controllers\Mobile\V1\MobileSchoolController;
use App\Http\Controllers\Mobile\V1\MobileSessionAttendanceController;
use App\Http\Controllers\Mobile\V1\MobileSessionController;
use App\Http\Controllers\Mobile\V1\MobileStatusController;
use App\Http\Controllers\Mobile\V1\MobileStudentController;
use App\Http\Controllers\Mobile\V1\MobileSubjectController;
use App\Http\Controllers\Mobile\V1\MobileTeacherController;
use App\Http\Controllers\Mobile\V1\MobileUserController;
use App\Http\Controllers\Mobile\V1\MobileUserParentController;
use App\Jobs\TestingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::controller(MobileAuthController::class)
    ->group(function () {
        Route::get('refresh', 'refresh');
        Route::post('logout', 'logout');
        Route::post('login', 'login');
    });

Route::controller(MobileUserController::class)->group(function () {
    Route::get('users', 'mobileGetAllUsers');
    Route::get('users/pending', 'mobileAllUsersNotApproved');
    Route::get('users/{userId}', 'getUserById');
    Route::post('users', 'mobileCreateUser');
    Route::put('users/{userId}', 'mobileUpdateUser');
    Route::delete('users/{userId}', 'mobileDeleteUser');
});
Route::middleware(['auth:api', 'active', 'approved'])->group(function () {
    // all your existing routes here

    Route::controller(MobileUserParentController::class)->group(function () {
        Route::get('parents', 'mobileGetAllUserParents');
        Route::get('parents/{userParentId}', 'getUserParentById');
        Route::post('parents', 'mobileCreateUserParent');
        Route::put('parents/{userParentId}', 'mobileUpdateUserParent');
        Route::delete('parents/{userParentId}', 'mobileDeleteUserParent');
    });

    Route::controller(MobileAdminController::class)->group(function () {
        Route::get('admins', 'mobileGetAllAdmins');
        Route::get('admins/unassigned', 'mobileGetAllUnassignedAdmins');
        Route::get('admins/{adminId}', 'mobileGetAdminById');
        Route::post('admins', 'mobileCreateAdmin');
        Route::post('admins/{adminId}', 'mobileUpdateAdmin');
        Route::delete('admins/{adminId}', 'mobileDeleteAdmin');
    });

    Route::controller(MobileOrganizationAdminController::class)->group(function () {
        Route::get('organization/admins', 'mobileGetAllOrganizationsAdmin');
        Route::get('organization/admins/{organizationId}', 'mobileGetOrganizationAdminById');
        Route::post('organization/admins', 'mobileCreateOrganizationAdmin');
        Route::post('organization/admins/{organizationId}', 'mobileUpdateOrganizationAdmin');
        Route::delete('organization/admins/{organizationId}', 'mobileDeleteOrganizationAdmin');
    });

    Route::controller(MobileBranchAdminController::class)->group(function () {
        Route::get('branch/admins', 'mobileGetAllBranchAdmins');
        Route::get('branch/admins/{branchId}', 'mobileGetBranchAdminById');
        Route::post('branch/admins', 'mobileCreateBranchAdmin');
        Route::post('branch/admins-transfer', 'mobileTransferBranchAdmin');
        Route::put('branch/admins/{branchId}', 'mobileUpdateBranchAdmin');
        Route::delete('branch/admins/{branchId}', 'mobileDeleteBranchAdmin');

    });
    Route::controller(MobilePropertyAdminController::class)->group(function () {
        Route::get('property/admins', 'mobileGetAllPropertyAdmins');
        Route::get('property/admins/{propertyId}', 'mobileGetPropertyAdminById');
        Route::post('property/admins', 'mobileCreatePropertyAdmin');
        Route::post('property/admins-transfer', 'mobileTransferPropertyAdmin');
        Route::put('property/admins/{propertyId}', 'mobileUpdatePropertyAdmin');
        Route::delete('property/admins/{propertyId}', 'mobileDeletePropertyAdmin');

    });

    Route::controller(MobileJobTitleController::class)->group(function () {
        Route::get('job-titles', 'mobileGetAllJobTitles');
        Route::get('job-titles/{jobTitleId}', 'mobileGetJobTitleById');
        Route::post('job-title', 'mobileCreateJobTitle');
        Route::put('job-title/{jobTitleId}', 'mobileUpdateJobTitle');
        Route::delete('job-title/{jobTitleId}', 'mobileDeleteJobTitle');
    });

    Route::controller(MobileStudentController::class)->group(function () {
        Route::get('students', 'mobileGetAllStudents');
        Route::get('students/{studentId}', 'mobileGetStudentById');
        Route::get('student-statistics/{studentId}', 'studentStatistics');
        Route::get('all-student', 'mobileGetAllOrgStudents');
        Route::post('students', 'mobileCreateStudent');
        Route::post('students/{studentId}', 'mobileUpdateStudent');
        Route::post('students/transfer/{studentId}', 'mobileTransferStudent');
        Route::delete('students/{studentId}', 'mobileDeleteStudent');
        Route::get('students/{studentId}/student-groups', 'mobileGetStudentGroups');
    });

    Route::controller(MobileTeacherController::class)->group(function () {
        Route::get('teachers', 'mobileGetAllTeachers');
        Route::get('all-teachers', 'mobileGetAllOrgTeacher');
        Route::get('teachers/{teacherId}', 'mobileGetTeacherById');
        Route::post('teachers', 'mobileCreateTeacher');
        Route::post('teachers/{teacherId}', 'mobileUpdateTeacher');
        Route::post('teachers/transfer/{teacherId}', 'mobileTransferTeacher');
        Route::delete('teachers/{teacherId}', 'mobileDeleteTeacher');
        Route::get('teachers-statistics/{teacherId}', 'teacherStatistics');
    });

    Route::controller(MobileCertificationController::class)->group(function () {
        Route::get('certifications', 'mobileGetAllCertifications');
        Route::get('certifications/{certificationId}', 'mobileGetCertificationById');
        Route::post('certifications', 'mobileCreateCertification');
        Route::put('certifications/{certificationId}', 'mobileUpdateCertification');
        Route::delete('certifications/{certificationId}', 'mobileDeleteCertification');
    });

    Route::controller(MobilePatientController::class)->group(function () {
        Route::get('patients', 'mobileGetAllPatients');
        Route::get('patients/{patientId}', 'mobileGetPatientById');
        Route::post('patients', 'mobileCreatePatient');
        Route::put('patients/{patientId}', 'mobileUpdatePatient');
        Route::delete('patients/{patientId}', 'mobileDeletePatient');
    });

    Route::controller(MobilePatientTreatmentController::class)->group(function () {
        Route::get('treatments', 'mobileGetAllPatientTreatments');
        Route::get('treatments/{treatmentId}', 'mobileGetPatientTreatmentById');
        Route::post('treatments', 'mobileCreatePatientTreatment');
        Route::put('treatments/{treatmentId}', 'mobileUpdatePatientTreatment');
        Route::delete('treatments/{treatmentId}', 'mobileDeletePatientTreatment');
    });

    //Route::controller(MobileSchoolController::class)->group(function () {
    //    Route::get('properties/schools', 'mobileGetAllSchools');
    //    Route::get('properties/schools/{schoolId}', 'mobileGetSchoolById');
    //    Route::post('properties/schools', 'mobileCreateSchool');
    //    Route::put('properties/schools/{schoolId}', 'mobileUpdateSchool');
    //    Route::delete('properties/schools/{schoolId}', 'mobileDeleteSchool');
    //});
    //
    //Route::controller(MobileMosqueController::class)->group(function () {
    //    Route::get('properties/mosques', 'mobileGetAllMosques');
    //    Route::get('properties/mosques/{mosqueId}', 'mobileGetMosqueById');
    //    Route::post('properties/mosques', 'mobileCreateMosque');
    //    Route::put('properties/mosques/{mosqueId}', 'mobileUpdateMosque');
    //    Route::delete('properties/mosques/{mosqueId}', 'mobileDeleteMosque');
    //});

    Route::controller(MobilePropertyController::class)->group(function () {
        Route::get('properties', 'mobileGetAllProperties');
        Route::get('properties/{propertyId}', 'mobileGetPropertyById');
        Route::get('property-classes/{propertyId}', 'propertyClassRooms');
        Route::get('properties-mosques', 'mobileGetAllMosques');
        Route::get('properties-schools', 'mobileGetAllSchools');

        Route::get('property-statistics/all', 'allPropertyStatistics');
        Route::get('property-statistics/{propertyId}', 'mobilePropertyStatistics');
        Route::get('property-students/{propertyId}', 'mobileGetAllPropertyStudents');
        Route::get('property-teachers-and-admins/{propertyId}', 'mobileGetAllPropertyTeachers');
        Route::get('property-students-without-class-room/{propertyId}', 'getStudentsWithoutClassroomByPropertyId');
        Route::get('property-teachers-without-class-room/{propertyId}', 'getTeachersWithoutClassroomByPropertyId');
        Route::post('properties', 'mobileCreateProperty');
        Route::post('properties/{propertyId}', 'mobileUpdateProperty');
        Route::delete('properties/{propertyId}', 'mobileDeleteProperty');
    });

    Route::controller(MobileGradeController::class)->group(function () {
        Route::get('grades', 'mobileGetAllGrades');
        Route::get('grades/{gradeId}', 'mobileGetGradeById');
        Route::get('grades-statistics/{gradeId}', 'mobileGetGradeStatistics');
        Route::post('grades', 'mobileCreateGrade');
        Route::put('grades/{gradeId}', 'mobileUpdateGrade');
        Route::delete('grades/{gradeId}', 'mobileDeleteGrade');
    });

    Route::controller(MobileActivityTypeController::class)->group(function () {
        Route::get('activity-types', 'mobileGetAllActivityTypes');
        Route::get('activity-types/{activityTypeId}', 'mobileGetActivityTypeById');
        Route::post('activity-types', 'mobileCreateActivityType');
        Route::post('activity-types/{activityTypeId}', 'mobileUpdateActivityType');
        Route::delete('activity-types/{activityTypeId}', 'mobileDeleteActivityType');
    });

    Route::controller(MobileActivityParticipantsController::class)->group(function () {
        Route::get('activity-participants', 'mobileGetAllActivityParticipants');
        Route::get('activity-participants/{activityParticipantId}', 'mobileGetActivityParticipantById');
        Route::post('activity-participants', 'mobileCreateActivityParticipant');
        Route::put('activity-participants/{activityParticipantId}', 'mobileUpdateActivityParticipant');
        Route::delete('activity-participants/{activityParticipantId}', 'mobileDeleteActivityParticipant');
    });

    Route::controller(MobileActivityController::class)->group(function () {
        Route::get('activities', 'mobileGetAllActivities');
        Route::get('activities/{activityId}', 'mobileGetActivityById');
        Route::post('activities', 'mobileCreateActivity');
        Route::post('activities/{activityId}', 'mobileUpdateActivity');
        Route::delete('activities/{activityId}', 'mobileDeleteActivity');
    });

    Route::controller(MobileBookController::class)->group(function () {
        Route::get('books', 'mobileGetAllBooks');
        Route::get('school-books', 'mobileGetAllSchoolBooks');
        Route::get('mosque-books', 'mobileGetAllMosqueBooks');
        Route::get('books/{bookId}', 'mobileGetBookById');
        Route::post('books', 'mobileCreateBook');
        Route::post('books/{bookId}', 'mobileUpdateBook');
        Route::delete('books/{bookId}', 'mobileDeleteBook');
    });

    Route::controller(MobileClassRoomController::class)->group(function () {
        Route::get('class-room', 'mobileGetAllClassRooms');
        Route::get('school/class-room', 'mobileAllSchoolClassroom');
        Route::get('mosque/class-room', 'mobileAllMosqueClassroom');
        Route::get('class-room/approved', 'getAllApprovedClasses');
        Route::get('school/class-room/approved', 'getAllApprovedSchoolClasses');
        Route::get('mosque/class-room/approved', 'getAllApprovedMosqueClasses');
        Route::get('class-room/pending', 'getAllClassesNotApproved');
        Route::get('school/class-room/pending', 'getAllPendingSchoolClasses');
        Route::get('mosque/class-room/pending', 'getAllPendingMosqueClasses');
        Route::get('class-room/{classRoomId}', 'mobileGetClassRoomById');
        Route::post('class-room', 'mobileCreateClassRoom');
        Route::post('class-room/{classRoomId}', 'mobileUpdateClassRoom');
        Route::delete('class-room/{classRoomId}', 'mobileDeleteClassRoom');
        Route::get('students-without-class', 'mobileGetAllStudentsWithoutClassroom');
        Route::get('teachers-without-class', 'mobileGetAllTeachersWithoutClassroom');
        Route::get('class-room-statistics/{classRoomId}', 'classStatistics');
        Route::get('class-room-books/{classRoomId}', 'getClassRoomBooks');
        Route::post('class-room-books/{bookId}/{classRoomId}', 'mobileCreatClassRoomBook');
        Route::delete('class-room-books/{bookId}/{classRoomId}', 'mobileDeleteClassRoomBook');

    });

    Route::controller(MobileSubjectController::class)->group(function () {
        Route::get('subjects', 'mobileGetAllSubjects');
        Route::get('subjects/{subjectId}', 'mobileGetSubjectById');
        Route::post('subjects', 'mobileCreateSubject');
        Route::put('subjects/{subjectId}', 'mobileUpdateSubject');
        Route::delete('subjects/{subjectId}', 'mobileDeleteSubject');
    });

    Route::controller(MobileSessionController::class)->group(function () {
        Route::get('sessions', 'mobileGetAllSessions');
        Route::get('sessions/{sessionId}', 'mobileGetSessionById');
        Route::post('sessions', 'mobileCreateSession');
        Route::put('sessions/{sessionId}', 'mobileUpdateSession');
        Route::delete('sessions/{sessionId}', 'mobileDeleteSession');
    });

    Route::controller(MobileStatusController::class)->group(function () {
        Route::get('statuses', 'mobileGetAllStatuses');
        Route::get('statuses/{statusId}', 'mobileGetStatusById');
        Route::post('statuses', 'mobileCreateStatus');
        Route::put('statuses/{statusId}', 'mobileUpdateStatus');
        Route::delete('statuses/{statusId}', 'mobileDeleteStatus');
    });

    Route::controller(MobileReferenceController::class)->group(function () {
        Route::get('references', 'mobileGetAllReferences');
        Route::get('references/{referenceId}', 'mobileGetReferenceById');
        Route::post('references', 'mobileCreateReference');
        Route::put('references/{referenceId}', 'mobileUpdateReference');
        Route::delete('references/{referenceId}', 'mobileDeleteReference');
    });

    Route::controller(MobileAttendanceController::class)->group(function () {
        Route::get('attendances', 'mobileGetAllAttendances');
        Route::get('attendances/{attendanceId}', 'mobileGetAttendanceById');
        Route::post('attendances', 'mobileCreateAttendance');
        Route::put('attendances/{attendanceId}', 'mobileUpdateAttendance');
        Route::delete('attendances/{attendanceId}', 'mobileDeleteAttendance');
    });

//    Route::controller(MobileRateTypeController::class)->group(function () {
//        Route::get('rate-types', 'mobileGetAllRateTypes');
//        Route::get('rate-types/{rateTypeId}', 'mobileGetRateTypeById');
//        Route::post('rate-types', 'mobileCreateRateType');
//        Route::put('rate-types/{rateTypeId}', 'mobileUpdateRateType');
//        Route::delete('rate-types/{rateTypeId}', 'mobileDeleteRateType');
//    });

    Route::controller(MobileRateController::class)->group(function () {
        Route::get('rates', 'mobileGetAllRates');
        Route::get('rates/{rateId}', 'mobileGetRateById');
        Route::post('rates', 'mobileCreateRate');
        Route::put('rates/{rateId}', 'mobileUpdateRate');
        Route::delete('rates/{rateId}', 'mobileDeleteRate');
    });

    Route::controller(MobileReportTypeController::class)->group(function () {
        Route::get('report-types', 'mobileGetAllReportTypes');
        Route::get('report-types/{reportTypeId}', 'mobileGetReportTypeById');
        Route::post('report-types', 'mobileCreateReportType');
        Route::put('report-types/{reportTypeId}', 'mobileUpdateReportType');
        Route::delete('report-types/{reportTypeId}', 'mobileDeleteReportType');
    });

    Route::controller(MobileReportController::class)->group(function () {
        Route::get('reports', 'mobileGetAllReports');
        Route::get('reports/{reportId}', 'mobileGetReportById');
        Route::post('reports', 'mobileCreateReport');
        Route::put('reports/{reportId}', 'mobileUpdateReport');
        Route::delete('reports/{reportId}', 'mobileDeleteReport');
    });

    Route::controller(MobileReportContentController::class)->group(function () {
        Route::get('report-contents', 'mobileGetAllReportContents');
        Route::get('report-contents/{contentId}', 'mobileGetReportContentById');
        Route::post('report-contents', 'mobileCreateReportContent');
        Route::put('report-contents/{contentId}', 'mobileUpdateReportContent');
        Route::delete('report-contents/{contentId}', 'mobileDeleteReportContent');
    });

    Route::controller(MobileReportReviewerController::class)->group(function () {
        Route::get('report-reviewers', 'mobileGetAllReportReviewers');
        Route::get('report-reviewers/{reviewerId}', 'mobileGetReportReviewerById');
        Route::post('report-reviewers', 'mobileCreateReportReviewer');
        Route::put('report-reviewers/{reviewerId}', 'mobileUpdateReportReviewer');
        Route::delete('report-reviewers/{reviewerId}', 'mobileDeleteReportReviewer');
    });

    Route::controller(MobileOrganizationController::class)->group(function () {
        Route::get('organizations', 'mobileGetAllOrganizations');
        Route::get('organizations/{organizationId}', 'mobileGetOrganizationById');
        Route::post('organizations', 'mobileCreateOrganization');
        Route::put('organizations/{organizationId}', 'mobileUpdateOrganization');
        Route::delete('organizations/{organizationId}', 'mobileDeleteOrganization');
    });
    //    ->middleware('auth:api');

    //Route::middleware('auth:api')->controller(MobileBranchController::class)->group(function () {
    Route::controller(MobileBranchController::class)->group(function () {
        Route::get('branches', 'mobileGetAllBranches');
        Route::get('branches/{branchId}', 'mobileGetBranchById');
        Route::post('branches', 'mobileCreateBranch');
        Route::put('branches/{branchId}', 'mobileUpdateBranch');
        Route::delete('branches/{branchId}', 'mobileDeleteBranch');
    });

    Route::controller(MobileInterviewController::class)->group(function () {
        Route::get('interviews', 'mobileGetAllInterviews');
        Route::get('interviews/{interviewId}', 'mobileGetInterviewById');
        Route::post('interviews', 'mobileCreateInterview');
        Route::post('interviews/{interviewId}', 'mobileUpdateInterview');
        Route::delete('interviews/{interviewId}', 'mobileDeleteInterview');
    });

    Route::controller(MobileQuizController::class)->group(function () {
        Route::get('quizzes', 'mobileGetAllQuizzes');
        Route::get('quizzes/{quizId}', 'mobileGetQuizById');
        Route::post('quizzes', 'mobileCreateQuiz');
        Route::put('quizzes/{quizId}', 'mobileUpdateQuiz');
        Route::delete('quizzes/{quizId}', 'mobileDeleteQuiz');
    });

    Route::controller(MobileQuranQuizController::class)->group(function () {
        Route::get('quran-quizzes', 'mobileGetAllQuranQuizzes');
        Route::get('quran-quizzes/{quranQuizId}', 'mobileGetQuranQuizById');
        Route::post('quran-quizzes', 'mobileCreateQuranQuiz');
        Route::put('quran-quizzes/{quranQuizId}', 'mobileUpdateQuranQuiz');
        Route::delete('quran-quizzes/{quranQuizId}', 'mobileDeleteQuranQuiz');
    });

    Route::controller(MobileNoteController::class)->group(function () {
        Route::get('notes', 'mobileGetAllNotes');
        Route::get('notes/{noteId}', 'mobileGetNoteById');
        Route::post('notes', 'mobileCreateNote');
        Route::put('notes/{noteId}', 'mobileUpdateNote');
        Route::delete('notes/{noteId}', 'mobileDeleteNote');
    });

    Route::controller(MobileCalendarController::class)->group(function () {
        Route::get('calendars', 'mobileGetAllCalendars');
        Route::get('calendars/{calendarId}', 'mobileGetCalendarById');
        Route::post('calendars', 'mobileCreateCalendar');
        Route::put('calendars/{calendarId}', 'mobileUpdateCalendar');
        Route::delete('calendars/{calendarId}', 'mobileDeleteCalendar');
    });

    Route::controller(MobileClassRoomStudentController::class)->group(function () {
        Route::get('class-room-students', 'mobileGetAllClassRoomStudents');
        Route::get('class-room-students/{classRoomStudentId}', 'mobileGetClassRoomStudentById');
        Route::post('class-room-students', 'mobileCreateClassRoomStudent');
        Route::put('class-room-students/{classRoomStudentId}', 'mobileUpdateClassRoomStudent');
        Route::delete('class-room-students/{classRoomStudentId}', 'mobileDeleteClassRoomStudent');
    });

    Route::controller(MobileClassRoomTeacherController::class)->group(function () {
        Route::get('class-room-teachers', 'mobileGetAllClassRoomTeachers');
        Route::get('class-room-teachers/{classRoomTeacherId}', 'mobileGetClassRoomTeacherById');
        Route::post('class-room-teachers', 'mobileCreateClassRoomTeacher');
        Route::put('class-room-teachers/{classRoomTeacherId}', 'mobileUpdateClassRoomTeacher');
        Route::delete('class-room-teachers/{classRoomTeacherId}', 'mobileDeleteClassRoomTeacher');
    });

    Route::controller(MobileSessionAttendanceController::class)->group(function () {
        Route::get('session-attendances', 'mobileGetAllSessionAttendances');
        Route::get('session-attendances/{sessionAttendanceId}', 'mobileGetSessionAttendanceById');
        Route::post('session-attendances', 'mobileCreateSessionAttendance');
        Route::put('session-attendances/{sessionAttendanceId}', 'mobileUpdateSessionAttendance');
        Route::delete('session-attendances/{sessionAttendanceId}', 'mobileDeleteSessionAttendance');
    });

    Route::controller(MobileAnalyticsController::class)->group(function () {
        Route::get('analytics/properties', 'mobileGetPropertiesAnalytics');
        Route::get('analytics/general-counts/{timeFilter}/{customStartDate?}/{customEndDate?}', 'mobileGetGeneralCounts');
    });

    Route::get('/check-version/{version}', function ($version) {

        $mobileVersion = "1.3.3";
        $newVersionLink = "https://drive.google.com/file/d/1Z02DatuVm7sRe2I7L5zwNYNAHLT4gvb7/view?usp=sharing";

        // Compare the requested version with the stored version
        $isCorrectVersion = $version === $mobileVersion;

        return response()->json([
            'isCorrectVersion' => $isCorrectVersion,
            "newVersionLink" => $newVersionLink
        ]);
    });


});

Route::get('search', function (Request $request) {
    // dd($request->search);
    TestingJob::dispatch()->onQueue('testing');
    Redis::set('name', 'ali kasmou');
    // return Redis::get('name');
    $students = \Mobile\Models\Users\Student::with('user')->get();
    Redis::set('students', $students);

    $result = \Mobile\Models\Users\Student::search($request->search)->get();

    return $result;
});

Route::get('/powered_by', function (Request $request) {

    $company = "Zad Tech";
    $description = "null";
    $url = "https://zaad-tech.com/";


    return response()->json([
        'company_name' => $company,
        'url' => $url,
        'description' => $description,
        'powered_by' => 'This application is powered by ' . $company
    ]);
});
