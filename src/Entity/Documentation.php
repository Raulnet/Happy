<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 13/05/18
 * Time: 13:34.
 */

namespace Happy\Entity;

use Happy\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Documentation.
 *
 * @ORM\Table(name="documentation", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="Happy\Repository\DocumentationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Documentation
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
     * @ORM\Column(name="version", type="string", length=36, nullable=false)
     * @Assert\NotBlank(
     *     message="http.exception.field.version.is.required"
     * )
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     * @Assert\NotBlank(
     *     message="http.exception.field.path.is.required"
     * )
     */
    private $path;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Happy\Entity\Project")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $project;

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
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }
}
