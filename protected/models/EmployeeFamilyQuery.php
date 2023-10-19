<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeFamily]].
 *
 * @see EmployeeFamily
 */
class EmployeeFamilyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeFamily[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeFamily|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
