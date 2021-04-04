<?php 
//include smtp_mail_function and database_connection file
include '../../../cron/smtp_mail_common_function.php';
include '../../../db/db2.php';

//db connection establish
$ob = new db_init();
if($ob)
{
 $con = $ob->db_conn();
}

// fetch email id of school table and store into an array
$sql = "select emailid from schools";
$result = $con->query( $sql );
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
	$school_emails[] = $row['emailid'];
	}
}else{
	echo "database connection error";
}

//for testing whether email is triggered or not
// $school_emails = array('email_id1','email_id2',.....);

//for counting
$email_sent_count=0;
$email_not_sent_count=0;
$total_emailid_count=count($school_emails);

//send mail function
sendEmailToSchools($school_emails,$email_sent_count,$email_not_sent_count,$total_emailid_count);

function sendEmailToSchools($school_emails,$email_sent_count,$email_not_sent_count,$total_emailid_count){
	
	//sender name and mailid
	$e_from_name="edustoke";
	$e_from='no-reply@edustoke.com';
	
	//mail subject and body
	$e_subject="new notification";
	$e_body="<p>.............</p>";

	//sending mails to each school mail id and count for success or failed
	if($total_emailid_count>0){
		foreach ($school_emails as $each_mail) 
		{	
			$email_sent=sendSMTPmail($each_mail, $e_from, $e_from_name, $e_subject, $e_body);	
			if($email_sent){
				$email_sent_count=$email_sent_count+1;
			}else{
				$email_not_sent=$email_not_sent+1;
			}
		}

	//give output of counts
	if ($email_sent_count>0) {
		
		echo "<h3>Total Number of Emails id in the list: <span style='color:red;'>".$total_emailid_count."</span></h3>";
		echo "<hr>";
		echo "<h3>Number of Emails Sent: <span style='color:red;'>".$email_sent_count."</span></h3>";
		echo "<hr>";
		echo "<h3>Number of Emails not Sent: <span style='color:red;'>".$email_not_sent_count."</span></h3>";
		echo "<hr>";
	}
	}
	else{
			echo '<script>alert("Emails list is empty...")</script>';
	}
}
		
?>