<?php

    include('dbConnect.php');
    $conn = new dbConnect();
    $connection = $conn->start_connection();
    //used to cleanup the data retrieved
    $data = stripslashes(file_get_contents("php://input"));
    $mydata = json_decode($data, true); //convert into php object/array
    // echo $data;
    if($data){
        // echo count($mydata);
        $studentDetail = $mydata['studentDetail'];
        $placementDetail = $mydata['placementDetail'];
        // echo count($placementDetail);
        $firstTable = "INSERT IGNORE INTO Student_Details(Registration_Number, Name, Primary_Contact_Number, College_MS_Team_Email_Id, Course, Branch, Placed_Or_Unplaced) VALUES ";
        $secondTable = "INSERT IGNORE INTO Student_Placement_Details(Registration_Number, Organization_Name, Type, CTC) VALUES ";
     
        for($i=0; $i<count($studentDetail); $i++){
            $reg = trim($studentDetail[$i]['Registration']);
            $name = trim($studentDetail[$i]['Name']);
            $contact = trim($studentDetail[$i]['Primary Contact No']);
            $email = trim($studentDetail[$i]['College MS Team EMail_Id']);
            $course = trim($studentDetail[$i]['Course']);
            $branch = trim($studentDetail[$i]['Branch']);
            $placed = trim($studentDetail[$i]['Placed / Unplaced']);
            $firstTable .= "('$reg','$name','$contact','$email','$course','$branch','$placed'),";
        }
        for ($i = 0; $i < count($placementDetail); $i++){
            $reg = trim($placementDetail[$i]['Registration']);
            $org = trim($placementDetail[$i]['Organization']);
            $type = trim($placementDetail[$i]['Type']);
            $ctc = trim($placementDetail[$i]['CTC']);
            $secondTable .= "('$reg','$org','$type','$ctc'),";
        }
        //removing the extra comma at last
        $firstTable = rtrim($firstTable, ",");
        $secondTable = rtrim($secondTable, ",");
        //adding semicolon to end
        $firstTable .= ";";
        $secondTable .= ";";
        // echo $firstTable;
        // echo $secondTable;
        $sql = $connection->prepare($firstTable);
        $sql->execute();
        $query = $connection->prepare($secondTable);
        $query->execute();
        echo "CSV File has been successfully Imported.";
    }
    else{
        echo "error in transfering data";
    }
?>