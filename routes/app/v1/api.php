<?php

use App\Http\Controllers\App\V1\AppActivityController;
use App\Http\Controllers\App\V1\AppActivityParticipantsController;
use App\Http\Controllers\App\V1\AppActivityTypeController;
use App\Http\Controllers\App\V1\AppAdminController;
use App\Http\Controllers\App\V1\AppAnalyticsController;
use App\Http\Controllers\App\V1\AppAttendanceController;
use App\Http\Controllers\App\V1\AppAuthController;
use App\Http\Controllers\App\V1\AppBookController;
use App\Http\Controllers\App\V1\AppBranchAdminController;
use App\Http\Controllers\App\V1\AppBranchController;
use App\Http\Controllers\App\V1\AppCalendarController;
use App\Http\Controllers\App\V1\AppCertificationController;
use App\Http\Controllers\App\V1\AppClassRoomController;
use App\Http\Controllers\App\V1\AppClassRoomStudentController;
use App\Http\Controllers\App\V1\AppClassRoomTeacherController;
use App\Http\Controllers\App\V1\AppGradeController;
use App\Http\Controllers\App\V1\AppInterviewController;
use App\Http\Controllers\App\V1\AppJobTitleController;
use App\Http\Controllers\App\V1\AppMosqueController;
use App\Http\Controllers\App\V1\AppNoteController;
use App\Http\Controllers\App\V1\AppOrganizationAdminController;
use App\Http\Controllers\App\V1\AppOrganizationController;
use App\Http\Controllers\App\V1\AppPatientController;
use App\Http\Controllers\App\V1\AppPatientTreatmentController;
use App\Http\Controllers\App\V1\AppPropertyAdminController;
use App\Http\Controllers\App\V1\AppPropertyController;
use App\Http\Controllers\App\V1\AppQuizController;
use App\Http\Controllers\App\V1\AppQuranQuizController;
use App\Http\Controllers\App\V1\AppRateController;
use App\Http\Controllers\App\V1\AppRateTypeController;
use App\Http\Controllers\App\V1\AppReferenceController;
use App\Http\Controllers\App\V1\AppReportContentController;
use App\Http\Controllers\App\V1\AppReportController;
use App\Http\Controllers\App\V1\AppReportReviewerController;
use App\Http\Controllers\App\V1\AppReportTypeController;
use App\Http\Controllers\App\V1\AppSchoolController;
use App\Http\Controllers\App\V1\AppSessionAttendanceController;
use App\Http\Controllers\App\V1\AppSessionController;
use App\Http\Controllers\App\V1\AppStatusController;
use App\Http\Controllers\App\V1\AppStudentController;
use App\Http\Controllers\App\V1\AppSubjectController;
use App\Http\Controllers\App\V1\AppTeacherController;
use App\Http\Controllers\App\V1\AppUserController;
use App\Http\Controllers\App\V1\AppUserParentController;
use App\Jobs\TestingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::controller(AppAuthController::class)
    ->group(function () {
        Route::get('refresh', 'refresh');
        Route::post('logout', 'logout');
        Route::post('login', 'login');
    });

