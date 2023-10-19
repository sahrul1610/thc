<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeIdentity]].
 *
 * @see EmployeeIdentity
 */
class EmployeeIdentityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeIdentity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeIdentity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
