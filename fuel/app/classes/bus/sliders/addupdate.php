<?php

namespace Bus;

/**
 * AddUpdate Slider
 *
 * @package Bus
 * @created 2018-10-01
 * @version 1.0
 * @author AnhMH
 */
class Sliders_AddUpdate extends BusAbstract
{
    /** @var array $_required field require */
    protected $_required = array();

    /** @var array $_length Length of fields */
    protected $_length = array();

    /** @var array $_email_format field email */
    protected $_email_format = array(
        
    );

    /**
     * Call function get_list() from model Slider
     *
     * @author AnhMH
     * @param array $data Input data
     * @return bool Success or otherwise
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Slider::add_update($data);
            return $this->result(\Model_Slider::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }
}
