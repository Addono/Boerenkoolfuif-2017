<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
    const DefaultValue = 'default';

    private $data = ['redirectTime' => 0];

    public function index($page = self::DefaultValue)
    {
        // Import all helpers and libraries.
        $this->load->helper('url');
        $this->load->helper('score');
        $this->load->library('session');

        // Check if the user is logged in
        $this->data['loggedIn'] = $this->session->username !== NULL;
        if($this->data['loggedIn'] && $page == 'login') {
            $page = self::DefaultValue;
        }

        $this->handlePost();

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
            case 'redirect':
                $this->load->view('redirect/'.$page.'-redirect-header');
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
                $username = $this->input->post('username'); // Add input validation
                $password = $this->input->post('password');

                $result['usernameSet'] = $username !== '';
                $result['passwordSet'] = $password !== '';

                if(!$result['usernameSet'] || !$result['usernameSet']) {

                }

                if($this->data['loggedIn']) {
                    $result['loginFailed'] = 'alreadyLoggedIn';
                } elseif(true) { // Check if the password is correct
                    $this->session->username = $username;
                    session_write_close();

                    $result['loginFailed'] = false;
                    $this->data['loggedIn'] = true;
                    $this->data['redirect'] = site_url('account'); // Redirect the user to the homepage
                    $this->data['redirectTime'] = 2;
                } else {
                    $result['loginFailed'] = 'incorrectCredentials';
                }
                break;
            case 'logout':
                if($this->data['loggedIn']) {
                    $this->session->sess_destroy();
                    $result['failure'] = false;
                    $this->data['loggedIn'] = false;
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
