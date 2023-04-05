<?php
namespace OUR\DIRECTORY;

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

    switch ($_POST['filter']) {
        case 'both':
            $data['filter'] = 'both';
            if ($firstName && $lastName) {
                $users = $search->show_staff()->show_students()->set_limit(50)->find_by_full_name($firstName, $lastName);
            } elseif ($firstName) {
                $users = $search->show_staff()->show_students()->set_limit(50)->find_by_first_name( $firstName);
            } else   {
                $users = $search->show_staff()->show_students()->set_limit(50)->find_by_last_name( $lastName);
            }
            break;
        case 'students':
            $data['filter'] = 'student';
            if ($firstName && $lastName) {
                $users = $search->show_students()->set_limit(50)->find_by_full_name($firstName, $lastName);
            } elseif ($firstName) {
                $users = $search->show_students()->set_limit(50)->find_by_first_name( $firstName);
            } else   {
                $users = $search->show_students()->set_limit(50)->find_by_last_name( $lastName);
            }
            break;

        case 'faculty':
            $data['filter'] = 'faculty';
            if ($firstName && $lastName) {
                $users = $search->show_staff()->set_limit(50)->find_by_full_name($firstName, $lastName);
            } elseif ($firstName) {
                $users = $search->show_staff()->set_limit(50)->find_by_first_name( $firstName);
            } else   {
                $users = $search->show_staff()->set_limit(50)->find_by_last_name( $lastName);
            }
            break;
    }

    if ($users) {
        $data['count'] = count($users);
        if (count($users) == 50) {
            $data['message'] = 'Not all matches are shown. If you do not see the person you are searching for try changing the filter to either students or faculty/staff only and/or provide a first and last name.';
        }
        $users =  directory_format_people($users, $_POST['filter']);
        $data['user_found'] = true;
        $data['users'] = $users;
    }  else   {
        $data['message'] = 'No People found.';
    }
    wp_send_json($data);
    die();
}

add_action( 'wp_ajax_nopriv_directory_search_people', __NAMESPACE__ . '\directory_search_people', 10, 0 );
add_action( 'wp_ajax_directory_search_people', __NAMESPACE__ . '\directory_search_people', 10, 0 );

//search AD for the pirate_id if on was provided with ?person=PIRATEID in the URL
function directory_search_pirate_id() {
    $data['user_found'] = false;
    $user = new \Ldap\Ad_User($_POST['pirate_id']);
    if ($user && !$user->is_hidden()) {
        $data['user_found'] = true;
        $data['first_name'] = $user->get_user()->getFirstName();
        $data['last_name'] = $user->get_user()->getLastName();
    }   else    {
        $data['message'] = 'No user with that pirate id was found!';
    }
    wp_send_json($data);
    die();
}


add_action( 'wp_ajax_nopriv_directory_search_pirate_id', __NAMESPACE__ . '\directory_search_pirate_id', 10, 0 );
add_action( 'wp_ajax_directory_search_pirate_id', __NAMESPACE__ . '\directory_search_pirate_id', 10, 0 );

//search database for department title or subtitle that was provided via ajax
function directory_search_department() {
    $dept = trim($_POST['dept']);
    $data['dept_found'] = false;
    if (!$dept ) {
        $data['message'] = 'Please specify Department Title';
        wp_send_json($data);
        die();
        // else prepare to search
    }   else   {
        $dept = '%' . $dept . '%';
        $data['dept_found'] = true;
        $depts = \Database\Directory::query("SELECT * FROM phone WHERE title LIKE ? OR subtitle LIKE ?", array($dept, $dept));
    }

    if ($depts) {
        $data['depts'] =  directory_format_departments($depts);
    }   else   {
        $data['message'] = 'No Departments found.';
    }

    wp_send_json($data);
    die();
}
add_action( 'wp_ajax_nopriv_directory_search_department', __NAMESPACE__ . '\directory_search_department', 10, 0 );
add_action( 'wp_ajax_directory_search_department', __NAMESPACE__ . '\directory_search_department', 10, 0 );


//browse departments based on which letter was clicked in the UI
function directory_browse_depts() {
    $letter = sanitize_text_field($_POST['letter']) . '%';
    $depts = \Database\Directory::query("SELECT * FROM phone WHERE title LIKE ?", array($letter));
    $data['depts'] =  directory_format_departments($depts);
    wp_send_json($data);
    die();
}
add_action( 'wp_ajax_nopriv_directory_browse_depts', __NAMESPACE__ . '\directory_browse_depts', 10, 0 );
add_action( 'wp_ajax_directory_browse_depts', __NAMESPACE__ . '\directory_browse_depts', 10, 0 );


//Serch database for phone number that was provided in the reverse search form
function directory_reverse_search() {
    $data['dept_found'] = false;
    $phone_number = trim($_POST['phoneNumber']);
    // if fields are blank return false
    if (!$phone_number) {
        $data['message'] = 'Please specify phone number';
        wp_send_json($data);
        die();
    }

    //search users
    $search = new \Ldap\Ad_Search();
    $users = $search->show_staff()->set_limit(50)->find_by_phone($phone_number);
    if ($users) {
        $data['users'] =  directory_format_people($users, 'faculty');
    }

    //search depts
    if (preg_match('/([0-9]{3})?[- .]?([0-9]{4})$/', $phone_number, $matches)) {
        $number = '%' . $matches[1] . '-' . $matches[2] . '%';
        $depts = \Database\Directory::query("SELECT * FROM phone WHERE phonenumber LIKE ? OR fax LIKE ?", array($number,$number));
        if ($depts) {
            $data['depts'] = directory_format_departments($depts);
        }
    }

    if (!$users && !$depts) {
        $data['message'] = 'No people or department listings were found that match your search request.';
    }

    wp_send_json($data);
    die();
}
add_action( 'wp_ajax_nopriv_directory_reverse_search', __NAMESPACE__ . '\directory_reverse_search', 10, 0 );
add_action( 'wp_ajax_directory_reverse_search', __NAMESPACE__ . '\directory_reverse_search', 10, 0 );

