<?php

namespace Adagio\Rad\Traits;

trait GuessIdentifier
{
    /**
     * Try to guess object unique identifier, returns NULL if failed.
     *
     * @param array|object $object
     *
     * @return ?string (type is not enforced, it can be an int or anything the object|array returns)
     */
    private function guessIdentifier($object)
    {
        if (is_array($object)) {
            return $this->guessIdentifierFromArray($object);
        } elseif (is_object($object)) {
            return $this->guessIdentifierFromObject($object);
        }

        throw new \InvalidArgumentException("Can guess identifier only for array or object.");
    }

    /**
     * Guess the identifier of $object or generates one from hash
     *
     * @param object|array $object
     *
     * @return string (type is not enforced, it can be an int or anything the object|array returns)
     */
    private function guessIdentifierOrHash($object)
    {
        if (!is_null($identifier = $this->guessIdentifier($object))) {
            return $identifier;
        }

        if (is_object($object)) {
            return spl_object_hash($object);
        }

        // $object is an array
        return md5(json_encode($object));
    }

    /**
     * Try to guess array's unique identifier, returns NULL if failed.
     *
     * @param array $data
     *
     * @return ?string
     */
    private function guessIdentifierFromArray(array $data)
    {
        $keys = array_keys($data);
        $keysMap = array_combine(array_map('strtolower', $keys), $keys);
        foreach (['id', 'identifier', 'pk', 'primary_key', 'primarykey'] as $property) {
            if (isset($keysMap[$property])) {
                return $data[$keysMap[$property]];
            }
        }

        return null;
    }

    /**
     * Try to guess object unique identifier, returns NULL if failed.
     *
     * @param array|object $object
     *
     * @return ?string
     */
    private function guessIdentifierFromObject($object)
    {
        foreach (['getId', 'getIdentifier', 'getPk', 'getPrimaryKey'] as $method) {
            try {
                return $object->$method();
            } catch (\Throwable $e) {}
        }

        // Generate an array with the object properties and rely on "guessIdentifierFromArray"
        $r = new \ReflectionClass($object);
        $data = [];
        /* @var $p \ReflectionProperty */
        foreach ($r->getProperties() as $p) {
            $p->setAccessible(true);
            $data[$p->getName()] = $p->getValue($object);
        }

        return $this->guessIdentifierFromArray($data);
    }
}
