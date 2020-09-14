<?php

namespace App\Entity;

use DateTimeInterface;
use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

class TrackEvent implements JsonSerializable
{
    private UuidInterface $id;
    private ?UuidInterface $idUser;
    private string $sourceLabel;
    private DateTimeInterface $dateCreated;

    public function __construct(
        UuidInterface $id,
        ?UuidInterface $idUser,
        string $sourceLabel,
        DateTimeInterface $dateCreated
    ) {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->sourceLabel = $sourceLabel;
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return UuidInterface
     */
    public function getIdUser(): UuidInterface
    {
        return $this->idUser;
    }

    /**
     * @return string
     */
    public function getSourceLabel(): string
    {
        return $this->sourceLabel;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDateCreated(): DateTimeInterface
    {
        return $this->dateCreated;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->id->jsonSerialize(),
            'id_user' => $this->idUser ? $this->idUser->jsonSerialize() : null,
            'source_label' => $this->sourceLabel,
            'date_created' => $this->dateCreated->format('Y-m-d H:i:s'),
        ];
    }
}