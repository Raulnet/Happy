<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 23/05/18
 * Time: 21:23
 */

namespace Happy\Service;

use Symfony\Component\Cache\Simple\FilesystemCache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\Common\Annotations\AnnotationReader;
use Happy\Service\Model\EntityHydrator;

/**
 * Class NormalizerService
 *
 * @package Happy\Service
 */
class NormalizerService extends AbstractService
{
    /**
     * @param string $className
     *
     * @return EntityHydrator
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function getHydrator(string $className): EntityHydrator
    {
        $cache           = new FilesystemCache();
        $nameExploded = explode('\\', strtolower($className));
        $name = end($nameExploded);
        if (!$cache->has('entity.map.properties.' . $name)) {
            $entityMapProperty = $this->getReflexionProperties($className);
            $cache->set('entity.map.properties.' . $name, $entityMapProperty);
        }

        return new EntityHydrator($name, $className, $cache->get('entity.map.properties.' . $name));
    }

    /**
     * @param string $className
     *
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    private function getReflexionProperties(string $className)
    {
        $classMethods    = get_class_methods($className);
        $classProperties = [];
        foreach ($classMethods as $classMethod) {
            if (preg_match('/^get?/i', $classMethod)) {
                $labelProperty = lcfirst(str_replace('get', '', $classMethod));
                if (property_exists($className, $labelProperty)) {
                    $classProperties[$labelProperty] = $this->getProperty($className, $labelProperty);
                }
            }
        }

        return $classProperties;

    }

    /**
     * @param string $entityName
     * @param string $labelProperty
     *
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    private function getProperty(string $entityName, string $labelProperty): array
    {
        $property = [];

        $reflectionProperty  = new \ReflectionProperty($entityName, $labelProperty);
        $annotationReader    = new AnnotationReader();
        $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);

        foreach ($propertyAnnotations as $propertyAnnotation) {
            if ($propertyAnnotation instanceof Column) {
                $property['column_type'] = 'column';
                $property['type']        = $propertyAnnotation->type;
            }
            if ($propertyAnnotation instanceof ManyToMany) {
                $property['column_type']   = 'collection';
                $property['target_entity'] = $propertyAnnotation->targetEntity;
                $property['type']          = 'collection';
            }
            if ($propertyAnnotation instanceof ManyToOne) {
                $property['column_type']   = 'entity';
                $property['target_entity'] = $propertyAnnotation->targetEntity;
                $property['type']          = 'entity';
            }
            //TODO add ManyToMany OneToMany etc...
        }

        return $property;
    }
}