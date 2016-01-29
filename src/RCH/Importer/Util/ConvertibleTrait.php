<?php

namespace RCH\Importer\Util;

trait ConvertibleTrait
{
    /**
     * Transforms values with given Converters.
     *
     * @param array $data
     *
     * @return array Previous data with changed values
     */
    protected function useConverters(array $data)
    {
        foreach ($this->converters as $converter) {
            $data = $converter->convert($data);
        }

        return $data;
    }
}
