<?php
//Start the session to store information
session_start();
?>

<!DOCTYPE html>

<!The webpage is in english language
 Use UTF-8 which covers almost all of the characters and symbols in the world
 Enable the user's visible area depends on their device, small for smartphone and large for computers
 Allows web authors to choose what version of Internet Explorer and ie=edge display the content in the highest mode available
 Rel Stylesheet specifies the relationship between current document with imported style sheet from href. Href specifies the link's destination>

<html lang = "en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Electricity Bill Calculator</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<link rel="stylesheet" href="css/style.css">
	</head>

<body>
		<!To create the header of the webpage>
		<!Id=wrapper is used to create the white large box to insert the content>
		<div id="wrapper">
		<div id="header">
			<h1>Electricity Bill Calculator</h1>
			<h2>Calculate your electricity bill here!</h2>
		</div>
	
	<br><br>
	
	<!To create table tariff block (kWh)>
	<div id="page-wrap">
	<table class="table table-striped">

	<!To create table column>
		<thead>
			<tr>
				<th scope="col">Block</th>
				<th scope="col">Tariff Block (kWh)</th>
				<th scope="col">Rate (RM)</th>
			</tr>
		</thead>
	
	<!To create table row>
		<tbody>
			<tr>
				<th scope="row">1</th> <!Details for row 1>
				<td>200</td>
				<td>0.218</td>
			</tr>
			<tr>
				<th scope="row">2</th> <!Details for row 2>
				<td>100</td>
				<td>0.334</td>
			</tr>
			<tr>
				<th scope="row">3</th> <!Details for row 3>
				<td>Remaining kWh used</td>
				<td>0.516</td>
			</tr>
			
		</tbody>
	</table>

		<!To create the form>
			<form class="post-form" action="" method="post"> 
					<div class="form-group">
					<label> Current (kWh) month meter reading: </label>
					<input type="number" name="unitkWh_current" placeholder="Consumption (kWh)" step="0.01" min="0"/>
			<br><br>
			
					<label> Previous (kWh) month meter reading: </label>
					<input type="number" name="unitkWh_previous" placeholder="Consumption (kWh)" step="0.01" min="0"/>
			
				<p style="text-align:center; margin-top: 50px; color:blue;">Note: <a style="text-decoration:none; color:black;">The kilowatt-hours (kWh) used for the current month is obtain by deducting the previous (kWh) month meter reading with the current (kWh) month meter reading.</a></p>
			
			</div>
		
			<br><br>
				<div>
					<input class="submit" type="submit" name="calculate" value="Calculate" />
					<input class="submit" type="submit" name="reset" value="Reset" />
				</div>
				
				
		</form>
	</div>
		<br><br>
	
	<! To create the function of the tnb calculation using php>
	<div id="main-content">
		<div style="width: 100%; height: 10px; border-bottom: 1px solid black; text-align: center">
			<span style="font-size: 18px; background-color: #F8E4DF; padding: 0 5px;">
				<b>Billing Summary</b>
			</span>
		</div>
		
		<br><br>
		
	<?php
		if (isset($_POST['reset'])) {
			//Destroys all the data associated with the current session
            session_destroy();
            header('location:index.php');
                }		
				
		if (isset($_POST['calculate'])) {
			session_destroy();
			$unitkWh_current = $_POST['unitkWh_current'];
			$unitkWh_previous = $_POST['unitkWh_previous'];
			
		if (empty($unitkWh_current)) {
			echo "<font color='red'> *Current (kWh) month meter reading field is required!";
			echo "<br><br>";
			}
		
		if (empty($unitkWh_previous)) {
			echo "<font color='red'> *Previous (kWh) month meter reading field is required!";
			}
			
		else { //If both form is empty it will generate the result
			$unitkWh_used = $unitkWh_current - $unitkWh_previous;	
			$result = unitkWh_used($unitkWh_used);
		}
	}
		
		function unitkWh_used($unitkWh_used) {
			$unit_cost_first = 0.218;
			$unit_cost_second = 0.334;
			$unit_cost_remaining = 0.516;
					
		//To calculate consumption unitkWh that is less and equal than 200 
		if ($unitkWh_used <= 200) {
			$bill = $unitkWh_used * $unit_cost_first;
			$billformat = number_format ($bill, 2);
			echo "Your total consumption used is " . $unitkWh_used . " kWh";
			echo "<br><br>";
			echo "First 200kWh (1-200 kWh) per month: RM " . $billformat;
			echo "<br><br>";
			echo "Your current bill excluded ICPT and service tax per month: RM " . $billformat;
		//number format is used to set that the output are in the 2 decimal point
		}
		
		//To calculate consumption unitkWh that is greater than 200 but less than 300 because of the next 100kWh
		else if($unitkWh_used > 200 && $unitkWh_used <= 300) {
			$temp = 200 * $unit_cost_first;
			$tempformat = number_format ($temp, 2);
			$remaining_units = $unitkWh_used - 200;
			$b = ($remaining_units * $unit_cost_second);
			$bformat = number_format ($b, 2);
			$bill = $temp + ($remaining_units * $unit_cost_second);
			$billformat = number_format ($bill, 2);
			echo "Your total consumption used is " . $unitkWh_used . " kWh";
			echo "<br><br>";
			echo "First 200kWh (1-200 kWh) per month: RM " . $tempformat;
			echo "<br> <br>";
			echo "Next 100 kWh (201 - 300 kWh) per month: RM " . $bformat;
			echo "<br><br>";
			echo "Your current bill excluded ICPT and service tax per month: RM " . $billformat;
		}
		
		//To calculate consumption unitkWh that is greater than 300 because of the balance remaining unitkWh used
		else if ($unitkWh_used > 300){
			$a = (200 * $unit_cost_first);
			$aformat = number_format ($a, 2);
			$b = (100 * $unit_cost_second);
			$bformat = number_format ($b, 2);
			$temp = (200 * $unit_cost_first) + (100 * $unit_cost_second);
			$remaining_units = $unitkWh_used - 300;
			$c = ($remaining_units * $unit_cost_remaining);
			$cformat = number_format ($c, 2);
			$bill = $temp + ($remaining_units * $unit_cost_remaining);
			$billformat = number_format ($bill, 2);
			echo "Your total consumption used is " . $unitkWh_used . " kWh";
			echo "<br><br>";
			echo "First 200kWh (1-200 kWh) per month: RM " . $aformat;
			echo "<br> <br>";
			echo "Next 100 kWh (201 - 300 kWh) per month: RM " . $bformat;
			echo "<br> <br>";
			echo "Next remaining kWh onwards per month: RM " . $cformat;
			echo "<br> <br>";
			echo "Your current bill excluded ICPT and service tax per month: RM " . $billformat;
			}
		}
	?>
		<div id="print">
			<button style="cursor: pointer; width: 70px;" onClick="window.print()">Print</button>
		</div>
	</div>
	
	<p style="text-align:center; margin-top: 100px; color: black;">The app is created by <a href="https://www.linkedin.com/in/nursyafiqah-hapiz/" style="color:blue; text-decoration:none;">SYAFIQAH HAPIZ</a></p>
	</div>
	</body>
</html>