<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

// an email address that will be in the From field of the email.
$from = '<info@insiderjourneys.com.au>';

// an email address that will receive the email with the output of the form
$sendTo = '<kyle@focus.asia>';

// subject of the email
$subject = 'Enquiry from Online Contact Form';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('first-name' => 'First Name', 'last-name' => 'Last Name', 'phone' => 'Phone', 'email' => 'Email', 'comments' => 'Additonal Comments', 'country' => 'Country of Residence', 'travel-month' => 'Preferred Travel Month', 'travel-year' => 'Preferred Travel Year', 'duration' => 'Travel Duration', 'contact-email' => 'I prefer to be contacted via email.', 'contact-call' => 'I prefer receiving a call.', 'contact-surprise' => 'Surprise me! I prefer recieving an email or a call.', 'country' => 'Where would you like to go?', 'title' => 'Title', 'newsletter' => 'Sign me up for the Journeys newsletter.',);

// message that will be displayed when everything is OK :)
$okMessage = 'Thanks! A representative from Insider-Journeys will be in touch shortly.';

// If something goes wrong, we will display this message.
$errorMessage = 'Oh no! There is an error submitting the form. Please try again.';

/*
 *  LET'S DO THE SENDING
 */

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(0);

try
{

    if(count($_POST) == 0) throw new \Exception('Form is empty');

    $emailText = "You have a new message from your contact form\n=============================\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // All the neccessary headers for the email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );

    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}


// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
