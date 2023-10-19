<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeInnovation]].
 *
 * @see EmployeeInnovation
 */
class EmployeeInnovationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeInnovation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeInnovation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
