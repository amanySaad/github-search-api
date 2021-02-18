<?php

namespace AmanySaad\GithubSearchApi\Api;

/**
 * This class provides several helpers for working with attributes.
 */
abstract class AbstractModelApi extends AbstractApi implements PopulateableInterface
{
    private $attributes = [];
    private $isLoaded = [];

    /**
     * Fully loads the model from GitHub.
     */
    abstract protected function load();

    /**
     * Populates the model with the given data.
     */
    public function populate(array $data)
    {
        foreach ($data as $name => $value) {
            $this->isLoaded[$name] = true;
            $this->attributes[$name] = $value;
        }
       
    }

    /**
     * @param string $attribute The name of the attribute
     * @return boolean Whenever an attribute is loaded or not
     */
    protected function isAttributeLoaded($attribute)
    {
        return array_key_exists($attribute, $this->isLoaded) && $this->isLoaded[$attribute];
    }

    /**
     * Returns the value of the attribute with the name $attribute.
     * @see isAttributeLoaded()
     */
    protected function getAttribute($attribute)
    {
        if (!$this->isAttributeLoaded($attribute)) {
            $this->load();
        }

        return $this->attributes[$attribute];
    }
}