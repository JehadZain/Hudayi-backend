<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IClassRoom;

interface IMobileClassRoom extends IClassRoom
{
    public function mobileAll(): object;

    public function mobileAllSchoolClassroom(): object;

    public function mobileAllMosqueClassroom(): object;

    public function getAllClassesNotApproved(): object;

    public function getAllPendingSchoolClasses(): object;

    public function getAllPendingMosqueClasses(): object;

    public function getAllApprovedClasses(): object;

    public function getAllApprovedSchoolClasses(): object;

    public function getAllApprovedMosqueClasses(): object;

    //    public function mobileGetAllStudentsWithoutClassroom();

    public function mobileById(string $id): object;

    public function getClassRoomBooks(string $id): object;

    public function mobileCreatClassRoomBook(string $bookId, string $classRoomId): object;

    public function mobileDeleteClassRoomBook(string $bookId, string $classRoomId): object;

    public function classStatistics(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileDeleteObject(string $id): object;
}
