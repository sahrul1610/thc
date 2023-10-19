<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeEducation]].
 *
 * @see EmployeeEducation
 */
class EmployeeEducationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeEducation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeEducation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
