<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeAddress]].
 *
 * @see EmployeeAddress
 */
class EmployeeAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
