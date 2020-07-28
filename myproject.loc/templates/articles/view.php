<?php
/**
 * @var \MyProject\Models\Articles\Article $article
 * @var \MyProject\Models\Comments\Comment[] $comments
 * @var \MyProject\Models\Users\User $user
 */
include __DIR__ . '/../header.php' ?>

<h1><?= $article->getName() ?></h1>
<p><?= $article->getParsedText() ?></p>
<p> Автор: <?= $article->getAuthor()->getNickname() ?></p>
<?php if ($user !== null && $user->isAdmin()): ?>
    <a href="/articles/<?= $article->getId() ?>/edit">Редактировать статью</a>
<?php endif; ?>
<br>


<h2>Комментарии:</h2>

<?php foreach ($comments as $comment): ?>
    <strong><?= $comment->getAuthor()->getNickname() ?></strong>
    <p><?= $comment->getText() ?></p>

    <?php if ($user !== null && ($user->isAdmin() || ($user->getId() == $comment->getAuthorId()))): ?>
        <div style="text-align: right;">
        <a href="/comments/<?= $comment->getId() ?>/edit">Редактировать</a>
            </div>
        <?php endif; ?>
    <?php if ($user !== null && $user->isAdmin()):?>
    <div style="text-align: right;">
        <a href="/comments/<?=$comment->getId() ?>/delete">Удалить</a>
    </div>

    <?php endif;?>

    <hr>
<?php endforeach; ?>
<a href="/articles/<?= $article->getId() ?>/comments">Добавить комментарий</a>
<?php include __DIR__ . '/../footer.php' ?>
