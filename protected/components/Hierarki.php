<?php


namespace app\components;

use app\models\Employee;
use app\models\Organization;

class Hierarki
{
    public static function getSubordinate($person_id)
    {
        $code = 404;
        $status = 'failed';
        $message = 'Bawahan Tidak Ditemukan';
        $data = null;
        $org = Organization::find()->innerJoin('employee', 'employee.object_id = organization.object_id')
            ->where(['employee.person_id' => $person_id])->one();
        if ($org->is_chief == true) {
            $data = Employee::find()
                ->alias('t')
                ->innerJoin('organization o', 'o.object_id = t.object_id')
                ->where(['o.object_parent' => $org->objectunit_id])
                ->all();
            $code = 200;
            $status = 'success';
            $message = 'Bawahan ditemukan';
        }
        return ['code' => $code, 'status' => $status, 'message' => $message, 'data' => $data];
    }

    public static function getPeer($person_id)
    {
        $code = 404;
        $status = 'failed';
        $message = 'Peer Tidak Ditemukan';
        $data = null;
        $org = Organization::find()
            ->alias('o')
            ->innerJoin('employee e', 'e.position_id = o.object_id')
            ->where(['e.person_id' => $person_id])
            ->one();
        if (!empty($org)) {
            $data = Employee::find()
                ->alias('t')
                ->innerJoin('organization o', 'o.object_id = t.position_id')
                ->where(['o.object_parent' => $org->object_parent])
                ->all();
            $code = 200;
            $status = 'success';
            $message = 'Bawahan ditemukan';
        }
        return ['code' => $code, 'status' => $status, 'message' => $message, 'data' => $data];
    }

    public static function getHead($person_id)
    {
        $code = 404;
        $status = 'failed';
        $message = 'Atasan tidak ditemukan';
        $data = null;
        $org = Organization::find()
            ->alias('o')
            ->innerJoin('employee e', 'e.org_id = o.org_id')
            ->where(['e.person_id' => $person_id])
            ->one();
        if (!empty($org)) {
            $data = Employee::find()
                ->alias('e')
                ->innerJoin('organization o', 'o.org_id =  e.org_id')
                ->where(['o.unit_code' => $org->org_parent, 'o.is_chief' => 't'])
                ->one();
            $code = 200;
            $status = 'success';
            $message = 'Atasan ditemukan';
        }
        return ['code' => $code, 'status' => $status, 'message' => $message, 'data' => $data];
    }

    public static function getOrg($person_id)
    {
        $code = 404;
        $status = 'failed';
        $message = 'Atasan tidak ditemukan';
        $data = null;
        $model = Organization::find()
            ->alias('o')
            ->innerJoin('employee e', 'e.object_id = o.object_id')
            ->where(['e.person_id' => $person_id])
            ->one();
        if (!empty($model)) {
            $data = $model;
        }
        return ['code' => $code, 'status' => $status, 'message' => $message, 'data' => $data];
    }
}