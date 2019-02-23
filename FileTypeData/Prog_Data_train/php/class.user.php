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

require_once 'class.database.php';
require_once 'class.session.php';
require_once 'class.errors.php';
require_once 'functions.php';

class User extends Database
{
	private $_user = array();

	public function fetch($username)
	{
		$userInfo = array(
		'username' => $username
		);

		$sql = "SELECT username, password FROM " . $this->_info['Prefix'] . "users WHERE `" . implode("`, `", arr_keys($userInfo)) . "` = ?";
		$fetch = $this->statement($sql, array('s', arr_vals($userInfo)[0]));
		$fetch->store_result();
		if ($fetch->num_rows == 0)
		{
			$this->_user['exists'] = FALSE;
		}
		else
		{
			$this->_user['exists'] = TRUE;
		}

		$fetch->bind_result($username, $password);
		$fetch->fetch();
		$this->_user['username'] = $username;
		$this->_user['password'] = $password;
		$fetch->close();
		return $this->_user;
	}

    public function createAccount($username, $password)
	{

        $userInfo = array(
            'username'  => $username,
            'password'  => $this->passwordHash($password),
						'is_banned' => 0
        );

		$existCheck = $this->fetch($username);

		if ($existCheck['exists'] == TRUE)
		{
            return FALSE;
		}

		$sql = "INSERT INTO " . $this->_info['Prefix'] . "users (`" . implode("`, `", arr_keys($userInfo)) . "`) VALUES (?, ?, ?)";
		$account_creation = $this->statement($sql, array('ssi', arr_vals($userInfo)[0], arr_vals($userInfo)[1], arr_vals($userInfo)[2]));
		$account_creation->close();
		return TRUE;

	}

	public function authenticate($username, $password)
	{
	   $info = $this->fetch($username);

       if ($info['exists'] == FALSE) {
           exit('The specified user does not exist!');
       }

        if (password_verify($password, $info['password']))
        {
            $_SESSION['username'] = $this->escape($info['username']);
            return TRUE;
        }
        else
        {
            return FALSE;
        }

	}

    public function deleteAccount($username)
    {
        $info = $this->fetch($username);

        if ($info['exists'] == FALSE)
        {
            exit('The specified user does not exist!');
        }

        $userInfo = array(
            'username' => $username
        );

				$sql = "DELETE FROM " . $this->_info['Prefix'] . "users WHERE `" . implode("`, `", arr_keys($userInfo)) . "` = ? LIMIT 1";
				$account_deletion = $this->statement($sql, array('s', arr_vals($userInfo)[0]));
				$account_deletion->close();
				return TRUE;
    }

    public function changePassword($username, $password)
    {
        $info = $this->fetch($username);

        if ($info['exists'] == FALSE)
        {
            return FALSE;
        }

        $userInfo = array(
            'username' => $username,
            'password' => $this->passwordHash($password)
        );

				$sql = "UPDATE " . $this->_info['Prefix'] . "users SET password = ? WHERE username = ?";

				$update_account = $this->statement($sql, array('ss', arr_vals($userInfo)[1], arr_vals($userInfo)[0]));
				$update_account->close();

				return TRUE;
    }

	private function passwordHash($string)
	{
        $hash = array(
            'Password' => $string
        );
		$hashed_password = password_hash($hash['Password'], PASSWORD_DEFAULT);
        return $hashed_password;
	}

	public function check_is_banned($username)
	{
		$info = $this->fetch($username);

		if ($info['exists'] == FALSE)
		{
			return FALSE;
		}

		$userInfo = array(
		'username' => $username
		);

		$sql = "SELECT uid FROM " . $this->_info['Prefix'] . "users WHERE `" . implode("`, `", arr_keys($userInfo)) . "` = ?";
		$check_is_banned = $this->statement($sql, array('s', arr_vals($userInfo)[0]));
		$check_is_banned->bind_result($id);
		$check_is_banned->fetch();
		$this->_info['uid'] = $id;
		$check_is_banned->close();

		return $this->_info;
	}

    public function ban($username, $is_banned_col)
    {
       $info = $this->fetch($username);

        $id = $this->check_is_banned($username);

        if ($info['exists'] == FALSE)
        {
            return FALSE;
        }

        $userInfo = array(
            'username'  => $username,
            'is_banned' => $is_banned_col,
        );

				$sql = "UPDATE " . $this->_info['Prefix'] . "users SET is_banned = ? WHERE username = ?";
				$ban = $this->statement($sql, array('si', arr_vals($userInfo)[1], arr_vals($userInfo)[2]));
				$ban->close();
				return TRUE;
    }

    public function unban($username)
    {
        $info = $this->fetch($username);

        if ($info['exists'] == FALSE)
        {
            return FALSE;
        }

        $userInfo = array(
            'username'  => $username,
            'is_banned' => 0
        );

				$sql = "UPDATE " . $this->_info['Prefix'] . "users SET is_banned = ? WHERE username = ?";
				$unban = $this->statement($sql, array('si', arr_vals($userInfo)[0], arr_vals($userInfo)[1]));
				$unban->close();
				return TRUE;
    }


    public function banWave()
    {
			$userInfo = array(
				'username' => self::username()
			);

			if ( ! self::username())
			{
				return FALSE;
			}
			else
			{
				$userInfo['username'] = self::username();
			}

				$sql = "SELECT is_banned FROM " . $this->_info['Prefix'] . "users WHERE username = ?";
				$banWave = $this->statement($sql, array('is', arr_vals($userInfo)[0]));
				$banWave->bind_result($is_banned);
				$banWave->fetch();
				$banWave->close();
				if ($is_banned === 1)	Error_Handle::DISPLAY_BANNED();
    }

    public static function username()
    {
        return $_SESSION['username'];
    }
}
