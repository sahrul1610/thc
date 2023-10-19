<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Approval]].
 *
 * @see Approval
 */
class ApprovalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Approval[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Approval|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
