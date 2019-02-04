<?php require "adminheader.php"; ?>
<?php

    $newELink = new DBLink();
    $eventTable = "SELECT * FROM MILBER.MilberEvents WHERE admin1_confirm_SQL = 'W' OR admin2_confirm_SQL = 'W'";
    $newEResult = $newELink->query($eventTable);

    if(isset($_GET['a1'])){
      $choise = htmlentities(trim($_GET['a1']));
      if($choise == 'yes'){
        $eventID = $_GET['e'];
        $newE = new DBLink();
        $eventT = "UPDATE MILBER.MilberEvents SET admin1_confirm_SQL = 'Y' WHERE event_id_SQL = '$eventID'";
        $newEResult = $newE->query($eventT);
        header("Location: weDoThisThingRight.php");
      }
      if($choise == 'no'){
        $eventID = $_GET['e'];
        $newE = new DBLink();
        $eventT = "UPDATE MILBER.MilberEvents SET admin1_confirm_SQL = 'N' WHERE event_id_SQL = '$eventID'";
        $newEResult = $newE->query($eventT);
        header("Location: weDoThisThingRight.php");
      }
    }
    if(isset($_GET['a2'])){
      $choise = htmlentities(trim($_GET['a2']));
      if($choise == 'yes'){
        $eventID = $_GET['e'];
        $newE = new DBLink();
        $eventT = "UPDATE MILBER.MilberEvents SET admin2_confirm_SQL = 'Y' WHERE event_id_SQL = '$eventID'";
        $newEResult = $newE->query($eventT);
        header("Location: weDoThisThingRight.php");
      }
      if($choise == 'no'){
        $eventID = $_GET['e'];
        $newE = new DBLink();
        $eventT = "UPDATE MILBER.MilberEvents SET admin2_confirm_SQL = 'N' WHERE event_id_SQL = '$eventID'";
        $newEResult = $newE->query($eventT);
        header("Location: weDoThisThingRight.php");
      }
    }


?>

        	<div>
        		<br />
        		<p>New events to be confirmed</p>
            <br />
            For Admin: W = waiting, Y = Event Was Confirmed, N = Event Was Declined
            <br />
            <table class="confirmE">
              <tr>
                <th>MakerID</th>
                <th>Maker Name</th>
                <th>Event Name</th>
                <th>Event Pic</th>
                <th>Privat Public</th>
                <th>Type</th>
                <th>Location</th>
                <th>X</th>
                <th>Y</th>
                <th>Description</th>
                <th>Prive</th>
                <th>Ticket#</th>
                <th>Home Phone</th>
                <th>Cell Phone</th>
                <th>Work Phone</th>
                <th>Starts On</th>
                <th>Ends On</th>
                <th>Admin 1 Confirm</th>
                <th>Admin 2 Confirm</th>
              </tr>
<?php
            while($ne = mysqli_fetch_assoc($newEResult)){
              $eId = $ne['event_id_SQL'];
?>
              <tr>
                <td><?= $ne['event_Posted_by_id_SQL'];?></td>
                <td><?= $ne['who_is_making_event_SQL'];?></td>
                <td><?= $ne['event_Name_SQL'];?></td>
                <td><img scr="<?= $ne['event_pic_SQL'];?>" alt="eimg"/></td>
                <td><?= $ne['event_Private_Public_SQL'];?></td>
                <td><?= $ne['event_Type_SQL'];?></td>
                <td><?= $ne['event_address_SQL'];?></td>
                <td><?= $ne['event_x_num_SQL'];?></td>
                <td><?= $ne['event_y_num_SQL'];?></td>
                <td><?= $ne['event_description_SQL'];?></td>
                <td><?= $ne['event_price_SQL'];?></td>
                <td><?= $ne['event_tickets_SQL'];?></td>
                <td><?= $ne['event_home_phone_SQL'];?></td>
                <td><?= $ne['event_cell_phone_SQL'];?></td>
                <td><?= $ne['event_work_phone_SQL'];?></td>
                <td><?php echo $ne['event_Start_Day_on_SQL']." ".$ne['event_Start_Month_on_SQL']." ".$ne['event_Start_Year_on_SQL'];?></td>
                <td><?php echo $ne['event_End_Day_on_SQL']." ".$ne['event_End_Month_on_SQL']." ".$ne['event_End_Year_on_SQL'];?></td>
                <td><?php echo $ne['admin1_confirm_SQL'];  if($adminEmail == "WellEmailCanBeAnythingAndIamNotAComputerggggg@xEvent.yyy"){?><br /><select onchange="window.location.href=this.value;"><option value="choise">choise</option><option value="weDoThisThingRight.php?a1=no&e=<?=$eId;?>">No</option><option value="weDoThisThingRight.php?a1=yes&e=<?=$eId;?>">Yes</option></select><?php } ?></td>
                <td><?php echo $ne['admin2_confirm_SQL'];  if($adminEmail == "willBeAveryLoooonEmailAddressThatYouGoHere@xEvent.com"){?><br /><select onchange="window.location.href=this.value;"><option value="choise">choise</option><option value="weDoThisThingRight.php?a2=no&e=<?=$eId;?>">No</option><option value="weDoThisThingRight.php?a2=yes&e=<?=$eId;?>">Yes</option></select><?php } ?></td>



              </tr>


<?php
            }         
?>



            </table>




        	</div>
  </body>
</html>