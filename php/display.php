<?php
// Select Data With PDO
    include('dbConnect.php');
    $conn = new dbConnect();
    $connection = $conn->start_connection();

    $data = array();

    $studentDetail = $connection->prepare("SELECT * FROM Student_Details");
    $studentDetail->execute();
// $students = $studentDetail->fetchAll();
    // echo count($students);

    // return no. of companies
    $query  = $connection->prepare("SELECT COUNT(DISTINCT Organization_Name) FROM Student_Placement_Details;");
    $query->execute();
    $companies = $query->fetchAll();
    // echo $companies[0][0];
    $data['CompanyCount'] = strval($companies[0][0]) ;

    // return no. of student placed
    $query  = $connection->prepare("SELECT COUNT(Registration_Number) FROM Student_Placement_Details;");
    $query->execute();
    $studentPlaced = $query->fetchAll();
    // echo $studentPlaced[0][0];
    $data['StudentsPlaced'] = strval($studentPlaced[0][0]);

    // return avg placement LPA
    $query  = $connection->prepare("SELECT AVG(CTC) FROM Student_Placement_Details;");
    $query->execute();
    $avgSalary = $query->fetchAll();
    $data['AveragePlacement'] =  strval($avgSalary[0][0]);

    $jsonArray = array();
    array_push($jsonArray, $data);
    

    // return table company name and their occurence (2 col)
    $companyWiseOfferArray = array();
    $companyWiseOffer = $connection->prepare("SELECT Organization_Name, COUNT(*) 'value' FROM Student_Placement_Details GROUP BY Organization_Name;");
    $companyWiseOffer->execute();
    $companyWiseOfferData = $companyWiseOffer->fetchAll();
    // echo $companyWiseOfferData[0]['Organization_Name'];
    foreach ($companyWiseOfferData as $item) {
        $companyWiseOfferItem = array();
        $companyWiseOfferItem['Organization_Name'] = $item['Organization_Name'];
        $companyWiseOfferItem['value'] = $item['value'];
        array_push($companyWiseOfferArray, $companyWiseOfferItem);
    }
    array_push($jsonArray, $companyWiseOfferArray);


    // return table ctc => 3 row and their count (2 col)
    $offerToSalaryArray = array();
    $offerToSalary = $connection->prepare(
"SELECT CASE CTC WHEN '3LPA' THEN '<=3LPA' WHEN '3.25LPA' THEN '3-4LPA' WHEN '3.7LPA' THEN '3-4LPA' WHEN '3 to 4LPA' THEN '3-4LPA' ELSE '>4LPA' end 'salary', COUNT(*) 'value' FROM Student_Placement_Details GROUP BY salary;");
    $offerToSalary->execute();
    $offerToSalaryDatas = $offerToSalary->fetchAll();
    // echo $offerToSalaryDatas[0]['value'];
    foreach($offerToSalaryDatas as $item) {
        $offerToSalaryItem = array();
        $offerToSalaryItem['salary'] = $item['salary'];
        $offerToSalaryItem['value'] = $item['value'];
        array_push($offerToSalaryArray, $offerToSalaryItem);       
    }
    array_push($jsonArray, $offerToSalaryArray);


    echo json_encode($jsonArray);


?>