<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VListApproval]].
 *
 * @see VListApproval
 */
class VListApprovalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return VListApproval[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return VListApproval|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
