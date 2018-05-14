<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 13/05/18
 * Time: 21:15.
 */

namespace Happy\Tests\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Happy\Tests\AbstractTestCase;

/**
 * Class GlobalEntityTest.
 */
class GlobalEntityTest extends AbstractTestCase
{
    /** @var AnnotationReader */
    private $annotationReader;

    public function setUp()
    {
        $this->annotationReader = new AnnotationReader();
    }

    public function testAllEntities()
    {
        $mapEntities = $this->dumpEntityProperties();
        foreach ($mapEntities as $className => $properties) {
            $this->entityPropertiesTest($className, $properties);
        }
    }

    /**
     * @param string $className
     * @param array  $properties
     *
     * @throws \Exception
     */
    private function entityPropertiesTest(string $className, array $properties): void
    {
        foreach ($properties as $property => $params) {
            $this->entityPropertyTest($className, $property, $params);
        }
    }

    /**
     * @param string $className
     * @param string $property
     * @param array  $params
     *
     * @throws \Exception
     */
    private function entityPropertyTest(string $className, string $property, array $params): void
    {
        switch ($params['column_type']) {
            case 'column':
                $this->columnTest($className, $property, $params);
                break;
            case 'many_to_many':
                $this->manyToManyTest($className, $property, $params);
                break;
            case 'many_to_one':
                $this->manyToOneTest($className, $property, $params);
                break;
            default:
                throw new \Exception('Type Column '.$params['column_type'].' unknown');
        }
    }

    /**
     * @param string $className
     * @param string $label
     * @param array  $params
     *
     * @throws \Exception
     */
    private function columnTest(string $className, string $label, array $params): void
    {
        $entity = new $className();
        $type = trim(strtolower($params['type']));
        switch ($type) {
            case 'string':
                $string = $this->buildType($entity, $label, 'string');
                $this->assertTrue(is_string($string));
                break;
            case 'array':
                $array = $this->buildType($entity, $label, ['nick' => 'sax']);
                $this->assertTrue(is_array($array));
                break;
            case 'integer':
                $integer = $this->buildType($entity, $label, 123);
                $this->assertTrue(is_integer($integer));
                break;
            case 'int':
                $int = $this->buildType($entity, $label, 321);
                $this->assertTrue(is_integer($int));
            case 'datetime':
                $datetime = $this->buildType($entity, $label, new \DateTime('now'));
                $this->assertTrue($datetime instanceof $datetime);
                break;
            default:
                throw new \Exception('Type: '.$type.' unknown');
        }
        if ($params['nullable']) {
            $methodSetName = 'set'.ucfirst($label);
            if (method_exists($entity, $methodSetName)) {
                $entity->$methodSetName(null);
                $this->assertTrue(true);
            }
            $methodGetName = 'get'.ucfirst($label);
            if (method_exists($entity, $methodGetName)) {
                $this->assertTrue(null === $entity->$methodGetName());
            }
        }
    }

    /**
     * @param        $entity object
     * @param string $label
     * @param mixed  $value
     *
     * @return null|mixed
     */
    private function buildType($entity, $label, $value)
    {
        $methodSetName = 'set'.ucfirst($label);
        if (method_exists($entity, $methodSetName)) {
            $entity->$methodSetName($value);
            $this->assertTrue(true);
        }
        $methodGetName = 'get'.ucfirst($label);
        if (method_exists($entity, $methodGetName)) {
            return $entity->$methodGetName();
        }

        return null;
    }

    /**
     * @param string $className
     * @param string $property
     * @param array  $params
     */
    private function manyToManyTest(string $className, string $label, array $params): void
    {
        $entity = new $className();
        $collection = new ArrayCollection();
        $targetEntityName = $params['target_entity'];
        $targetEntity = new $targetEntityName();
        $collection->add($targetEntity);
        $value = $this->buildType($entity, $label, $collection);
        $this->assertTrue($value instanceof Collection);
    }

    /**
     * @param string $className
     * @param string $label
     * @param array  $params
     */
    private function manyToOneTest(string $className, string $label, array $params): void
    {
        $entity = new $className();
        $targetEntityName = $params['target_entity'];
        $targetEntity = new $targetEntityName();
        $value = $this->buildType($entity, $label, $targetEntity);
        $this->assertTrue($value instanceof $targetEntityName);
    }

    private function dumpEntityProperties(): array
    {
        $resultScan = preg_grep('/^([^.])/', scandir(__DIR__.'/../../src/Entity'));
        $entitiesMap = [];
        foreach ($resultScan as $result) {
            if (preg_match('/^.*php$/i', $result)) {
                $entityName = 'Happy\Entity\\'.str_replace('.php', '', $result);
                $entitiesMap[$entityName] = $this->getEntityProperty($entityName);
            }
        }

        return $entitiesMap;
    }

    /**
     * @param string $entityName
     *
     * @return array
     */
    private function getEntityProperty(string $entityName): array
    {
        $classMethods = get_class_methods($entityName);
        $classProperties = [];
        foreach ($classMethods as $classMethod) {
            if (preg_match('/^get?/i', $classMethod)) {
                $labelProperty = lcfirst(str_replace('get', '', $classMethod));
                if (property_exists($entityName, $labelProperty)) {
                    $classProperties[$labelProperty] = $this->getProperty($entityName, $labelProperty);
                }
            }
        }

        return $classProperties;
    }

    private function getProperty(string $entityName, string $labelProperty): array
    {
        $property = [];

        $reflectionProperty = new \ReflectionProperty($entityName, $labelProperty);
        $propertyAnnotations = $this->annotationReader->getPropertyAnnotations($reflectionProperty);
        foreach ($propertyAnnotations as $propertyAnnotation) {
            if ($propertyAnnotation instanceof Column) {
                $property['column_type'] = 'column';
                $property['type'] = $propertyAnnotation->type;
                $property['nullable'] = $propertyAnnotation->nullable;
            }
            if ($propertyAnnotation instanceof ManyToMany) {
                $property['column_type'] = 'many_to_many';
                $property['target_entity'] = $propertyAnnotation->targetEntity;
            }
            if ($propertyAnnotation instanceof ManyToOne) {
                $property['column_type'] = 'many_to_one';
                $property['target_entity'] = $propertyAnnotation->targetEntity;
            }
            //TODO add ManyToMany OneToMany etc...
        }

        return $property;
    }
}