<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('AbstractForm.php');
/**
 * Description of postForm
 *
 * @author Hung-Fu Aaron Chang
 */
class PostForm extends AbstractForm{
    //put your code here
    
    public function __construct($id) {
        $this->setAttribute('id', $id);
        $this->setAttribute('method', 'post');
    }
}

?>