//formatting html for return values if people are to be returned
function  directory_format_people($data, $filter) {
    switch ($filter) {
        case 'both':
            $header = 'Faculty, Staff & Students';
            break;
        case 'faculty':
            $header = 'Faculty & Staff';
            break;
        case 'students':
            $header = 'Students';
            break;
        default:

            break;
    }
    ob_start(); ?>
    <h2><?php echo $header; ?></h2>
    <hr />
    <div id="accordion1">
    <?php
    $count=1;
        foreach ($data as $user) {
            if ($user->get_user()) {?>
                 <div class="card">
                    <div class="card-header" id="heading<?php echo $count; ?>">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $count; ?>" aria-expanded="true" aria-controls="collapse<?php echo $count; ?>">
                                <?php echo $user->get_user()->getLastName() . ', ' . $user->get_user()->getFirstName();
                                if ($filter == 'both') {
                                    if ($user->is_student() == 'Student') {
                                        echo ' (Student)';
                                    } else  {
                                        echo ' (Employee)';
                                    }
                                }
                            ?>
                            </button>
                        </h5>
                    </div>
                    <div id="collapse<?php echo $count; ?>" class="collapse" aria-labelledby="heading<?php echo $count; ?>" data-parent="#accordion1">
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td><strong>Name:<strong></td><td><?php echo $user->get_user()->getLastName() . ', ' . $user->get_user()->getFirstName(); ?> </td>
                                </tr>
                                <tr>
                                    <td><strong>Title:<strong></td><td><?php echo $user->get_user()->getTitle(); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>E-mail:</strong></td><td><a href="mailto:<?php echo $user->get_user()->getEmail(); ?>"><?php echo strtolower($user->get_user()->getEmail()); ?></a></td>
                                </tr>
                                <?php if ($user->get_user()->getTelephoneNumber()): ?>
                                    <tr>
                                        <td><strong>Phone:</strong></td><td><?php echo format_phone_number($user->get_user()->getTelephoneNumber()); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($user->get_user()->getCompany()): ?>
                                    <tr>
                                        <td><strong>Mail Stop:</strong></td><td><?php echo $user->get_user()->getCompany(); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($user->get_user()->getPhysicalDeliveryOfficeName() && $user->is_employee()): ?>
                                    <tr>
                                        <td><strong>Office:</strong></td><td><?php echo $user->get_user()->getPhysicalDeliveryOfficeName(); ?></td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($user->get_user()->getDepartment()): ?>
                                    <tr>
                                        <td><strong>Department:</strong></td><td><?php echo $user->get_user()->getDepartment(); ?></td>
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

//formatting html for return values for department data
function  directory_format_departments($data) {

 ob_start(); ?>
    <h2>Departments</h2>
    <hr />
    <div id="accordion2">
        <?php foreach ($data as $dept) {
            $titles[trim($dept->title)][] = $dept;
        }
        $count = 1;
        foreach ($titles as $title => $dept) { ?>
            <div class="card">
                <div class="card-header" id="heading<?php echo $count; ?>dept">
                    <h5 class="mb-0">
                        <button class="btn btn-link accordion-nav" data-toggle="collapse" data-target="#collapse<?php echo $count; ?>dept" aria-expanded="true" aria-controls="collapse<?php echo $count; ?>">
                            <?php echo $title; ?>
                        </button>
                    </h5>
                </div>
                <div id="collapse<?php echo $count; ?>dept" class="collapse" aria-labelledby="heading<?php echo $count; ?>dept" data-parent="#accordion2">
                    <div class="card-body">
                        <?php foreach ($dept as $d): ?>
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th colspan="2"><span><?php echo $d->subtitle; ?></span></td>
                                </tr>
                            <?php if ($d->phonenumber): ?>
                                <tr>
                                    <td><strong>Phone:</strong></td><td><?php echo $d->phonenumber; ?></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($d->fax): ?>
                                <tr>
                                    <td><strong>Fax:</strong></td><td><?php echo $d->fax; ?></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($d->mailstop):?>
                                <tr>
                                    <td><strong>Mail Stop:</strong></td><td><?php echo $d->mailstop; ?></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($d->mailstop): ?>
                                <tr>
                                    <td><strong>Map:</strong></td><td><a href="//www.ecu.edu/buildings/<?php echo get_map_code($d->mailstop); ?>" target="_blank"><i aria-hidden="true" aria-label="open map in new window" class="fa fa-map-marker fa-4"></span></a></td>
                                </tr>
                            <?php endif ?>
                        </table>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php  $count++;} ?>
    </div>
<?php
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

//strip all other characters except numbers and letters out of a phone number, ensure area code is on it, and insert hyphens at appropriate places
function format_phone_number($number)   {
    $number = preg_replace('/[^a-zA-Z0-9]/s','',$number);
    if (strlen($number) == 7) {
        $number = '252' . $number;
    }
    return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
}
