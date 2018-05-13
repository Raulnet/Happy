<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 13/05/18
 * Time: 13:11.
 */

namespace Happy\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Happy\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Project.
 *
 * @ORM\Table(name="project", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="Malcolm\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Project
{
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=36, nullable=false)
     * @ORM\Id
     * @Assert\Uuid(
     *     message="http.exception.uuid.pattern.false"
     * )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @ORM\Id
     * @Assert\NotBlank(
     *     message="http.exception.field.name.is.required"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url_documentation", type="string", length=255, nullable=false)
     * @ORM\Id
     * @Assert\NotBlank(
     *     message="http.exception.field.url_documentation.is.required"
     * )
     */
    private $urlDocumentation;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrlDocumentation(): string
    {
        return $this->urlDocumentation;
    }

    /**
     * @param string $urlDocumentation
     */
    public function setUrlDocumentation(string $urlDocumentation): void
    {
        $this->urlDocumentation = $urlDocumentation;
    }

}