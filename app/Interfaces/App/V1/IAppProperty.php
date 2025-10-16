<?php

namespace App\Interfaces\App\V1;

use App\Interfaces\Common\V1\IProperty;

interface IAppProperty extends IProperty
{
    public function appAll(): object;

    public function appGetAllMosques(): object;

    public function appGetAllSchools(): object;

    public function appById(string $id): object;

    public function allPropertyStatistics(): object;

    public function propertyStatistics(string $id): object;

    public function appGetAllPropertyStudents(string $id): object;

    public function appGetAllPropertyTeachers(string $id): object;

    public function getStudentsWithoutClassroomByPropertyId(string $id): object;

    public function getTeachersWithoutClassroomByPropertyId(string $id): object;

    public function appCreateObject($object): object;

    public function appUpdateObject($object, $id): object;

    public function appDeleteObject(string $id): object;
}
