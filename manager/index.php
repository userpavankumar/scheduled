<?php

    use MongoDB\Model\BSONArray;
    use MongoDB\Operation\Find;

    // error_reporting(0);
    session_start();
    require '../vendor/autoload.php';
    if($_SESSION['email'] == '') {
        header('location: ../index.php');
    }
    $con = new MongoDB\Client( 'mongodb://localhost:27017' );
    $db = $con->php_mongo;
    $collection = $db->manager;
    $add_msg = '';
    $_GET['q'] = '';

    $record = $collection->findOne( [ 'email' =>$_SESSION['email']] );
    $datetime = iterator_to_array( $record['datetime'] );

    $date_arr = [];
    $time_arr = [];

    foreach($datetime as $date_key=>$val) {
        $date_arr[] = $date_key;
        foreach($val as $index=>$v) {
            $time_arr[$date_key][] = $v;
        }    
    }
    $k = count( $date_arr );

?>

<!doctype html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <link rel='shortcut icon' href='../image/favicon.ico' type='image/x-icon'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'
        integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css'>
    <link rel='stylesheet' href='../style.css'>
    <title>Manager | Dashboard</title>
</head>

<body>

    <!-- navbar -->
    <nav class='navbar navbar-expand-lg navbar-primary bg-primary '>
        <div class='container-fluid'>
            <a class='navbar-brand text-light' href='../index.php'>Scheduld Meetings</a>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse'
                data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false'
                aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse d-flex justify-content-center' id='navbarSupportedContent'>
            </div>
            <form action='../controller/php/logout.php' method='POST'>
                <button type='submit' name='logout' class='btn btn-danger text-light px-4 mx-5'>Log Out</button>
            </form>
        </div>
    </nav>

    <?php echo $add_msg;
?>

    <!-- slider -->
    <div class='actions container my-5 '>
        <div class='d-flex gap-2 justify-content-start'>
            <button class='btn btn-primary px-5 py-2' type='button' data-bs-toggle='modal'
                data-bs-target='#add_meeting'>Add Meeting schedueld</button>
            <!-- <button class = 'btn btn-primary px-5 py-2' type = 'button' data-bs-toggle = 'modal'
data-bs-target = '#edit_meeting'>Edit Meeting</button> -->
            <!-- <button class = 'btn btn-danger px-5 py-2' type = 'button'>Delete Meetings</button> -->
        </div>
    </div>
    <hr class=' mx-5 my-5 '>
    <div class='container d-flex justify-content-center'>
        <div class='slider-container'>
            <div class='slider-main d-flex justify-content-center'>
                <i class='bi bi-chevron-left text-primary text-center my-auto rounder-circle'></i>
                <div class='slider d-flex'>
                    <?php
                        
                        if($k == '') {
                            $next_date_index = 0;
                            echo '<h5 class="text-primary text-center mx-5">No meeting scheduelds</h5>';
                        }
                        else {
                            $next_date_index = $k++;
                            $d = date( 'Y-m-d' );
                            $nd = ++$d;
                            // foreach($date_arr as $key) {
                            // }
                            foreach ( $time_arr as $key=>$val ) {
                                $c_date = count($time_arr[$key]);
                                echo '<a class="nav-item-date px-5" href="#">';
                                if ( $key == date( 'Y-m-d' )  ) {
                                    echo '<h5 class="text-center text-nowrap">Today</h5>';
                                    echo '<small class="text-center text-nowrap">'. $c_date .' Slots Available</small>';
                                } else if ( $key == $nd ) {
                                    echo '<h5 class="text-center text-nowrap">Tomorrow</h5>';
                                    echo '<small class="text-center text-nowrap">'. $c_date    .' Slots Available</small>';
                                } else {
                                    $timestamp = strtotime( $key );
                                    $day = date( 'D, d M', $timestamp );
                                    echo '<h5 class="text-center text-nowrap">'. $day .'</h5>';
                                    echo '<small class="text-center text-nowrap">'. $c_date .' Slots Available</small>';
                                }
                                echo '</a>';
                            }
                        }
                    ?>
                                            <!-- <a class = 'px-5'>
                        <h5 class = 'text-center text-nowrap'>Wed, 03 Aug</h5>
                        <small class = 'text-center text-nowrap'>8 Slots Available</small>
                        </a> -->
                </div>
                <i class='bi bi-chevron-right text-primary text-center my-auto rounder-circle'></i>
            </div>
            <hr class='mb-0 text-primary'>
            <div class='detail-main d-flex justify-content-start'>
                <div class='detail'>
                    <?php
                        $t = date( 'H' );
                        $timezone = date( 'e' );
                        $w = count($time_arr); $count = 0;

                        function display($date){
                            echo "<div class='morning my-5 d-flex'>
                                    <i class='bi bi-brightness-alt-high text-secondary my-auto'></i>
                                    <p class='text-secondary my-auto px-3'>Morning</p>
                                    <div class='d-flex justify-content-start'>";
                            foreach ( $time_arr as $key ) {
                                    foreach ( $key as $k=>$v ) {
                                        if ( $key[$k] < '12:00' ) {
                                            echo "<button class='btn btn-sm btn-primary-outline border border-primary text-primary mx-3'>". $key[$k] ."</button>";
                                        }
                                    }
                                break;
                            }
                            echo "</div>
                                </div>";
                            
                                echo "<div class='afternoon my-5 d-flex'>
                                    <i class='bi bi-brightness-high text-secondary my-auto'></i>
                                    <p class='text-secondary my-auto px-3'>Afternoon</p>
                                    <div class='d-flex justify-content-start'>";
                            foreach ( $time_arr as $key ) {
                                    foreach ( $key as $k=>$v ) {
                                        if ( $key[$k] >= '12:00' && $key[$k] < '17:00' ) {
                                            echo "<button class='btn btn-sm btn-primary-outline border border-primary text-primary mx-3'>". $key[$k] ."</button>";
                                        }
                                    }
                                break;
                            }
                            echo "</div>
                                </div>";
    
                                echo "<div class='evening my-5 d-flex'>
                                <i class='bi bi-moon text-secondary my-auto'></i>
                                <p class='text-secondary my-auto px-3'>Evening</p>
                                <div class='d-flex justify-content-start'>";
                        foreach ( $time_arr as $key ) {
                                foreach ( $key as $k=>$v ) {
                                    if ( $key[$k] >= '17:00') {
                                        echo "<button class='btn btn-sm btn-primary-outline border border-primary text-primary mx-3'>". $key[$k] ."</button>";
                                    }
                                }
                            break;
                        }
                        echo "</div>
                            </div>";

                        }
                        

                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- add meeting -->
    <div class='modal fade' id='add_meeting' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel'
        aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered '>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>Add Meeting Details</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <form action='../controller/php/add_m.php' method='POST'>
                        <div class='my-3'>
                            <label for='' class='form-label'>Meeting Date</label>
                            <input type='date' name='meeting_date' class='form-control' id='meet_date'>
                        </div>
                        <div class='my-3'>
                            <label for='' class='form-label'>Choose Time for Meeting</label>
                            <input type='time' name='meeting_time' class='form-control' id='meet_time'>
                        </div>
                        <div class='modal-footer d-flex justify-content-between'>
                            <button type='submit' class=' btn btn-primary text-light px-3 ' name='add_m_meeting'>Add
                                Meeting</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
        integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'>
    </script>
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
    <script src='../controller/js/manager.js'></script>
    <script>
        $(document).on('change', '#meet_date', function() {
            console.log($(this).val());
        });
    </script>

</body>

</html>