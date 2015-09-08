<?php

/**
 * @author Gabriel de Melo Paulon
 * @email  gabriel.paulon@gmail.com
 * @copyright 2012
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Autenticacao {

    function __construct(){
        //check login
        $this->_check_login();
    }

    private function _check_login()
    {
        //these pages won't be checked
        $public_access = array( 
            'login'
        );
        
        $CI =& get_instance(); //Busca o CIgniter por referencia.  
        
        //current controller
        $current_function = $CI->uri->segment(1);

        //if not in public_access array we will chekc the data
        if( !in_array( $current_function, $public_access ) ) {
           
            if( $CI->session->userdata('logado') != true || $CI->session->userdata('id_usuario')==NULL) {
                redirect('login');
            }
        }

    }
}

?>