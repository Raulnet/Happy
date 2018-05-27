<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 25/05/18
 * Time: 22:31
 */

namespace Happy\Service;


use Doctrine\ORM\EntityManagerInterface;
use Happy\Entity\Documentation;
use Happy\Entity\Project;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class DocumentationService
 *
 * @package Happy\Service
 */
class DocumentationService extends AbstractService
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * DocumentationService constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Project $project
     * @param string  $documentationRaw
     */
    public function pushDocumentationRaw(Project $project, string $docSwagger): void
    {
        $data = json_decode($docSwagger, true);
        $version = $data['info']['version'];
        if(empty($version)) {
            throw new HttpException('http.exception.format.documentation.wrong');
        }
        $version.='.'.time();
        $lastDocumentation = $this->manager->getRepository(Documentation::class)
                                           ->findOneBy(['project' => $project], ['dateCreation' => 'DESC']);
        if(empty($lastDocumentation)) {
            $this->createDocumentation($project, $docSwagger, $version);

        } else {
            $documentationPath = $lastDocumentation->getPath();
            $docStream = file_get_contents($documentationPath);
            if($docStream !== $docSwagger) {
                $this->createDocumentation($project, $docSwagger, $version);
            }
        }
    }

    /**
     * @param Project $project
     * @param string  $swaggerDoc
     * @param string  $version
     */
    private function createDocumentation(Project $project, string $swaggerDoc, string $version): void {
        $uuid = Uuid::uuid4();
        $fileName = '/tmp/'.$uuid->toString().'.json';
        $this->putFile($fileName, $swaggerDoc);
        $this->putDocumentation($uuid->toString(), $project, $version, $fileName);
    }

    /**
     * @param string $fileName
     * @param string $content
     */
    private function putFile(string $fileName, string $content): void
    {
        file_put_contents($fileName, $content);
    }

    /**
     * @param string  $uuid
     * @param Project $project
     * @param string  $version
     * @param string  $fileName
     */
    private function putDocumentation(string $uuid, Project $project, string $version, string $fileName): void
    {
        $documentation = new Documentation();
        $documentation->setId($uuid);
        $documentation->setProject($project);
        $documentation->setPath($fileName);
        $documentation->setVersion($version);
        $this->manager->persist($documentation);
        $this->manager->flush();
    }


}