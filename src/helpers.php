<?php

if (! function_exists('getProperty')) {
    function getProperty($obj, $property, $failedReturn = null)
    {
        if (property_exists($obj, $property)) {
            return $obj->$property;
        }

        return $failedReturn;
    }
}