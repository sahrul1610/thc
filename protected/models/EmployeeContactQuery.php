<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeContact]].
 *
 * @see EmployeeContact
 */
class EmployeeContactQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeContact[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeContact|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
