<?php
/**
 *@var \MyProject\Models\Articles\Article $articles
 *@var \MyProject\Models\Users\User $user
 */
include __DIR__ . '/../header.php' ?>
<?php foreach ($articles as $article): ?>
        <h2><a href="/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h2>
    <p><?= $article->getText() ?></p>
    <?php if ($user !== null && $user->isAdmin()): ?>
        <div style="text-align: right;">
            <a href="/articles/<?= $article->getId() ?>/edit">Редактировать || </a>

            <a href="/articles/<?= $article->getId() ?>/delete">Удалить</a>
        </div>
    <?php endif; ?>
    <hr>
<?php endforeach; ?>
<?php include __DIR__ . '/../footer.php' ?>
