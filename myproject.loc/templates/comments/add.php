<?php
/**
 *
 * @var \MyProject\Models\Articles\Article $article
 */
include __DIR__ . '/../header.php'; ?>

<?php if (!empty($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>
    <h1>Создание нового комментария</h1>
<?php if(!empty($user)):?>
    <form action="/articles/<?=$article->getId()?>/comments" method="post">
        <label for="text">Текст комментария</label><br>
        <textarea name="text" id="text" rows="10" cols="80"><?= $_POST['text'] ?? '' ?></textarea><br>
        <br>
        <input type="submit" value="Создать">
    </form>
<?php endif;?>
<?php include __DIR__ . '/../footer.php';?>