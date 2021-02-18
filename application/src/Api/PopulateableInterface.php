<?php

namespace AmanySaad\GithubSearchApi\Api;

interface PopulateableInterface
{
    /**
     * Populates the model with the given data
     */
    public function populate(array $data);
}