<?php
namespace app\components;

class Myldap
{

	var $err_msg = "";

	function connect($servername,$port)
	{
		$ds = @ldap_connect($servername,$port);  // must be a valid LDAP server!
		return $ds;
	}
	
	function bind($ds,$rdn,$pwd)
	{
		if (!$ds)
			return FALSE;
		$r = @ldap_bind($ds,$rdn,$pwd);
		return $r;
	}
	
	function close($ds)
	{
		@ldap_close($ds);
	}
	
	function set_error($err_str)
	{
		return $this->err_msg = $err_str;
	}
	
	function clear_error()
	{
		return $this->err_msg = '';
	}
	
	function get_last_error()
	{
		return $this->err_msg;
	}
	
	function authenticate($ds,$rdn,$pwd,$port)
	{
		$ldap_con = $this->connect($ds,$port);
		ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
		if ($ldap_con == FALSE)
		{
			return ldap_error($ldap_con);
		}
		$ldap_bind = $this->bind($ldap_con,$rdn,$pwd);
		if ($ldap_bind == FALSE)
		{
			return ldap_error($ldap_con);
		}
		$this->close($ldap_con);
		
		return "Login Success";
	}
}