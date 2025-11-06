<?php

namespace App\Entity;

use App\Repository\SlideRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlideRepository::class)]
class Slide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private static string $currentLang = 'en';

    // JSON translation storage
    #[ORM\Column(type: Types::JSON)]
    private array $name = [];

    #[ORM\Column(type: Types::JSON)]
    private array $title = [];

    #[ORM\Column(type: Types::JSON)]
    private array $alt = [];

    #[ORM\Column(type: Types::JSON)]
    private array $slug = [];

    #[ORM\Column(type: Types::JSON)]
    private array $text = [];

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $modified_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $order_num = null;

    #[ORM\ManyToOne]
    private ?MenuTarget $target = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->name = [];
        $this->title = [];
        $this->alt = [];
        $this->slug = [];
        $this->text = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // Smart getters/setters
    public function getName(?string $lang = null): ?string
    {
        $lang = $lang ?? self::$currentLang;
        return $this->name[$lang] ?? $this->name['en'] ?? null;
    }

    public function setName(?string $value, ?string $lang = null): static
    {
        $lang = $lang ?? self::$currentLang;
        $this->name[$lang] = $value;
        return $this;
    }

    public function getTitle(?string $lang = null): ?string
    {
        $lang = $lang ?? self::$currentLang;
        return $this->title[$lang] ?? $this->title['en'] ?? null;
    }

    public function setTitle(?string $value, ?string $lang = null): static
    {
        $lang = $lang ?? self::$currentLang;
        $this->title[$lang] = $value;
        return $this;
    }

    public function getAlt(?string $lang = null): ?string
    {
        $lang = $lang ?? self::$currentLang;
        return $this->alt[$lang] ?? $this->alt['en'] ?? null;
    }

    public function setAlt(?string $value, ?string $lang = null): static
    {
        $lang = $lang ?? self::$currentLang;
        $this->alt[$lang] = $value;
        return $this;
    }

    public function getSlug(?string $lang = null): ?string
    {
        $lang = $lang ?? self::$currentLang;
        return $this->slug[$lang] ?? $this->slug['en'] ?? null;
    }

    public function setSlug(?string $value, ?string $lang = null): static
    {
        $lang = $lang ?? self::$currentLang;
        $this->slug[$lang] = $value;
        return $this;
    }

    public function getText(?string $lang = null): ?string
    {
        $lang = $lang ?? self::$currentLang;
        return $this->text[$lang] ?? $this->text['en'] ?? null;
    }

    public function setText(?string $value, ?string $lang = null): static
    {
        $lang = $lang ?? self::$currentLang;
        $this->text[$lang] = $value;
        return $this;
    }

    // Methods to get/set all translations
    public function getNameTranslations(): array
    {
        return $this->name;
    }

    public function setNameTranslations(array $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getTitleTranslations(): array
    {
        return $this->title;
    }

    public function setTitleTranslations(array $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getAltTranslations(): array
    {
        return $this->alt;
    }

    public function setAltTranslations(array $alt): static
    {
        $this->alt = $alt;
        return $this;
    }

    public function getSlugTranslations(): array
    {
        return $this->slug;
    }

    public function setSlugTranslations(array $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getTextTranslations(): array
    {
        return $this->text;
    }

    public function setTextTranslations(array $text): static
    {
        $this->text = $text;
        return $this;
    }

    // Standard properties
    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeImmutable $modified_at): static
    {
        $this->modified_at = $modified_at;
        return $this;
    }

    public function getOrderNum(): ?int
    {
        return $this->order_num;
    }

    public function setOrderNum(?int $order_num): static
    {
        $this->order_num = $order_num;
        return $this;
    }

    public function getTarget(): ?MenuTarget
    {
        return $this->target;
    }

    public function setTarget(?MenuTarget $target): static
    {
        $this->target = $target;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getIdAsString(): string
    {
        return (string) $this->id;
    }

    public static function setCurrentLang(string $lang): void
    {
        self::$currentLang = $lang;
    }

    public static function getCurrentLang(): string
    {
        return self::$currentLang;
    }

    public function __toString(): string
    {
        return $this->getName() ?? '';
    }
}
