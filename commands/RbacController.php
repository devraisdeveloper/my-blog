<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

/**
 * Description of RbacController
 *
 * @author vitalii
 */
class RbacController extends Controller {

    public function actionInit() {
        $authManager = \Yii::$app->authManager;

        try {
            $authManager->removeAll();

            // Create roles
            $guest = $authManager->createRole('guest');
            $blogger = $authManager->createRole('blogger');
            $moderator = $authManager->createRole('moderator');
            $admin = $authManager->createRole('admin');

            // Create simple, based on action{$NAME} permissions
            $postComment = $authManager->createPermission('post comment');
            $viewMainPage = $authManager->createPermission('view main page');
            $viewPostDetails = $authManager->createPermission('view post details');
            $likePost = $authManager->createPermission('like post');
            $createPost = $authManager->createPermission('create post');
            $updatePost = $authManager->createPermission('update post');
            $deletePost = $authManager->createPermission('delete post');
            $deleteComment = $authManager->createPermission('delete comment');
            $blockBlogger = $authManager->createPermission('block blogger');
            $viewAllComments = $authManager->createPermission('view all comments');
            $viewAllBloggers = $authManager->createPermission('view all bloggers');
            $blockPostDetailsView = $authManager->createPermission('block post details view');
            $unblockPostDetailsView = $authManager->createPermission('unblock post details view');
            $deleteBlogger = $authManager->createPermission('delete blogger');

            // Add permissions in Yii::$app->authManager
            $authManager->add($postComment);
            $authManager->add($viewMainPage);
            $authManager->add($viewPostDetails);
            $authManager->add($likePost);
            $authManager->add($createPost);
            $authManager->add($updatePost);
            $authManager->add($deletePost);
            $authManager->add($deleteComment);
            $authManager->add($blockBlogger);
            $authManager->add($viewAllComments);
            $authManager->add($viewAllBloggers);
            $authManager->add($blockPostDetailsView);
            $authManager->add($unblockPostDetailsView);
            $authManager->add($deleteBlogger);

            $authManager->add($guest);
            $authManager->add($blogger);
            $authManager->add($moderator);
            $authManager->add($admin);

            // Add permission-per-role in Yii::$app->authManager
            // Guest
            $authManager->addChild($guest, $postComment);
            $authManager->addChild($guest, $viewMainPage);
            $authManager->addChild($guest, $viewPostDetails);
            $authManager->addChild($guest, $likePost);

            // Blogger
            $authManager->addChild($blogger, $postComment);
            $authManager->addChild($blogger, $viewMainPage);
            $authManager->addChild($blogger, $viewPostDetails);
            $authManager->addChild($blogger, $likePost);
            $authManager->addChild($blogger, $createPost);
            $authManager->addChild($blogger, $updatePost);
            $authManager->addChild($blogger, $deletePost);

            // Moderator
            $authManager->addChild($moderator, $deleteComment);
            $authManager->addChild($moderator, $blockBlogger);
            $authManager->addChild($moderator, $viewAllComments);
            $authManager->addChild($moderator, $viewAllBloggers);
            $authManager->addChild($moderator, $blockPostDetailsView);
            $authManager->addChild($moderator, $unblockPostDetailsView);

            // Admin
            $authManager->addChild($admin, $deletePost);
            $authManager->addChild($admin, $deleteBlogger);
            $authManager->addChild($admin, $deleteComment);
            $authManager->addChild($admin, $blockBlogger);
            $authManager->addChild($admin, $viewAllComments);
            $authManager->addChild($admin, $viewAllBloggers);
            $authManager->addChild($admin, $blockPostDetailsView);
            $authManager->addChild($admin, $unblockPostDetailsView);
        } catch (yii\console\Exception $e) {
            throw new $e;
        }
    }

    public function actionAssign() {
        $authManager = Yii::$app->authManager;

        try {
            $blogger= $authManager->getRole('blogger');
            $moderator = $authManager->getRole('moderator');
            $admin = $authManager->getRole('admin');

            $userAdmin = User::findByUsername('admin');
            $userModerator = User::findByUsername('moderator');
            $userBlogger = User::findByUsername('blogger');

            $authManager->assign($admin, $userAdmin->id);
            $authManager->assign($moderator, $userModerator->id);
            $authManager->assign($blogger, $userBlogger->id);
        } catch (yii\console\Exception $ex) {
            throw new $ex;
        }
    }

}
