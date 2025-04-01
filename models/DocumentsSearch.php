<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DocumentsSearch represents the model behind the search form of `app\models\Documents`.
 */
class DocumentsSearch extends Documents
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'organization_id', 'user_id', 'type_id'], 'integer'], // Ensure these fields are integers
            [['title', 'date_create', 'date_insert', 'description', 'url'], 'safe'], // Allow these fields to be searched
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // Bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Documents::find();

        // Add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
        {
            // Uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            'organization_id' => $this->organization_id,
            'user_id' => $this->user_id,
            'type_id' => $this->type_id,
            //'date_create' => $this->date_create,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url]);

        // Filter by date range
        if (!empty($this->date_create))
        {
            $dateRange = explode(' - ', $this->date_create);
            if (count($dateRange) === 2)
            {

                $query->andFilterWhere(['between', 'date_create', $dateRange[0], $dateRange[1]]);
            }
        }
        //\Yii::$app->session->setFlash('success', " date_insert: " . $this->date_insert); // Debugging line to check the value of date_insert
        if (!empty($this->date_insert))
        {
            //\Yii::$app->session->setFlash('success', "not empty date_insert: " . $this->date_insert); // Debugging line to check the value of date_insert
            $dateRange = explode(' - ', $this->date_insert);
            if (count($dateRange) === 2)
            {
                $query->andFilterWhere(['between', 'date_insert', $dateRange[0], $dateRange[1]]);
            }
        }

        //\Yii::$app->session->setFlash('error', "<pre>{$this->date_insert}\n" . print_r($params, true) . '</pre>');
        //\Yii::$app->session->setFlash('info', $query->createCommand()->rawSql); // Debugging line to check the SQL query

        return $dataProvider;
    }
}
