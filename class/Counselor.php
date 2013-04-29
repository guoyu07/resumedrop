<?php

namespace resumedrop;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Counselor extends \Resource {

    /**
     * User account for this counselor
     * @var Variable\Integer
     */
    protected $user_id;

    /**
     * Colleges associated with this counselor
     * @var array
     */
    private $colleges;
    protected $table = 'rd_counselor';

    public function __construct()
    {
        parent::__construct();
        $this->user_id = new \Variable\Integer(null, 'user_id');
    }

}

?>
