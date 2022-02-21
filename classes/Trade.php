
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - TRADE
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Trade{

    function __construct() {
    }

    function get_lastTrade(){
        $db = new Db();

        $sql = $db -> query("SELECT * FROM trades ORDER BY id DESC LIMIT 1");

        if($sql === false) {
            return false;
        }

        $holding = false;

        while ($row = $sql->fetch_assoc()) {
            if($row['buy_date'] > 0 && $row['sell_date'] == 0){
                $holding = true;
            }