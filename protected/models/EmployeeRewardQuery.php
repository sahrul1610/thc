<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeReward]].
 *
 * @see EmployeeReward
 */
class EmployeeRewardQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EmployeeReward[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EmployeeReward|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
