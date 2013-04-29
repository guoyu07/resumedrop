<?php

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Resume {
    /**
     * Index of resume.
     * @var integer
     */
    private $id;
    /**
     * Id of the student uploading this resume.
     * @var Variable\Integer
     */
    private $user_id;

    /**
     * Name given to the resume
     * @var Variable\String
     */
    private $title;

    /**
     * Directory path to resume file.
     * @var Variable\File
     */
    private $path;

    /**
     * Unix time stamp made on resume upload.
     * @var Variable\Date
     */
    private $created;

    /**
     * Version number this resume has gone through
     * @var Variable\Integer
     */
    private $version;


}

?>
