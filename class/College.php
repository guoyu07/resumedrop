<?php

namespace resumedrop;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class College extends \Resource {

    /**
     * Name of college
     * @var Variable\String
     */
    protected $name;

    protected $table = 'rd_college';

    public function __construct()
    {
        parent::__construct();
        $this->name = new \Variable\TextOnly(null, 'name');
    }

    public function setName($name)
    {
        $this->name->set($name);
    }

    public function getName()
    {
        return $this->name->get();
    }

}

?>
