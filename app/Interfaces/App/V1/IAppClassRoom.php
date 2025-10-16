<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IClassRoom;

interface IAppClassRoom extends IClassRoom
{
    public function appAll(): object;

    public function appAllClassesByTeacherId(string $id): object;

    public function appAllSchoolClassroom(): object;

    public function appAllMosqueClassroom(): object;

    public function getAllClassesNotApproved(): object;

    public function getAllPendingSchoolClasses(): object;

    public function getAllPendingMosqueClasses(): object;

    public function getAllApprovedClasses(): object;

    public function getAllApprovedSchoolClasses(): object;

    public function getAllApprovedMosqueClasses(): object;

    //    public function appGetAllStudentsWithoutClassroom();

    public function appById(string $id): object;

    public function getClassRoomBooks(string $id): object;

    public function appCreatClassRoomBook(string $bookId, string $classRoomId): object;

    public function appDeleteClassRoomBook(string $bookId, string $classRoomId): object;

    public function classStatistics(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
