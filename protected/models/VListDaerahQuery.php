<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VListDaerah]].
 *
 * @see VListDaerah
 */
class VListDaerahQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return VListDaerah[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return VListDaerah|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
