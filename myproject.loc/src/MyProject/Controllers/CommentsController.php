<?php


namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;

class CommentsController extends AbstractController
{


    public function add(int $commentId): void
    {

        if ($this->user === null) {
            throw new UnauthorizedException('Что бы добавить коммент , авторизируйтесь');
        }
        $article = Article::getById($commentId);

        if (!empty($_POST)) {
            try {
                $comment = Comment::create($this->user, $article, $_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('comments/add.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }
            header('Location: /articles/' . $comment->getArticleId() . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('comments/add.php', ['article' => $article]);
    }

    public function edit(int $commentId): void

    {
        $comment = Comment::getById($commentId);

        if ($comment === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            try {
                $comment->updateFromComment($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('comments/edit.php', ['error' => $e->getMessage(), 'comment' => $comment]);
                return;
            }
            header('Location: /articles/' . $comment->getArticleId() . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('comments/edit.php', ['comment' => $comment]);
    }

    public function delete(int $commentId): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException ('Удалить статью может только администратор');
        }
        $comment=Comment::getById($commentId);
        if ($comment !== null) {
            $comment->delete();
            $this->view->renderHtml('comments/delete.php');
        } else{
            throw new NotFoundException();

        }

    }


}