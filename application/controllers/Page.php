<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
    const DefaultValue = 'default';

    private $data = ['redirectTime' => 0];

    public function index($page = self::DefaultValue)
    {
        // Import all helpers and libraries.
        $this->load->helper([
            'url',
            'score',
        ]);
        $this->load->model('Users');
        $this->load->library([
            'session',
        ]);

        // Check if the user is logged in
        $this->data['loggedIn'] = $this->session->username !== NULL;
        $this->data['username'] = $this->session->username;
        if($this->data['loggedIn'] && $page == 'login') {
            $page = self::DefaultValue;
        }

        $this->data['post'] = $this->handlePost();

        $pageType = 'page';
        $headerPage = "page/$page-header.php";
        $bodyPage = "page/$page-body.php";

        $hasHeader = file_exists('./application/views/'.$headerPage);
        $hasBody = file_exists('./application/views/'.$bodyPage);

        if(!$hasHeader && !$hasBody) { // Check if the page exists
            $pageType = 'error';
            $page = 'pageNotFound';
        }
        if(key_exists('redirect', $this->data)) {
            $pageType = 'redirect';
        }

        //echo calculateScore('france', 3, 4, 3, 'noClue');

        // Show the page.
        $this->load->view('templates/header', $this->data);
        switch($pageType) {
            case 'page':
                if($hasHeader) {
                $this->load->view($headerPage);
                }
                $this->load->view('templates/intersection');
                if($hasBody) {
                    $this->load->view($bodyPage);
                }
            break;
            case 'error':
                $this->load->view('error/'.$page);
                $this->load->view('templates/intersection');
            break;
        }
        $this->load->view('templates/footer');
    }

    private function handlePost() {
        $type = $this->input->post('type');
        $result['type'] = $type;
        echo $type;
        switch($type) {
            case 'login':
                $result['username'] = $this->input->post('username'); // Add input validation
                $result['password'] = $this->input->post('password');

                $result['usernameSet'] = $result['username'] !== '';
                $result['passwordSet'] = $result['password'] !== '';

                if(!$result['usernameSet'] || !$result['passwordSet']) {
                    $result['loginFailed'] = 'invalidForm';
                    break;
                }

                if($this->data['loggedIn']) {
                    $result['loginFailed'] = 'alreadyLoggedIn';
                } elseif($this->Users->checkCredentials($result['username'], $result['password'])) { // Check if the password is correct
                    $this->session->username = $result['username'];
                    session_write_close();

                    $result['loginFailed'] = false;
                    $this->data['loggedIn'] = true;
                    $this->data['username'] = $result['username'];
                    redirect();
                } else {
                    $result['loginFailed'] = 'incorrectCredentials';
                }
                break;
            case 'logout':
                if($this->data['loggedIn']) {
                    $this->session->sess_destroy();
                    $result['failure'] = false;
                    $this->data['loggedIn'] = false;
                    $this->data['username'] = null;
                } else {
                    $result['failure'] = 'notLoggedIn';
                }
                break;
            default: // Ignore all other values
                break;
        }

        return $result;
    }
}
