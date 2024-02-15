<?php
 
namespace App\Entity;
 
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\PictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
 
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: PictureRepository::class)]
 
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 
    #[ORM\Column(length: 255)]
    private ?string $realName = null;
 
    #[ORM\Column(length: 255)]
    private ?string $realPath = null;
 
    #[ORM\Column(length: 255)]
    private ?string $publicPath = null;
 
    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;
 
    #[ORM\Column(length: 255)]
    private ?string $name = null;
 
    #[ORM\Column(length: 24)]
    private ?string $status = null;
 
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;
 
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;
 
    #[Vich\UploadableField(mapping: "pictures", fileNameProperty:"realpath")]
    private $file;
 
    public function getId(): ?int
    {
        return $this->id;
    }
 
    public function getRealName(): ?string
    {
        return $this->realName;
    }
 
    public function setRealName(string $realName): static
    {
        $this->realName = $realName;
 
        return $this;
    }
 
    public function getRealPath(): ?string
    {
        return $this->realPath;
    }
 
    public function setRealPath(string $realPath): static
    {
        $this->realPath = $realPath;
 
        return $this;
    }
 
    public function getPublicPath(): ?string
    {
        return $this->publicPath;
    }
 
    public function setPublicPath(string $publicPath): static
    {
        $this->publicPath = $publicPath;
 
        return $this;
    }
 
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }
 
    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;
 
        return $this;
    }
 
    public function getName(): ?string
    {
        return $this->name;
    }
 
    public function setName(string $name): static
    {
        $this->name = $name;
 
        return $this;
    }
 
    public function getStatus(): ?string
    {
        return $this->status;
    }
 
    public function setStatus(string $status): static
    {
        $this->status = $status;
 
        return $this;
    }
 
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
 
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
 
        return $this;
    }
 
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
 
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
 
        return $this;
    }
 
        public function getFile(): ?File
    {
        return $this->file;
    }
    public function setFile(?File $file): Picture
    {
        $this->file = $file;
        return $this;
    }
 
}