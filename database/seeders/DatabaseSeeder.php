<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(PropertySeeder::class);
        //        $this->call(ContactSeeder::class);
        //        $this->call(MosqueSeeder::class);
        //        $this->call(SchoolSeeder::class);
        $this->call(JobTitleSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClassRoomSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(StudentSeeder::class);
        //        $this->call(UserParentSeeder::class);
        //        $this->call(SubjectSeeder::class);
        //        $this->call(BookSeeder::class);
        //        $this->call(SubjectBookSeeder::class);
        //        $this->call(PatientSeeder::class);
        //        $this->call(PatientTreatmentSeeder::class);
        //        $this->call(CertificateTypeSeeder::class);
        //        $this->call(CertificationSeeder::class);
        //        $this->call(CertificateTranscriptSeeder::class);
        //        $this->call(CertificateDetailSeeder::class);
        $this->call(ClassRoomTeacherSeeder::class);
        $this->call(ClassRoomStudentSeeder::class);
        //        $this->call(SessionSeeder::class);
        //        $this->call(ActivityTypeSeeder::class);
        //        $this->call(ActivitySeeder::class);
        //        $this->call(StatusTypeSeeder::class);
        //        $this->call(StatusSeeder::class);
        //        $this->call(RateTypeSeeder::class);
        //        $this->call(ReportTypeSeeder::class);
        //        $this->call(ReportSeeder::class);
        //        $this->call(ReportContentSeeder::class);
        //        $this->call(ReportReviewerSeeder::class);
        //        $this->call(ParticipantSeeder::class);
        //        $this->call(SessionAttendanceSeeder::class);
        //        $this->call(CalendarSeeder::class);
        //        $this->call(NoteSeeder::class);
        //        $this->call(InterviewSeeder::class);
        //        $this->call(QuranQuizSeeder::class);
        //        $this->call(QuizSeeder::class);
        $this->call(OrganizationAdminSeeder::class);
        $this->call(BranchAdminSeeder::class);
        $this->call(PropertyAdminSeeder::class);

    }
}
