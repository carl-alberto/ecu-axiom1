<?php
namespace OUR\DIRECTORY\ITCS;

//search AD for the posted first and/or last name that was submitted via ajax
function directory_search_people() {

    $data['user_found'] = false;
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    // if fields are blank return false
    if (!$firstName && !$lastName) {
        $data['message'] = 'Please specify either first or last name';
        wp_send_json($data);
        die();
    // else prepare to search
    }    else   {
        $search = new \Ldap\Ad_Search();
    }

    
    if ($firstName && $lastName) {
        $users = $search->show_itcs()->set_limit(50)->find_by_full_name($firstName, $lastName);
    } elseif ($firstName) {
        $users = $search->show_itcs()->set_limit(50)->find_by_first_name( $firstName);
        } else   {
        $users = $search->show_itcs()->set_limit(50)->find_by_last_name( $lastName);
    }

    foreach ($users as $user)   {
        if ( $user->get_user()->getCompany() == '229 ITCS Cotanche Bldg') {
            $cotanche_users[] = $user;
        }
    }
    
    if ($cotanche_users) {
        $data['count'] = count($cotanche_users);
        if (count($cotanche_users) == 50) {
            $data['message'] = 'Not all matches are shown. If you do not see the person you are searching for try changing the filter to either students or faculty/staff only and/or provide a first and last name.';
        }
        
        $cotanche_users =  directory_format_people($cotanche_users);
        $data['user_found'] = true;
        $data['users'] = $cotanche_users;
    }  else   {
        $data['message'] = 'No People found.';
    }
    wp_send_json($data);
    die();
}

add_action( 'wp_ajax_nopriv_directory_search_people', __NAMESPACE__ . '\directory_search_people', 10, 0 );
add_action( 'wp_ajax_directory_search_people', __NAMESPACE__ . '\directory_search_people', 10, 0 );


//formatting html for return values if people are to be returned
function  directory_format_people($data) {
    
    $header = 'Search Results';
    
    ob_start(); ?>
    <h3><?php echo $header; ?></h3>
    <hr />
    <div id="accordion1">
    <?php
    $count=1;
        foreach ($data as $user) {
            if ($user->get_user()) {?>
                 <div class="card">
                    <div class="card-header" id="heading<?php echo $count; ?>">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block" data-toggle="collapse" data-target="#collapse<?php echo $count; ?>" aria-expanded="true" aria-controls="collapse<?php echo $count; ?>">
                                <h4 class="text-center"><?php echo $user->get_user()->getLastName() . ', ' . $user->get_user()->getFirstName();?></h4>
                            </button>
                        </h5>
                    </div>
                    <div id="collapse<?php echo $count; ?>" class="collapse" aria-labelledby="heading<?php echo $count; ?>" data-parent="#accordion1">
                        <div class="card-body">
                            <table class="table">                              
                                <tr>
                                    <td><a href="sip:<?php echo $user->get_user()->getEmail(); ?>" class="btn btn-primary btn-block btn-lg"><i class="fa fa-video-camera"></i>&nbsp;&nbsp;Call with WebEx</a></td>
                                    <?php if ($user->get_user()->getTelephoneNumber()): 
                                        $number = format_phone_number($user->get_user()->getTelephoneNumber());
                                    ?>
                                    <td><a href="sip:<?php echo $number; ?>@ecu.edu" class="btn btn-primary btn-block btn-lg"><i class="fa fa-phone"></i>&nbsp;&nbsp;Call with phone</a></td>
                                </tr>
                                <?php endif ?>                                
                            </table>
                        </div>
                    </div>
                </div>
<?php  $count++;
        }
    }

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

//convert a mail stop into a building code in order to display the correct url
function get_map_code($mailstop)    {
    $stop = \Database\Tools::query("SELECT building_id FROM university_buildings_mailstops WHERE mailstop = ?",array($mailstop));
    if ($stop) {
        $code = \Database\Tools::query("SELECT code FROM university_buildings WHERE id = ?", array($stop[0]->building_id));
        if ($code) {
            return $code[0]->code;;
        }
    }
    return false;
}

//strip all other characters except numbers and letters out of a phone number, and ensure area code is on it
function format_phone_number($number)   {
    $number = preg_replace('/[^a-zA-Z0-9]/s','',$number);
    if (strlen($number) == 7) {
        $number = '252' . $number;
    }
    return $number;
}
