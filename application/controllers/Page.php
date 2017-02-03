<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property    CI_Form_validation  $form_validation
 * @property    Users               $Users
 * @property    Receipt             $Receipt
 * @property    CI_Session          $session
 * @property    CI_DB_query_builder $db
 */
class Page extends CI_Controller {
    const DefaultValue = 'default';

    private $data = ['errors' => []];

    public function index($page = self::DefaultValue, $subPage = null)
    {
        // Import all helpers and libraries.
        $this->load->helper([
            'url',
            'form',
            'score',
        ]);
        $this->load->model([
            'Users',
            'Receipt',
        ]);
        $this->load->library([
            'session',
            'form_validation'
        ]);

        // Check if the user is logged in
        $this->data['loggedIn'] = $this->session->username !== NULL;
        $this->data['username'] = $this->session->username;
        if($this->data['loggedIn']) {
            $this->data['role'] = $this->Users->userRole($this->data['username']);
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

        // Show the page.
        $this->load->view('templates/header', $this->data);
        switch($pageType) {
            case 'page':
                $this->handlePage($page, $subPage);
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
    private function handlePage($page, $subPage) {
        switch($page) {
            case 'top':
                $countries = ['netherlands', 'belgium', 'france', 'germany'];
                foreach($countries as $country) {
                    $this->data['top'][$country] = $this->Users->topUsers($country);
                }
                break;
            case 'add':
                // Check if the user is logged in.
                if(!$this->data['loggedIn']){
                    redirect('login');
                    exit;
                }
                // Check if the user is an admin.
                if($this->data['role'] !== 'admin') {
                    redirect();
                    exit;
                }
                switch($subPage) {
                    case null:
                        $subPage = 'netherlands';
                    case 'netherlands':
                        $this->data['country'] = 'Nederlands';
                        $this->data['resources'] = ['boerenkool', 'aardappelen', 'worst'];
                        break;
                    case 'germany':
                        $this->data['country'] = 'Duits';
                        $this->data['resources'] = ['Sauerkraut', 'Kartoffeln', 'Bradwurst'];
                        break;
                    case 'france':
                        $this->data['country'] = 'Frans';
                        $this->data['resources'] = ['rode ui', 'pommes de terres', 'jambon'];
                        break;
                    case 'belgium':
                        $this->data['country'] = 'Belgisch';
                        $this->data['resources'] = ['picallily', 'patat', 'stoofvlees'];
                        break;
                    default:
                        redirect('pageNotFound');
                        break;
                }
                $this->data['specialties'] = [
                    'mustard' => 'zaanse mosterd',
                    'curry' => 'curry',
                    'mayonaise' => 'zure mayo',
                    'escargots' => 'escargots',
                    'pickle' => 'augurken',
                    'union' => 'amsterdamse ui',
                    'fryed_union' => 'gefrituurde ui',
                    'rosti' => 'rösti',
                    'sprouts' => 'spruiten',
                    'chocolates' => 'bonbons',
                    'ketchup' => 'ketchup',
                    'camembert' => 'camembert',
                ];
                $this->data['usernames'] = $this->Users->getUsernames();
                $rules = [
                    [
                        'field' => 'username',
                        'label' => 'Gebruikersnaam',
                        'rules' => [
                            'required',
                            ['usernameExists', [$this->Users, 'userExists']],
                        ],
                        'errors' => [
                            'required' => 'Geef aan van welke groep het was.',
                            'usernameExists' => 'Selecteer een gebruiker.',
                        ],
                    ],
                    [
                        'field' => 'specialty',
                        'label' => 'Specialiteit',
                        'rules' => [
                            'required',
                        ],
                        'errors' => [
                            'required' => 'Geef de specialiteit aan.',
                        ],
                    ],
                ];
                for($i = 0; $i < 3; $i++) {
                    $name = $this->data['resources'][$i];
                    $rules[]= [
                        'field' => 'resource'.$i,
                        'label' => $name,
                        'rules' => [
                            'required',

                        ],
                        'errors' => [
                            'required' => 'Geef de hoeveelheid <i>'.$name.'</i> aan.',

                        ],
                    ];
                }
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run()) {
                    $this->data['score'] = $this->Receipt->insertReceipt(
                        set_value('username'),
                        set_value('resource0'),
                        set_value('resource1'),
                        set_value('resource2'),
                        set_value('specialty'),
                        $subPage
                    );
                    $this->data['success'] = true;
                } else {
                    $this->data['success'] = false;
                }
                break;
            case 'login':
                // Check if the user is already logged in.
                if($this->data['loggedIn']) {
                    redirect();
                    exit;
                }
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
