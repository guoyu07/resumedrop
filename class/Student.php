<?php
namespace resumedrop;
/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Student extends \Resource {
    /**
     * Id to users
     * @var Variable\Integer
     */
    protected $user_id;

    /**
     * User name of student
     * @var Variable\String
     */
    protected $username;

    /**
     * @var Variable\String
     */
    protected $first_name;

    /**
     * @var Variable\String
     */
    protected $last_name;

    /**
     * Id of college
     * @var Variable\Integer
     */
    protected $college_id;

    /**
     * Number of submissions made by student
     * @var Variable\Integer
     */
    protected $submissions;

    protected $table = 'rd_student';

    public function __construct()
    {
        parent::__construct();
        $this->user_id = new \Variable\Integer(null, 'user_id');
        $this->username = new \Variable\String(null, 'username');
        $this->first_name = new \Variable\String(null, 'first_name');
        $this->last_name = new \Variable\String(null, 'last_name');
        $this->college_id = new \Variable\Integer(null, 'college_id');
        $this->submissions = new \Variable\Integer(null, 'submissions');
    }
}

?>
