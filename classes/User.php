<?php

/**
 * User
 *
 * A person or entity that can log in to the site
 */
class User
{
    /**
     * Unique identifier
     * @var integer
     */
    public $id;

    /**
     * Unique username
     * @var string
     */
    public $username;

    /**
     * Password
     * @var string
     */
    public $password;

    /**
     * Email
     * @var string
     */
    public $email;

    /**
     * Authenticate a user by username and password
     *
     * @param object $conn Connection to the database
     * @param string $username Username
     * @param string $password Password
     *
     * @return boolean True if the credentials are correct, null otherwise
     */
    public static function authenticate($conn, $username, $password)
    {
        $sql = "SELECT *
                FROM users
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            if (password_verify($password, $user->password)) {
                return true;
            }
            else return false;
        }
        else return false;
    }

    public static function isUserNameAvailable($conn, $username)
    {
        $sql = "SELECT *
                FROM users
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
           
            return false;
        }
        else return true;
    }

    public static function getUserIdOrNull($conn, $username, $password)
    {
        $sql = "SELECT *
                FROM users
                WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');

        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return $user->id;
        } else return null;
    }

    public static function registerNewUser($conn, $username, $password, $email)
    {
        $sql = "INSERT INTO users 
                    VALUES(null, :username, :password, :email)";
    
        $stmt = $conn->prepare($sql);
    
            $stmt->bindValue(':username',   $username,  PDO::PARAM_STR);
            $stmt->bindValue(':password',   $password,  PDO::PARAM_STR);
            $stmt->bindValue(':email',      $email,     PDO::PARAM_STR);
    
            $stmt->execute();    
    }

    public static function copyDefaultIncomes ($conn, $userId)
    {
        $sql = "SELECT * FROM incomes_category_default";

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        $incomes_category_defaults = $stmt->fetchAll();

        foreach ($incomes_category_defaults as $income) {
            $sql =  "INSERT INTO incomes_category_assigned_to_users VALUES(null, :userId, :name)";
            
            $stmt = $conn->prepare($sql);
    
            $stmt->bindValue(':userId',     $userId,              PDO::PARAM_STR);
            $stmt->bindValue(':name',       $income['name'],      PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    public static function getIncomeBills ($conn, $userId, $dateStart, $dateEnd)
    {
        $sql = "SELECT * FROM incomes WHERE user_id = :userId
        AND date_of_income BETWEEN :dateStart AND :dateEnd";

        $stmt = $conn->prepare($sql);

       
    
        $stmt->bindValue(':userId',     $userId,        PDO::PARAM_STR);
        $stmt->bindValue(':dateStart',  $dateStart,     PDO::PARAM_STR);
        $stmt->bindValue(':dateEnd',    $dateEnd,       PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
        /**
       $income_bills = $stmt->fetchAll();
        foreach ($income_bills as $income) {
            print_r($income);
        }
        */
    }
}