Route::controller(AppUserController::class)->group(function () {
    Route::get('users', 'appGetAllUsers');
    Route::get('users/pending', 'appAllUsersNotApproved');
    Route::get('users/{userId}', 'getUserById');
    Route::post('users', 'appCreateUser');
    Route::put('users/{userId}', 'appUpdateUser');
    Route::delete('users/{userId}', 'appDeleteUser');
});
Route::middleware(['auth:api', 'active', 'approved'])->group(function () {
    // all your existing routes here

    Route::controller(AppUserParentController::class)->group(function () {
        Route::get('parents', 'appGetAllUserParents');
        Route::get('parents/{userParentId}', 'getUserParentById');
        Route::post('parents', 'appCreateUserParent');
        Route::put('parents/{userParentId}', 'appUpdateUserParent');
        Route::delete('parents/{userParentId}', 'appDeleteUserParent');
    });

    Route::controller(AppAdminController::class)->group(function () {
        Route::get('admins', 'appGetAllAdmins');
        Route::get('admins/unassigned', 'appGetAllUnassignedAdmins');
        Route::get('admins/{adminId}', 'appGetAdminById');
        Route::post('admins', 'appCreateAdmin');
        Route::post('admins/{adminId}', 'appUpdateAdmin');
        Route::delete('admins/{adminId}', 'appDeleteAdmin');
    });

    Route::controller(AppOrganizationAdminController::class)->group(function () {
        Route::get('organization/admins', 'appGetAllOrganizationsAdmin');
        Route::get('organization/admins/{organizationId}', 'appGetOrganizationAdminById');
        Route::post('organization/admins', 'appCreateOrganizationAdmin');
        Route::post('organization/admins/{organizationId}', 'appUpdateOrganizationAdmin');
        Route::delete('organization/admins/{organizationId}', 'appDeleteOrganizationAdmin');
    });

    Route::controller(AppBranchAdminController::class)->group(function () {
        Route::get('branch/admins', 'appGetAllBranchAdmins');
        Route::get('branch/admins/{branchId}', 'appGetBranchAdminById');
        Route::post('branch/admins', 'appCreateBranchAdmin');
        Route::post('branch/admins-transfer', 'appTransferBranchAdmin');
        Route::put('branch/admins/{branchId}', 'appUpdateBranchAdmin');
        Route::delete('branch/admins/{branchId}', 'appDeleteBranchAdmin');

    });
    Route::controller(AppPropertyAdminController::class)->group(function () {
        Route::get('property/admins', 'appGetAllPropertyAdmins');
        Route::get('property/admins/{propertyId}', 'appGetPropertyAdminById');
        Route::post('property/admins', 'appCreatePropertyAdmin');
        Route::post('property/admins-transfer', 'appTransferPropertyAdmin');
        Route::put('property/admins/{propertyId}', 'appUpdatePropertyAdmin');
        Route::delete('property/admins/{propertyId}', 'appDeletePropertyAdmin');

    });

    Route::controller(AppJobTitleController::class)->group(function () {
        Route::get('job-titles', 'appGetAllJobTitles');
        Route::get('job-titles/{jobTitleId}', 'appGetJobTitleById');
        Route::post('job-title', 'appCreateJobTitle');
        Route::put('job-title/{jobTitleId}', 'appUpdateJobTitle');
        Route::delete('job-title/{jobTitleId}', 'appDeleteJobTitle');
    });

    Route::controller(AppStudentController::class)->group(function () {
        Route::get('students', 'appGetAllStudents');
        Route::get('students/{studentId}', 'appGetStudentById');
        Route::get('student-statistics/{studentId}', 'studentStatistics');
        Route::get('all-student', 'appGetAllOrgStudents');
        Route::post('students', 'appCreateStudent');
        Route::post('students/{studentId}', 'appUpdateStudent');
        Route::post('students/transfer/{studentId}', 'appTransferStudent');
        Route::delete('students/{studentId}', 'appDeleteStudent');
        Route::get('students/{studentId}/student-groups', 'appGetStudentGroups');
    });

    Route::controller(AppTeacherController::class)->group(function () {
        Route::get('teachers', 'appGetAllTeachers');
        Route::get('all-teachers', 'appGetAllOrgTeacher');
        Route::get('teachers/{teacherId}', 'appGetTeacherById');
        Route::post('teachers', 'appCreateTeacher');
        Route::post('teachers/{teacherId}', 'appUpdateTeacher');
        Route::post('teachers/transfer/{teacherId}', 'appTransferTeacher');
        Route::delete('teachers/{teacherId}', 'appDeleteTeacher');
        Route::get('teachers-statistics/{teacherId}', 'teacherStatistics');
    });

    Route::controller(AppCertificationController::class)->group(function () {
        Route::get('certifications', 'appGetAllCertifications');
        Route::get('certifications/{certificationId}', 'appGetCertificationById');
        Route::post('certifications', 'appCreateCertification');
        Route::put('certifications/{certificationId}', 'appUpdateCertification');
        Route::delete('certifications/{certificationId}', 'appDeleteCertification');
    });

    Route::controller(AppPatientController::class)->group(function () {
        Route::get('patients', 'appGetAllPatients');
        Route::get('patients/{patientId}', 'appGetPatientById');
        Route::post('patients', 'appCreatePatient');
        Route::put('patients/{patientId}', 'appUpdatePatient');
        Route::delete('patients/{patientId}', 'appDeletePatient');
    });

    Route::controller(AppPatientTreatmentController::class)->group(function () {
        Route::get('treatments', 'appGetAllPatientTreatments');
        Route::get('treatments/{treatmentId}', 'appGetPatientTreatmentById');
        Route::post('treatments', 'appCreatePatientTreatment');
        Route::put('treatments/{treatmentId}', 'appUpdatePatientTreatment');
        Route::delete('treatments/{treatmentId}', 'appDeletePatientTreatment');
    });

    //Route::controller(AppSchoolController::class)->group(function () {
    //    Route::get('properties/schools', 'appGetAllSchools');
    //    Route::get('properties/schools/{schoolId}', 'appGetSchoolById');
    //    Route::post('properties/schools', 'appCreateSchool');
    //    Route::put('properties/schools/{schoolId}', 'appUpdateSchool');
    //    Route::delete('properties/schools/{schoolId}', 'appDeleteSchool');
    //});
    //
    //Route::controller(AppMosqueController::class)->group(function () {
    //    Route::get('properties/mosques', 'appGetAllMosques');
    //    Route::get('properties/mosques/{mosqueId}', 'appGetMosqueById');
    //    Route::post('properties/mosques', 'appCreateMosque');
    //    Route::put('properties/mosques/{mosqueId}', 'appUpdateMosque');
    //    Route::delete('properties/mosques/{mosqueId}', 'appDeleteMosque');
    //});

    Route::controller(AppPropertyController::class)->group(function () {
        Route::get('properties', 'appGetAllProperties');
        Route::get('properties/{propertyId}', 'appGetPropertyById');
        Route::get('properties-mosques', 'appGetAllMosques');
        Route::get('properties-schools', 'appGetAllSchools');

        Route::get('property-statistics/all', 'allPropertyStatistics');
        Route::get('property-statistics/{propertyId}', 'propertyStatistics');
        Route::get('property-students/{propertyId}', 'appGetAllPropertyStudents');
        Route::get('property-teachers-and-admins/{propertyId}', 'appGetAllPropertyTeachers');
        Route::get('property-students-without-class-room/{propertyId}', 'getStudentsWithoutClassroomByPropertyId');
        Route::get('property-teachers-without-class-room/{propertyId}', 'getTeachersWithoutClassroomByPropertyId');
        Route::post('properties', 'appCreateProperty');
        Route::post('properties/{propertyId}', 'appUpdateProperty');
        Route::delete('properties/{propertyId}', 'appDeleteProperty');
    });

    Route::controller(AppGradeController::class)->group(function () {
        Route::get('grades', 'appGetAllGrades');
        Route::get('grades/{gradeId}', 'appGetGradeById');
        Route::get('grades-statistics/{gradeId}', 'appGetGradeStatistics');
        Route::post('grades', 'appCreateGrade');
        Route::put('grades/{gradeId}', 'appUpdateGrade');
        Route::delete('grades/{gradeId}', 'appDeleteGrade');
    });

    Route::controller(AppActivityTypeController::class)->group(function () {
        Route::get('activity-types', 'appGetAllActivityTypes');
        Route::get('activity-types/{activityTypeId}', 'appGetActivityTypeById');
        Route::post('activity-types', 'appCreateActivityType');
        Route::post('activity-types/{activityTypeId}', 'appUpdateActivityType');
        Route::delete('activity-types/{activityTypeId}', 'appDeleteActivityType');
    });

    Route::controller(AppActivityParticipantsController::class)->group(function () {
        Route::get('activity-participants', 'appGetAllActivityParticipants');
        Route::get('activity-participants/{activityParticipantId}', 'appGetActivityParticipantById');
        Route::post('activity-participants', 'appCreateActivityParticipant');
        Route::put('activity-participants/{activityParticipantId}', 'appUpdateActivityParticipant');
        Route::delete('activity-participants/{activityParticipantId}', 'appDeleteActivityParticipant');
    });

    Route::controller(AppActivityController::class)->group(function () {
        Route::get('activities', 'appGetAllActivities');
        Route::get('activities/{activityId}', 'appGetActivityById');
        Route::get('activities-by-teacher/{teacherId}', 'appGetAllActivitiesByTeacherId');
        Route::post('activities', 'appCreateActivity');
        Route::post('activities/{activityId}', 'appUpdateActivity');
        Route::delete('activities/{activityId}', 'appDeleteActivity');
    });

    Route::controller(AppBookController::class)->group(function () {
        Route::get('books', 'appGetAllBooks');
        Route::get('school-books', 'appGetAllSchoolBooks');
        Route::get('mosque-books', 'appGetAllMosqueBooks');
        Route::get('books/{bookId}', 'appGetBookById');
        Route::post('books', 'appCreateBook');
        Route::post('books/{bookId}', 'appUpdateBook');
        Route::delete('books/{bookId}', 'appDeleteBook');
    });

    Route::controller(AppClassRoomController::class)->group(function () {
        Route::get('class-room', 'appGetAllClassRooms');
        Route::get('school/class-room', 'appAllSchoolClassroom');
        Route::get('mosque/class-room', 'appAllMosqueClassroom');
        Route::get('class-room/approved', 'getAllApprovedClasses');
        Route::get('school/class-room/approved', 'getAllApprovedSchoolClasses');
        Route::get('mosque/class-room/approved', 'getAllApprovedMosqueClasses');
        Route::get('class-room/pending', 'getAllClassesNotApproved');
        Route::get('school/class-room/pending', 'getAllPendingSchoolClasses');
        Route::get('mosque/class-room/pending', 'getAllPendingMosqueClasses');
        Route::get('class-room/{classRoomId}', 'appGetClassRoomById');
        Route::get('class-room-by-teacher/{teacherId}', 'appAllClassesByTeacherId');
        Route::post('class-room', 'appCreateClassRoom');
        Route::post('class-room/{classRoomId}', 'appUpdateClassRoom');
        Route::delete('class-room/{classRoomId}', 'appDeleteClassRoom');
        Route::get('students-without-class', 'appGetAllStudentsWithoutClassroom');
        Route::get('teachers-without-class', 'appGetAllTeachersWithoutClassroom');
        Route::get('class-room-statistics/{classRoomId}', 'classStatistics');
        Route::get('class-room-books/{classRoomId}', 'getClassRoomBooks');
        Route::post('class-room-books/{bookId}/{classRoomId}', 'appCreatClassRoomBook');
        Route::delete('class-room-books/{bookId}/{classRoomId}', 'appDeleteClassRoomBook');

    });

    Route::controller(AppSubjectController::class)->group(function () {
        Route::get('subjects', 'appGetAllSubjects');
        Route::get('subjects/{subjectId}', 'appGetSubjectById');
        Route::post('subjects', 'appCreateSubject');
        Route::put('subjects/{subjectId}', 'appUpdateSubject');
        Route::delete('subjects/{subjectId}', 'appDeleteSubject');
    });

    Route::controller(AppSessionController::class)->group(function () {
        Route::get('sessions', 'appGetAllSessions');
        Route::get('sessions/{sessionId}', 'appGetSessionById');
        Route::get('sessions-by-teacher/{teacherId}', 'appGetAllSessionByTeacherId');
        Route::post('sessions', 'appCreateSession');
        Route::put('sessions/{sessionId}', 'appUpdateSession');
        Route::delete('sessions/{sessionId}', 'appDeleteSession');
    });

    Route::controller(AppStatusController::class)->group(function () {
        Route::get('statuses', 'appGetAllStatuses');
        Route::get('statuses/{statusId}', 'appGetStatusById');
        Route::post('statuses', 'appCreateStatus');
        Route::put('statuses/{statusId}', 'appUpdateStatus');
        Route::delete('statuses/{statusId}', 'appDeleteStatus');
    });

    Route::controller(AppReferenceController::class)->group(function () {
        Route::get('references', 'appGetAllReferences');
        Route::get('references/{referenceId}', 'appGetReferenceById');
        Route::post('references', 'appCreateReference');
        Route::put('references/{referenceId}', 'appUpdateReference');
        Route::delete('references/{referenceId}', 'appDeleteReference');
    });

    Route::controller(AppAttendanceController::class)->group(function () {
        Route::get('attendances', 'appGetAllAttendances');
        Route::get('attendances/{attendanceId}', 'appGetAttendanceById');
        Route::post('attendances', 'appCreateAttendance');
        Route::put('attendances/{attendanceId}', 'appUpdateAttendance');
        Route::delete('attendances/{attendanceId}', 'appDeleteAttendance');
    });

    Route::controller(AppRateTypeController::class)->group(function () {
        Route::get('rate-types', 'appGetAllRateTypes');
        Route::get('rate-types/{rateTypeId}', 'appGetRateTypeById');
        Route::post('rate-types', 'appCreateRateType');
        Route::put('rate-types/{rateTypeId}', 'appUpdateRateType');
        Route::delete('rate-types/{rateTypeId}', 'appDeleteRateType');
    });

    Route::controller(AppRateController::class)->group(function () {
        Route::get('rates', 'appGetAllRates');
        Route::get('rates/{rateId}', 'appGetRateById');
        Route::post('rates', 'appCreateRate');
        Route::put('rates/{rateId}', 'appUpdateRate');
        Route::delete('rates/{rateId}', 'appDeleteRate');
    });

    Route::controller(AppReportTypeController::class)->group(function () {
        Route::get('report-types', 'appGetAllReportTypes');
        Route::get('report-types/{reportTypeId}', 'appGetReportTypeById');
        Route::post('report-types', 'appCreateReportType');
        Route::put('report-types/{reportTypeId}', 'appUpdateReportType');
        Route::delete('report-types/{reportTypeId}', 'appDeleteReportType');
    });

    Route::controller(AppReportController::class)->group(function () {
        Route::get('reports', 'appGetAllReports');
        Route::get('reports/{reportId}', 'appGetReportById');
        Route::post('reports', 'appCreateReport');
        Route::put('reports/{reportId}', 'appUpdateReport');
        Route::delete('reports/{reportId}', 'appDeleteReport');
    });

    Route::controller(AppReportContentController::class)->group(function () {
        Route::get('report-contents', 'appGetAllReportContents');
        Route::get('report-contents/{contentId}', 'appGetReportContentById');
        Route::post('report-contents', 'appCreateReportContent');
        Route::put('report-contents/{contentId}', 'appUpdateReportContent');
        Route::delete('report-contents/{contentId}', 'appDeleteReportContent');
    });

    Route::controller(AppReportReviewerController::class)->group(function () {
        Route::get('report-reviewers', 'appGetAllReportReviewers');
        Route::get('report-reviewers/{reviewerId}', 'appGetReportReviewerById');
        Route::post('report-reviewers', 'appCreateReportReviewer');
        Route::put('report-reviewers/{reviewerId}', 'appUpdateReportReviewer');
        Route::delete('report-reviewers/{reviewerId}', 'appDeleteReportReviewer');
    });

    Route::controller(AppOrganizationController::class)->group(function () {
        Route::get('organizations', 'appGetAllOrganizations');
        Route::get('organizations/{organizationId}', 'appGetOrganizationById');
        Route::post('organizations', 'appCreateOrganization');
        Route::put('organizations/{organizationId}', 'appUpdateOrganization');
        Route::delete('organizations/{organizationId}', 'appDeleteOrganization');
    });
    //    ->middleware('auth:api');

    //Route::middleware('auth:api')->controller(AppBranchController::class)->group(function () {
    Route::controller(AppBranchController::class)->group(function () {
        Route::get('branches', 'appGetAllBranches');
        Route::get('branches/{branchId}', 'appGetBranchById');
        Route::post('branches', 'appCreateBranch');
        Route::put('branches/{branchId}', 'appUpdateBranch');
        Route::delete('branches/{branchId}', 'appDeleteBranch');
    });

    Route::controller(AppInterviewController::class)->group(function () {
        Route::get('interviews', 'appGetAllInterviews');
        Route::get('interviews/{interviewId}', 'appGetInterviewById');
        Route::get('interviews-by-teacher/{teacherId}', 'appGetAllInterviewsByTeacherId');
        Route::post('interviews', 'appCreateInterview');
        Route::post('interviews/{interviewId}', 'appUpdateInterview');
        Route::delete('interviews/{interviewId}', 'appDeleteInterview');
    });

    Route::controller(AppQuizController::class)->group(function () {
        Route::get('quizzes', 'appGetAllQuizzes');
        Route::get('quizzes/{quizId}', 'appGetQuizById');
        Route::get('quizzes-by-teacher/{teacherId}', 'appGetAllQuizzesByTeacherId');
        Route::post('quizzes', 'appCreateQuiz');
        Route::put('quizzes/{quizId}', 'appUpdateQuiz');
        Route::delete('quizzes/{quizId}', 'appDeleteQuiz');
    });

    Route::controller(AppQuranQuizController::class)->group(function () {
        Route::get('quran-quizzes', 'appGetAllQuranQuizzes');
        Route::get('quran-quizzes/{quranQuizId}', 'appGetQuranQuizById');
        Route::get('quran-quizzes-by-teacher/{teacherId}', 'appGetAllQuranQuizzesByTeacherId');
        Route::post('quran-quizzes', 'appCreateQuranQuiz');
        Route::put('quran-quizzes/{quranQuizId}', 'appUpdateQuranQuiz');
        Route::delete('quran-quizzes/{quranQuizId}', 'appDeleteQuranQuiz');
    });

    Route::controller(AppNoteController::class)->group(function () {
        Route::get('notes', 'appGetAllNotes');
        Route::get('notes/{noteId}', 'appGetNoteById');
        Route::get('notes-by-teacher/{teacherId}', 'appGetAllNotesByTeacherId');
        Route::post('notes', 'appCreateNote');
        Route::put('notes/{noteId}', 'appUpdateNote');
        Route::delete('notes/{noteId}', 'appDeleteNote');
    });

    Route::controller(AppCalendarController::class)->group(function () {
        Route::get('calendars', 'appGetAllCalendars');
        Route::get('calendars/{calendarId}', 'appGetCalendarById');
        Route::post('calendars', 'appCreateCalendar');
        Route::put('calendars/{calendarId}', 'appUpdateCalendar');
        Route::delete('calendars/{calendarId}', 'appDeleteCalendar');
    });

    Route::controller(AppClassRoomStudentController::class)->group(function () {
        Route::get('class-room-students', 'appGetAllClassRoomStudents');
        Route::get('class-room-students/{classRoomStudentId}', 'appGetClassRoomStudentById');
        Route::post('class-room-students', 'appCreateClassRoomStudent');
        Route::put('class-room-students/{classRoomStudentId}', 'appUpdateClassRoomStudent');
        Route::delete('class-room-students/{classRoomStudentId}', 'appDeleteClassRoomStudent');
    });

    Route::controller(AppClassRoomTeacherController::class)->group(function () {
        Route::get('class-room-teachers', 'appGetAllClassRoomTeachers');
        Route::get('class-room-teachers/{classRoomTeacherId}', 'appGetClassRoomTeacherById');
        Route::post('class-room-teachers', 'appCreateClassRoomTeacher');
        Route::put('class-room-teachers/{classRoomTeacherId}', 'appUpdateClassRoomTeacher');
        Route::delete('class-room-teachers/{classRoomTeacherId}', 'appDeleteClassRoomTeacher');
    });

    Route::controller(AppSessionAttendanceController::class)->group(function () {
        Route::get('session-attendances', 'appGetAllSessionAttendances');
        Route::get('session-attendances/{sessionAttendanceId}', 'appGetSessionAttendanceById');
        Route::post('session-attendances', 'appCreateSessionAttendance');
        Route::put('session-attendances/{sessionAttendanceId}', 'appUpdateSessionAttendance');
        Route::delete('session-attendances/{sessionAttendanceId}', 'appDeleteSessionAttendance');
    });

    Route::controller(AppAnalyticsController::class)->group(function () {
        Route::get('analytics/properties', 'appGetPropertiesAnalytics');
        Route::get('analytics/general-counts/{timeFilter}/{customStartDate?}/{customEndDate?}', 'appGetGeneralCounts');
        Route::get('analytics/top-students/{timeFilter}/{customStartDate?}/{customEndDate?}', 'appGetTopLearners');
        Route::get('analytics/branches/{start_date?}/{end_date?}', 'appExportData');
        Route::get('analytics/property/{propertyId}/{start_date?}/{end_date?}', 'appPropertyExportData');

    });

});


//Route::controller(AppAnalyticsController::class)->group(function () {


//        ->where('propertyId', '[0-9]+')
//        ->where('start_date', '[0-9]{2}-[0-9]{2}-[0-9]{4}')
//        ->where('end_date', '[0-9]{2}-[0-9]{2}-[0-9]{4}');
//dd("h");

//    Route::get('analytics/property/', 'apptestData');

//});

Route::get('search', function (Request $request) {
    // dd($request->search);
    TestingJob::dispatch()->onQueue('testing');
    Redis::set('name', 'ali kasmou');
    // return Redis::get('name');
    $students = \App\Models\Users\Student::with('user')->get();
    Redis::set('students', $students);

    $result = \App\Models\Users\Student::search($request->search)->get();

    return $result;
});
