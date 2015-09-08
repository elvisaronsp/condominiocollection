<?php 
	
Class email_model extends CI_Model
{
	
	public function EnviarEmail($EmailFrom, $NomeFrom, $EmailTo, $Assunto, $Mensagem, $Anexos = null)
    {
    	
		    	
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['mailtype'] ='html';
		$config['charset'] = 'UTF-8';
		$config['wordwrap'] = TRUE;
		
		$this->email->initialize($config);
		
        $this->email->from($EmailFrom, $NomeFrom);
    	$this->email->to( $EmailTo );

    	$this->email->subject( $Assunto );
    	$this->email->message( $Mensagem );

    	if ($Anexos != null){
    		foreach ($Anexos as $value) {
    			$this->email->attach( $value );
    		}
		}
    	return $this->email->send();
	}
}