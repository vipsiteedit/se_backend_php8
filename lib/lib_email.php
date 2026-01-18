<?php

/**
 * E-mail checker by TuiKiken
 *
 * @param string $email
 * @return array
 */
function emailChecker($email)
{

    $result = null;

    $errors = array(
        'syntax_error' => array('code' => 'syntax_error', 'message' => 'Invalid e-mail syntax'),
        'dns_error'    => array('code' => 'dns_error', 'message' => 'Invalid e-mail DNS'),
        'smtp_error'   => array('code' => 'smtp_error', 'message' => 'Error with SMTP validation')
    );

    // Step 1: syntax validation
    if (!preg_match("/^[-a-z0-9!#$%&'*+\/=?^_`{|}~]+(?:\.[-a-z0-9!#$%&'*+\/=?^_`{|}~]+)*@((?:[a-z0-9](?:[-a-z0-9]{0,61}[a-z0-9])?\.)*(?:[a-z]{2,6}))$/", $email, $domain)) {
        $result = array('result' => false, 'error' => $errors['syntax_error']);

        // Step 2: DNS validation
    } else if (!checkdnsrr($domain[1], 'MX')) {
        $result = array('result' => false, 'error' => $errors['dns_error']);

        // Step 3: SMTP validation
    } else {

        require_once dirname(__FILE__) . '/emailvalid/smtp_validateEmail.class.php';

        $SMTP_Validator = new SMTP_validateEmail();
        $SMTP_Validator->max_read_time = 5;
        $SMTP_Validator->max_conn_time = 60;
        //$SMTP_Validator->debug = true;

        $smtpResult = $SMTP_Validator->validate(array($email));

        if (!$smtpResult[$email]) {
            $result = array('result' => false, 'error' => $errors['smtp_error']);
        } else {
            $result = array('result' => true, 'error' => null);
        }
    }

    return $result;
}
