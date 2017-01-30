<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Users extends CI_Model {
    private $usersTable = 'users_table';

    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Adds a new user to the database.
     * @param $username
     * @param $role
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     */
    public function insertUser($username, $role) {
        $data = [
            'username' => $username,
            'pin' => generatePin(),
            'role' => $role,
        ];
        if($this->db->insert_string($this->usersTable, $data)) {
            return $data['pin'];
        } else {
            return false;
        }
    }

    /**
     * Checks if a user matching the credentials is present in the database.
     * @param $username
     * @param $pin
     * @return bool
     */
    public function checkCredentials($username, $pin) {
        $this->db->where([
            'username' => $username,
            'pin' => $pin,
        ]);
        return $this->db->count_all_results($this->usersTable) !== 0;
    }

    /**
     * Resets the pin of a user.
     * @param $username
     * @return bool|void
     */
    public function resetPin($username) {
        $pin = $this->generatePin();
        $success = $this->db->update(
            $this->usersTable,
            ['pin' => $pin],
            ['username' => $username]
        );
        if($success) {
            return $pin;
        } else {
            return false;
        }
    }

    /**
     * Generates a random pin of 6 numbers as a string.
     * @return string
     */
    private function generatePin() {
        $pin = "";
        for($i = 0; $i < 6; $i++) {
            $pin .= strval(random_int(0,9));
        }
        return $pin;
    }
}