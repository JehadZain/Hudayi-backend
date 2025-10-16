<?php

namespace App\Interfaces\Mobile\V1;

use App\Interfaces\Common\V1\IProperty;

interface IMobileProperty extends IProperty
{
    public function mobileAll(): object;

    public function mobileGetAllMosques(): object;

    public function mobileGetAllSchools(): object;

    public function mobileById(string $id): object;

    public function propertyClassRooms(string $id): object;

    public function allPropertyStatistics(): object;

    public function mobilePropertyStatistics(string $id): object;

    public function mobileGetAllPropertyStudents(string $id): object;

    public function mobileGetAllPropertyTeachers(string $id): object;

    public function getStudentsWithoutClassroomByPropertyId(string $id): object;

    public function getTeachersWithoutClassroomByPropertyId(string $id): object;

    public function mobileCreateObject($object): object;

    public function mobileUpdateObject($object, $id): object;

    public function mobileDeleteObject(string $id): object;
}
