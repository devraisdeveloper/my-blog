<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Members;

/**
 * BlogerSearch represents the model behind the search form about `app\models\Bloger`.
 */
class BloggerSearch extends Blogger
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name','username', 'email', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$user_id,$community_id)
    {
        $membersList = [];
        $members = Members::find()->all();
        
        if (!empty($members)) {
            foreach ($members as $key => $member) {
                // $membersList[] = $member->user_id;
                if ($community_id == $member->community_id) {
                    $membersList[] = $member->user_id;
                }
            }
        }

        $query = Blogger::find()->where('id != :id', [':id' => $user_id])
                ->andWhere(['not in','id',$membersList]);
              //  ->andWhere(['community_id'=>$community_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'status', $this->status])
        ->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
    
    public function excludeGroupCreator(){
         $query = Blogger::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
