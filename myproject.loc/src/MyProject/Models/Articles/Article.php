<?php

namespace MyProject\Models\Articles;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;

use MyProject\Models\Users\User;


class Article extends ActiveRecordEntity
{

    /** @var string */
    protected $name;
    /** @var string */
    protected $text;
    /** @var int */
    protected $authorId;
    /** @var string */
    protected $createdAt;
    /** @var int */



    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /** @return string */
    public function getText(): string
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    /** @return User */
    public function getAuthor(): User
    {

        return User::getById($this->authorId);
    }

    public function setAuthor(User $author): void
    {
        $this->authorId = $author->getId();
    }


    protected static function getTableName(): string
    {
        return 'articles';
    }

    public static function createFromArray(array $fieIds, User $author): Article
    {
        if (empty($fieIds['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }
        if (empty($fieIds['text'])) {
            throw new InvalidArgumentException('Не передан тест статьи');
        }
        $article = new Article();
        $article->setAuthor($author);
        $article->setName($fieIds['name']);
        $article->setText($fieIds['text']);

        $article->save();
        return $article;
    }

    public function updateFromArray(array $fieIds): Article
    {
        if (empty($fieIds['name'])) {
            throw new InvalidArgumentException('Не передано навзвание статьи');
        }
        if (empty($fieIds['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }
        $this->setName($fieIds['name']);
        $this->setText($fieIds['text']);

        $this->save();
        return $this;
    }
    public function getParsedText():string {
        $parser=  new \Parsedown();
        return $parser->text($this->getText());
    }


}