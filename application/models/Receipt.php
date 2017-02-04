<?php

/**
 * @property  CI_DB_driver $db
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 30-01-2017
 */
class Receipt extends CI_Model {
    private $receiptsTable = 'receipts_entries';

    public function __construct()
    {
        $this->load->database();
        $this->load->model('Users');
        $this->load->helper('score');
    }

    /**
     * Adds a new receipt to the database.
     * @param $username
     * @param $resource0
     * @param $resource1
     * @param $resource2
     * @param $specialty
     * @param $country
     * @return bool|mixed Returns the pin of the generated user on success, else returns false.
     * @internal param $group
     */
    public function insertReceipt($username, $resource0, $resource1, $resource2, $specialty, $country) {
        $data = [
            'group_id' => $this->Users->getId($username),
            'resource0' => $resource0,
            'resource1' => $resource1,
            'resource2' => $resource2,
            'specialty' => $specialty,
            'score' => calculateScore($country, $resource0, $resource1, $resource2, $specialty),
            'country' => $country,
        ];
        if($this->db->insert($this->receiptsTable, $data)) {
            return $data['score'];
        } else {
            return false;
        }
    }

    public function getUserReceipts($username) {
        return $this->db
            ->where(['group_id' => $this->Users->getId($username)])
            ->get($this->receiptsTable)
            ->result();
    }
}