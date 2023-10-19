<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectDocument]].
 *
 * @see ProjectDocument
 */
class ProjectDocumentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProjectDocument[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectDocument|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
