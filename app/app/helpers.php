<?php

if (!function_exists('isResponseValid')) {
    function isResponseValid(array $entity): bool
    {
        return !$entity || isset($entity['success']) && !$entity['success'];
    }
}
