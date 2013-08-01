<?php

namespace resumedrop;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Resume extends \Resource {

    /**
     * Id of the student uploading this resume.
     * @var Variable\Integer
     */
    protected $user_id;

    /**
     * Name given to the resume
     * @var Variable\String
     */
    protected $title;

    /**
     * Directory path to resume file.
     * @var Variable\File
     */
    protected $path;

    /**
     * Unix time stamp made on resume upload.
     * @var Variable\Date
     */
    protected $created;

    /**
     * Version number this resume has gone through
     * @var Variable\Integer
     */
    protected $version;

    protected $table = 'rd_resume';

    public function __construct()
    {
        parent::__construct();
        $this->user_id = new \Variable\Integer(null, 'user_id');
        $this->title = new \Variable\String(null, 'title');
        $this->path = new \Variable\File(null, 'path');
        $this->created = new \Variable\Date(null, 'created');
        $this->version = new \Variable\Integer(null, 'version');
    }

}

?>
