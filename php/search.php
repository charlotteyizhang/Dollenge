<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/22
 * Time: 13:15
 */
$mysqli = getConnect();
$return_arr = array();
$term = $_GET['term'];//retrieve the search term that autocomplete sends
$table = $_GET['table'];
$stmt = $mysqli->stmt_init();
$qstring = null;
if($table == "action"){
    $qstring = "SELECT action_name, action_id FROM actions WHERE action_name LIKE '%".$term."%'";
}else{
    $qstring = "SELECT funding_name, funding_id FROM fundings WHERE funding_name LIKE '%".$term."%'";
}

if ($stmt->prepare($qstring)) {
    $stmt->execute();
    $stmt->bind_result($value, $id);
    while ($stmt->fetch())//loop through the retrieved values
    {
        $row['value']=htmlentities(stripslashes($value));
        $row['id']=(int)$id;
        $row_set[] = $row;//build an array
    }
    /* free result set */
    $stmt->close();
}
$mysqli->close();
echo json_encode($row_set);//format the array into json data

?>