<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;

use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;
use MyProject\Models\Articles\Article;


class ArticlesController extends AbstractController
{

    public function view(int $articleId): void
    {

        $article = Article::getById($articleId);


        if ($article === null) {
            throw new NotFoundException();
        }
        $comments= Comment::findArticleComments($articleId);

        $this->view->renderHtml('articles/view.php', [
            'article' => $article,'comments'=>$comments
        ]);


    }

    public function edit(int $articleId)
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Редактировать статью может только администратор');
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function add(): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException ('Добавить статью может только администратор');
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit;
        }
        $this->view->renderHtml('articles/add.php');

    }

    public function delete(int $articleId)
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException ('Удалить статью может только администратор');
        }
        $article = Article::getById($articleId);
        if ($article !== null) {
                $article->delete();
                $this->view->renderHtml('articles/delete.php');
            } else{
            throw new NotFoundException();

            }
        }


}

