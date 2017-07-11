<?php

namespace app\controllers;

use Yii;
use app\models\Community;
use app\models\Post;
use app\models\CommunitySearch;
use app\models\Members;
use app\models\BloggerSearch;
use app\helpers\PostBuilder;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CommunityController implements the CRUD actions for Community model.
 */
class CommunityController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
            'access'=>[
                'class' => AccessControl::className(),
                'only'=>['index','create','update','delete','view','list','add-post','group'],
                'rules'=>[
                     [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['?','@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','update','delete','list','add-post','group','view'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Community models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommunitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Community model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Community model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   public function actionCreate() {
        $model = new Community();
        $member = new Members();
        if ($model->load(Yii::$app->request->post())) {
            try {
                $dbTransaction = Yii::$app->db->beginTransaction();
                $model->total_posts = 0;
                $model->created_by = Yii::$app->user->id;              
                if ($model->validate() && $model->save()) {
                    $community = Community::findOne(['id' => $model->id]);
                    $member->user_id = Yii::$app->user->id;
                    $member->community_id = $community->id;
                    if ($member->validate() && $member->save()) {
                        $dbTransaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Failed to save Membership for Post creator!!!');
                        $dbTransaction->rollBack();
                        return $this->refresh();
                    }
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Failed to save Post in this community!!!');
                    $dbTransaction->rollBack();
                    return $this->refresh();
                }
            } catch (\Exception $ex) {
                Yii::$app->getSession()->setFlash('error', 'Failed to create Post in this community!!!');             
                $dbTransaction->rollBack();
                return $this->refresh();
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Community model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Community model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    
    public function actionList(){
        $model = new Community();
        $communities = $model::find()->all();
        return $this->render('list',['communities'=> $communities]);
    }
    
    public function actionAddPost($id) {
        $model = new Post();
        $builder = new PostBuilder();
        $postData = Yii::$app->request->post();
        
        if (isset($postData['Post'])) {
            try {
                $dbTransaction = Yii::$app->db->beginTransaction();
                $post_id = $builder->createNewPost($postData);
                if ($post_id) {
                    $post = Post::findOne(['id'=> $post_id]);
                    $community = Community::findOne(['id'=> $id]);
                    $community->link('posts', $post);
                    $community->total_posts +=1;
                    $community->update();
                    $dbTransaction->commit();
                    return $this->redirect(['post/view','id'=> $post_id]);
                }
            } catch (Exception $ex) {
                $dbTransaction->rollBack();
            }
        } else {
            return $this->render('add-post', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionAvailableMembers($id){      
       $searchModel = new BloggerSearch();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id, $id);
       
        return $this->render('available-members', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'communityId' => $id
        ]);
    }
    
    public function actionAddMembers() {
        if (Yii::$app->request->post('Members')) {
           $member = new Members();
           $member->load(Yii::$app->request->post());
           if($member->validate() && $member->save()){
               return $this->redirect(['community/list']);
           }else{
               Yii::$app->getSession()->setFlash('error', 'Failed to add Blogger to group!!!');
           }
        } else {
            echo 'This action only works with post requests';
        }
    }

    public function actionGroup($id){
        $community = Community::findOne(['id'=> $id]);  
        $groupName = $community->name;
        $posts = $community->posts;
        return $this->render('group',['posts' => $posts, 'groupName'=> $groupName]);
    }
    
    public function actionIsMember($id) {
        if (Yii::$app->request->post()) {
            $testUser = Yii::$app->request->post('userId');
            $community = Community::findOne(['id' => $id]);
            $members = $community->members;
            foreach ($members as $member) {
                if ($member->user_id == $testUser) {
                    return 'true';
                }
            }
            return 'false';
        } else {
            echo 'This action only works with post requests';
        }
    }

    /**
     * Finds the Community model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Community the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Community::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
