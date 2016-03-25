<?php
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/5
 * Time: 23:19
 */

// calculate percentage
function calculate($total, $goal){
    $percentage = $total / $goal;
    $number = number_format($percentage * 100, 1);
    if($number>100){
        $number=100;
    }
    return $number;
}
// return interge nubmer
function returnFormat($number){
    return number_format($number,0);
}
//return time left
function getTime($start_time, $expire_time){
//    $current_date = date("Y-m-d", time());

    $expire_time = date(strtotime($expire_time));
//    $expire_date = date("Y-m-d", $expire_time);
    if(!$start_time){
        $diff = ceil(($expire_time - time())/60/60/24);
    }else{
        $start_time = date(strtotime($start_time));
        $diff = ceil(($expire_time - $start_time)/60/60/24);
    }
    return $diff;
}

function getTitle(){
    // dynamic page titles

    $page = basename($_SERVER['SCRIPT_FILENAME']);
    $page = str_replace('_',' ',$page);
    $page = str_replace('.php','',$page);
    $page = ucwords($page);
    return $page;
}

?>