<?php


namespace MyProject\Models\Comments;


use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\Services\Db;

class Comment extends ActiveRecordEntity
{
    /** @var int */
    protected $authorId;

    /** @var int */
    protected $articleId;

    /** @var string */
    protected $text;

    /** @var \DateTime */
    protected $createdAt;

    public static function create(User $author, Article $article, array $fieIds):Comment
    {
        if (empty($fieIds['text'])) {
            throw new InvalidArgumentException('Не передано текст комментария');
        }
        if (mb_strlen(trim($fieIds['text'])) <= 2) {
            throw new InvalidArgumentException('Длина строки менее 2 символов,пробел за символ не считатается');
        }
        $comment = new Comment();
        $comment->setAuthor($author);
        $comment->setText($fieIds['text']);
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTime());
        $comment->save();
        return $comment;

    }
    public function updateFromComment(array $fieIds): Comment
    {

        if (empty($fieIds['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }
        if (mb_strlen(trim($fieIds['text'])) <= 2) {
            throw new InvalidArgumentException('Длина строки менее 2 символов,пробел за символ не считатается');
        }
        $this->setText($fieIds['text']);

        $this->save();
        return $this;
    }

    /**
     * @param int $articleId
     * @return array
     */
    public static function findArticleComments(int $articleId): array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE article_id=:id', ['id' => $articleId], static::class);
    }


    protected static function getTableName(): string
    {
        return 'comments';
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }


    public function setAuthor(User $author)
    {
        $this->authorId = $author->getId();
    }

    public function getAuthor():User
    {
        return User::getById($this->authorId);
    }


    public function getArticleId()
    {
        return $this->articleId;
    }



    public function setArticle(Article $article)
    {
        $this->articleId = $article->getId();
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt->format('Y-m-d H:m:s');
    }




}