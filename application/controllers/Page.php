<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
    const DefaultValue = 'default';

    private $data = ['errors' => []];

    public function index($page = self::DefaultValue)
    {
        // Import all helpers and libraries.
        $this->load->helper([
            'url',
            'form',
            'score',
        ]);
        $this->load->model('Users');
        $this->load->library([
            'session',
            'form_validation'
        ]);

        var_dump($this->session->username);

        // Check if the user is logged in
        $this->data['loggedIn'] = $this->session->username !== NULL;
        $this->data['username'] = $this->session->username;
        if($this->data['loggedIn'] && $page == 'login') {
            $page = self::DefaultValue;
        }

        $pageType = 'page';
        $headerPage = "page/$page-header";
        $bodyPage = "page/$page-body";

        $hasHeader = file_exists('./application/views/'.$headerPage.'.php');
        $hasBody = file_exists('./application/views/'.$bodyPage.'.php');

        if(!$hasHeader && !$hasBody) { // Check if the page exists
            $pageType = 'error';
            $page = 'pageNotFound';
        }

        //echo calculateScore('france', 3, 4, 3, 'noClue');

        // Show the page.
        $this->load->view('templates/header', $this->data);
        switch($pageType) {
            case 'page':
                $this->handlePage($page);
                if($hasHeader) {
                    $this->load->view($headerPage, $this->data);
                }
                $this->load->view('templates/intersection');
                if($hasBody) {
                    $this->load->view($bodyPage, $this->data);
                }
            break;
            case 'error':
                $this->load->view('error/'.$page);
                $this->load->view('templates/intersection');
            break;
        }
        $this->load->view('templates/footer');
    }

    /**
     * Handles some actions specific for certain pages.
     * @param $page
     */
    private function handlePage($page) {
        switch($page) {
            case 'login':
                $rules = [
                    [
                        'field' => 'username',
                        'label' => 'Gebruikersnaam',
                        'rules' => [
                            'required',
                        ],
                        'errors' => [
                            'required' => 'Vul uw gebruikersnaam in.',
                        ],
                    ],
                    [
                        'field' => 'password',
                        'label' => 'Pin',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Vul de pincode in.',
                        ],
                    ],
                    [
                        'field' => 'username',
                        'label' => 'Gebruikersnaam',
                        'rules' => [
                            ['usernameExists', [$this->Users, 'userExists']],
                        ],
                        'errors' => [
                            'usernameExists' => 'Gebruikersnaam bestaat niet.',
                        ],
                    ],
                ];

                // Parse all rules individually to enforce the order.
                $hasError = false;
                foreach($rules as $rule) {
                    $this->form_validation->set_rules([$rule]);

                    if(!$this->form_validation->run()) {
                        $hasError = true;
                        break;
                    }

                    $this->form_validation->reset_validation()->set_data($_POST);
                }
                if($hasError) {
                    break;
                } elseif($this->Users->checkCredentials(set_value('username'), set_value('password'))) { // Check if the credentials are valid
                    $this->session->username = set_value('username');
                    redirect('');
                    break;
                } else {
                    $this->data['errors'][] = 'Ongeldige combinatie van gebruikersnaam en pincode.';
                    break;
                }
            case 'logout':
                $this->session->sess_destroy();
                redirect();
                exit;
            default:
                break;
        }
    }
}
