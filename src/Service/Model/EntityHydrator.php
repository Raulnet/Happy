<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 23/05/18
 * Time: 22:03
 */

namespace Happy\Service\Model;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class EntityHydrator
 *
 * @package Happy\Service\Model
 */
class EntityHydrator
{
    /**
     * @var string
     */
    private $className;

    private $name;

    /**
     * @var array
     */
    private $entityMapProperties;

    /**
     * EntityHydrator constructor.
     *
     * @param $className
     * @param $entityMapProperties
     */
    public function __construct(string $name, string $className, array $entityMapProperties)
    {
        $this->name                = $name;
        $this->className           = $className;
        $this->entityMapProperties = $entityMapProperties;
    }

    /**
     * @param object  $entity
     * @param Request $request
     *
     * @return object
     * @throws \Exception
     */
    public function handleRequest(object $entity, Request $request): object
    {
        $content = $request->getContent();
        if (empty($content)) {
            throw new HttpException(JsonResponse::HTTP_CONFLICT, 'http.exception.no.body.content.submit');
        }
        $propertiesValues = json_decode($content, true)[$this->name];
        foreach ($propertiesValues as $property => $value) {

            $property = $this->getLabelProperty($property);

            $reflexionProperty = $this->entityMapProperties[$property];
            $method            = 'set' . ucfirst($property);

            if (method_exists($entity, $method)) {
                switch ($reflexionProperty['type']) {
                    case 'string':
                        $entity->$method($value);
                        break;
                    case 'array':
                        $entity->$method($value);
                        break;
                    case 'integer':
                        $entity->$method($value);
                        break;
                    case 'int':
                        $entity->$method($value);
                    case 'datetime':
                        $datetime = new \DateTime($value);
                        $entity->$method($datetime);
                        break;
                    case 'collection':
                        break;
                    default:
                        throw new \Exception('Type: ' . $this->entityMapProperties['type'] . ' unknown');
                }
            }
        }
        return $entity;
    }

    /**
     * @param $dataNameProperty
     *
     * @return string
     */
    private function getLabelProperty(string $dataNameProperty): string
    {
        $labelProperty = null;
        $expStr        = explode('_', $dataNameProperty);
        foreach ($expStr as $value) {
            $labelProperty .= ucfirst($value);
        }

        return lcfirst($labelProperty);
    }


}