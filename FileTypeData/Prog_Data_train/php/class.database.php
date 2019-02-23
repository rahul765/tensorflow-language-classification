<?php
/*======================================================================*\
|| #################################################################### ||
|| # PHP Utilities Alpha 2                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #         Useful Everyday PHP classes, that work as one!           # ||
|| # ---------------------------------------------------------------- # ||
|| #     All PHP code in this file is ©2014 VisionWare Studios        # ||
|| #        This script is released under The MIT License.            # ||
|| #                                                                  # ||
|| #  --------------- PHP VERSION 5.5.0 OR GREATER -----------------  # ||
|| #                                                                  # ||
|| #                  http://visionware-studios.com                   # ||
|| #################################################################### ||
\*======================================================================*/

class Database
{
  private $_connection;
  protected $_info;
  protected $_last_query;
    public $session;

  public function __construct($server, $username, $password, $database, $port, $prefix = '')
  {
    $this->_info = array(
    'Server'   => $server,
    'Username' => $username,
    'Password' => $password,
    'Database' => $database,
    'Port'     => $port,
    'Prefix'   => $prefix
    );

        if ( ! empty($this->_info['Prefix']))
        {
          $this->_info['Prefix'] = $this->_info['Prefix'] . '_';
        }

    $this->_connection = new mysqli(
    $this->_info['Server'],
    $this->_info['Username'],
    $this->_info['Password'],
    $this->_info['Database'],
    $this->_info['Port']
    );

    if ($this->_connection->connect_error)
    {
      Error_Handle::GEN_ERROR('<strong>Error Connecting to MySQL:</strong> ' . $this->_connection->connect_error);
    }

      $this->session = new Session();

      return $this->_info;
  }

  public function Connection()
  {
    return $this->_connection;
  }

  public function session()
  {
    return $this->session;
  }

  protected function escape($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }

  public function customQuery($sql)
  {
    $result = $this->Connection()->query($sql);

    return $result;
  }

  protected function statement($sql, $params)
  {
      if ( ! $stmt = $this->Connection()->prepare($sql))
      {
        Error_Handle::GEN_ERROR('Prepare Failed: ' . $stmt->error);
        return FALSE;
      }

      if ( ! empty($params)) call_user_func_array(array($stmt, 'bind_param'), refValues($params));

      if ( ! $stmt->execute())
      {
        Error_Handle::GEN_ERROR('Execute Failed: ' . $stmt->error);
        return FALSE;
      }

      return $stmt;
  }

}
