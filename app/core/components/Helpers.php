    <?php
    class Helpers
    {   

    public static function handleResult($ReturnData)
    {
      $Data = [];
      if($ReturnData->num_rows > 0){
         while($row = $ReturnData->fetch_assoc()) {
            array_push($Data, $row);
          }
      }
      return  $Data;
        }


    }
    
    ?>