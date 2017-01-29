<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
    const DefaultValue = 'default';

    public function index($page = self::DefaultValue)
    {
        // Import all helpers and libraries.
        $this->load->helper('url');
        $this->load->helper('score');
        $this->load->library('session');

        // Check if the user is logged in
        $data['loggedIn'] = $this->session->username !== NULL;
        if($data['loggedIn'] && $page == 'login') {
            $page = self::DefaultValue;
        }

        $this->handlePost($this->input->post('type'));

        $pageType = 'page';
        $headerPage = "page/$page-header.php";
        $bodyPage = "page/$page-body.php";

        $hasHeader = file_exists('./application/views/'.$headerPage);
        $hasBody = file_exists('./application/views/'.$bodyPage);

        if(!$hasHeader && !$hasBody) { // Check if the page exists
            $pageType = 'error';
            $page = 'pageNotFound';
        }

        //echo calculateScore('france', 3, 4, 3, 'noClue');

        // Show the page.
        $this->load->view('templates/header', $data);
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
        }
        $this->load->view('templates/footer');
    }

    private function handlePost($type) {
        switch($type) {
            case 'login':

                break;
            default: // Ignore all other values
                break;
        }
    }
}
