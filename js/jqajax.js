$(document).ready(function() {

    $("#display_button").click(function(e) {
        e.preventDefault();
        console.log("View button clicked");
		Papa.parse(document.getElementById('fileUpload').files[0], {
            download: true,
            header: true,
            skipEmptyLines: true,
            complete: function(results) {
                // console.log(results.data);
                mydata = JSON.stringify(results.data);
                // console.log(mydata[0].Name);
				data = results.data;
				// console.log(data);

				// storing data in array
				studentDetail = [];
				for (let i = 0; i < data.length; i++) {
					row = {};
					row["Registration"] = data[i]["Registration No."];
					row["Name"] = data[i]["Name"];
					row["Primary Contact No"] = data[i]["Primary Contact No"];
					row["College MS Team EMail_Id"] = data[i]["College MS Team EMail_Id"];
					row["Course"] = data[i]["Course"];
					row["Branch"] = data[i]["Branch"];
					row["Placed / Unplaced"] = data[i]["Placed / Unplaced"];
					studentDetail.push(row);
				}

				// console.log(studentDetail);

				$('#gridContainer-student-detail').dxDataGrid({
					dataSource: studentDetail,
					showBorders: true,
					paging: {
					enabled: false,
					},
					editing: {
						mode: 'batch',
						allowUpdating: true,
						allowAdding: true,
						allowDeleting: true,
						selectTextOnEditStart: true,
						startEditAction: 'dblClick',
					},
					columns: [
						{
							dataField: 'Registration',
							caption: 'Registration No.',
							width: 70,
						},
						'Name', 
						{
							dataField: 'Primary Contact No',
							caption: 'Contact no',
						},
						{
							dataField: 'College MS Team EMail_Id',
							caption: 'Email',
						},
						'Course', 
						'Branch', 
						{
							dataField: 'Placed / Unplaced',
							caption: 'Placement',
						},
					],
					onSaved(e){
						console.log(e);
						console.log(studentDetail);
					}
				}).dxDataGrid('instance');

				placementDetail = [];
				let len = data.length;
				for (let i = 0; i < len; i++) {
					var e = data[i]["Organization / Type / CTC"].split(",");
					e.forEach(element => {
						row = {};
						row["Registration"] = data[i]["Registration No."];
						elem = element.split("-");
						row["Organization"] = elem[0];
						row["Type"] = elem[1];
						row["CTC"] = elem[2];
						placementDetail.push(row);
					});
				}
				// console.log(placementDetail);

				$('#gridContainer-placement-detail').dxDataGrid({
					dataSource: placementDetail,
					showBorders: true,
					paging: {
					enabled: false,
					},
					editing: {
						mode: 'batch',
						allowUpdating: true,
						allowAdding: true,
						allowDeleting: true,
						selectTextOnEditStart: true,
						startEditAction: 'dblClick',
					},
					columns: [
						'Registration', 
						'Organization', 
						'Type', 
						'CTC'
					],
					onSaved(e){
						console.log(e);
						// console.log(placementDetail);
					}
				}).dxDataGrid('instance');

				allStudentData = {
					"studentDetail": studentDetail, 
					"placementDetail": placementDetail
				};
			}
			
		})
	})

    $("#insert_button").click(function(e) {
        // console.log("insert btn clicked");
		// console.log(allStudentData);
		mydata = JSON.stringify(allStudentData);
		// console.log(mydata);
        e.preventDefault();
		$.ajax({
            url: "php/insert.php",
            method: "POST",
            data: mydata,
            success: function(data) {
                console.log(data);
                msg = "<div class='alert alert-warning alert-dismissible fade show mt-3' role='alert' >" + data + "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                $("#msg").html(msg);
                $("#myform")[0].reset();
                $('#gridContainer-student-detail').empty();
                $('#gridContainer-placement-detail').empty();
            },
        });
    });
});