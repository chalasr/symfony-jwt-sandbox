<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class AbstractAdmin extends Admin
{
    /**
     * Get parameters from an Admin class.
     *
     * @param $param
     * @param String $type
     * 
     * @return array
     */
    protected function _getParameter($param, $type='array')
    {
        if($type == 'single'){
            return $this->getConfigurationPool()->getContainer()->getParameter($param);
        }
        //get value from config
        $values = $this->getConfigurationPool()->getContainer()->getParameter($param);
        $rs = array();

        //process values
        foreach($values as $val){
            $rs[$val['label']] = $val['value'];
        }
        return $rs;
    }


}
