<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class AbstractAdmin extends Admin
{
    /**
     * Get parameters from an Admin class.
     *
     * @param $param
     * @param string $type
     *
     * @return array
     */
    protected function _getParameter($param, $type = 'array')
    {
        if ($type == 'single') {
            return $this->getConfigurationPool()->getContainer()->getParameter($param);
        }
        //get value from config
        $values = $this->getConfigurationPool()->getContainer()->getParameter($param);
        $rs = array();

        //process values
        foreach ($values as $val) {
            $rs[$val['label']] = $val['value'];
        }

        return $rs;
    }

    /**
     * Get create label.
     *
     * @return string
     */
    public function getCreateLabel()
    {
        return sprintf('%s Création', $this->translator->trans($this->getClassnameLabel(), [], 'AppAdminBundle'));
    }
}
