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
        $this->name = new \Variable\String(null, 'name');
    }

}

?>
