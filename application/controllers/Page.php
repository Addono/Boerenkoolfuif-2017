<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property    CI_Form_validation  $form_validation
 * @property    Users               $Users
 * @property    CI_Session          $session
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

        //echo calculateScore('france', 3, 4, 3, 'noClue');

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
                    case 'netherlands':
                        $this->data['country'] = 'Nederlands';
                        $this->data['resources'] = ['Boerenkool', 'Aardappelen', 'Worst'];
                        break;
                    case 'germany':
                        $this->data['country'] = 'Duits';
                        $this->data['resources'] = ['Sauerkraut', 'Kartoffeln', 'Bradwurst'];
                        break;
                    case 'france':
                        $this->data['country'] = 'Frans';
                        $this->data['resources'] = ['Rode ui', 'Pommes de Terres', 'Jambon'];
                        break;
                    case 'belgium':
                        $this->data['country'] = 'Belgisch';
                        $this->data['resources'] = ['Picallily', 'Patat', 'Stoofvlees'];
                        break;
                    default:
                        redirect('pageNotFound');
                        break;
                }
                $this->data['specialties'] = [
                    'mustard' => 'Zaanse mosterd',
                    'curry' => 'Curry',
                    'mayonaise' => 'Zure mayo',
                    'escargots' => 'Escargots',
                    'pickle' => 'Augurken',
                    'union' => 'Amsterdamse ui',
                    'fryed_union' => 'Gefrituurde ui',
                    'rosti' => 'Rösti',
                    'sprouts' => 'Spruiten',
                    'chocolates' => 'Bonbons',
                    'ketchup' => 'Ketchup',
                    'camembert' => 'Camembert',
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
                    $rules[] = [
                        [
                            'field' => 'resource'.$i,
                            'label' => $name,
                            'rules' => [
                                'required',
                                'less_than_equal_to[6]',
                                'greater_than[0]',
                            ],
                            'errors' => [
                                'required' => 'Geef de hoeveelheid '.$name.' aan.',
                                'greater_than[0]' => 'Ongeldige hoeveelheid '.$name.'.',
                                'less_than_equal_to[6]' => 'Ongeldige hoeveelheid '.$name.'.',
                            ],
                        ],
                    ];
                }
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run()) {
                    var_dump(1);
                } else {
                    var_dump(0);
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
