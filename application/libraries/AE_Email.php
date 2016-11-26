<?
/*
    Notes
        - We do a > 0 check for emails because emails to us decrement remaining by 2, making it possible that remaining value becomes -1 (if 1 was the previous value). This is less costly than adding more conditions to check the to value
*/


class AE_Email extends CI_Email 
{
    public function __construct()
    {
        parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('email_model');
    }

    // Might just make process_email recursive if array detected
    function process_emails($emails, $force_queue = FALSE)
    {
        $success = TRUE;
        foreach($emails as $email)
        {
            if (!$this->process_email($email, $force_queue))
                $success = FALSE;
        }

        return $success;
    }

    function process_email($email, $force_queue = FALSE)
    {
        if (isset($email['bcc']))
            return $this->process_email_batch($email, $force_queue);
        else
            return $this->process_email_single($email, $force_queue);
    }

    function process_email_single($email, $force_queue = FALSE)
    {
        if ($force_queue)
        {
            if ($this->queue($email))
                return EMAIL_STATUS_QUEUED;
            return;
        }

        $emails_remaining = $this->ci->email_model->get_emails_remaining();

        if ($emails_remaining['hourly'] > 0)
        {
            if ($emails_remaining['hourly'] == 1)
            {
                $this->send_no_emails_remaining_message('hourly');
                // No decrementing, this uses the 10 email reserve
            }

            // Because of forwarding to Gmail, 2 emails are sent for any sent to main
            if ($email['to'] == SITE_EMAIL)
                $decrement = 2;
            else
                $decrement = 1;
            $this->ci->email_model->decrement_emails_remaining($decrement);

            if ($this->make_and_send($email))
                return EMAIL_STATUS_SENT;
            elseif ($this->queue($email)) // Some freak error
            {
                // Should we increment emails remaining again? I think so, but it would depend on why we got here
                return EMAIL_STATUS_QUEUED;
            }
        }
        else
        {
            if (isset($email['priority'])
                && ($email['priority'] >= IMPORTANT_EMAIL_PRIORITY)
                && $emails_remaining['backup'] > 0)
            {
                if ($emails_remaining['backup'] == 1)
                {
                    $this->send_no_emails_remaining_message('backup');
                    // No decrementing, this uses the 10 email reserve
                }

                // Because of forwarding to Gmail, 2 emails are sent for any sent to main
                if ($email['to'] == SITE_EMAIL)
                    $decrement = 2;
                else
                    $decrement = 1;
                $this->ci->email_model->decrement_backup_emails_remaining($decrement);

                if ($this->make_and_send($email))
                    return EMAIL_STATUS_SENT;
                elseif ($this->queue($email)) // Some freak error
                    return EMAIL_STATUS_QUEUED;
            }
            else
            {
                if ($this->queue($email))
                    return EMAIL_STATUS_QUEUED;
            }
        }
    }

    // Batch emails refers to same email with 2+ recipients. Structure is same as regular $email, but multiple 'bcc' values
    function process_email_batch($email, $force_queue = FALSE)
    {
        if ($force_queue)
        {
            if ($this->queue($email))
                return EMAIL_STATUS_QUEUED;
            return;
        }

        $num_of_emails = count($email['bcc']);
        $emails_remaining = $this->ci->email_model->get_emails_remaining();

//        var_dump($emails_remaining);return;

        // Batch emails only work with the regular hourly limit. The backup is...well...backup.
        $emails_remaining = $emails_remaining['hourly'];

        // If all emails can be sent, do it
        if ($emails_remaining - $num_of_emails >= 0)
        {
            if ($emails_remaining - $num_of_emails == 0)
            {
                $this->send_no_emails_remaining_message('hourly');
                // No decrementing, this uses the 10 email reserve
            }

            $this->ci->email_model->decrement_emails_remaining($num_of_emails);
            if ($this->make_and_send($email))
                return EMAIL_STATUS_SENT;
            elseif ($this->queue($email)) // Some freak error
                return EMAIL_STATUS_QUEUED;
        }
        // If only some can be sent, send them and queue the others
        elseif ($emails_remaining > 0)
        {
            $bccs_to_queue = $email['bcc'];
            $email['bcc'] = array_chop($bccs_to_queue, $emails_remaining);

            $this->ci->email_model->decrement_emails_remaining($emails_remaining);  // Decrement to 0

            $this->send_no_emails_remaining_message('hourly'); // No decrementing, this uses the 10 email reserve

            // Glitch here. What if email sends but queueing fails?  :(  <- is what
            if ($this->make_and_send($email))
            {
                $email['bcc'] = $bccs_to_queue;
                $this->queue($email);

                return EMAIL_STATUS_QUEUED;
            }
        }
        // If can't send any (limit already reached), queue them all
        else
        {
            if ($this->queue($email))
                return EMAIL_STATUS_QUEUED;          
        }
    }

    function reset_emails_remaining()
    {
        return $this->ci->email_model->reset_emails_remaining();
    }

    function process_queued_emails()
    {
        $emails = $this->ci->email_model->get_queued_emails();
        $email_ids = array();
        
        foreach($emails as $email)
        {
            $email_ids[] = $email['id'];
        }
        
        if ($this->ci->email_model->delete_queued_emails($email_ids))
            return $this->process_emails($emails);
    }

    function queue($email)
    {
        return $this->ci->email_model->queue($email);
    }

    function make_and_send($email)
    {
        $this->from(MAILER_EMAIL, MAILER_NAME);

        // Why isn't this being set from email.php in config?
        $this->set_mailtype("html");

        // reply-to name will always be set with an email
        if (isset($email['reply_to_name']))
        {
            $this->reply_to($email['reply_to_email'], $email['reply_to_name']);
        }
        // but an email might be set without a name
        elseif (isset($email['reply_to_email']))
        {
            $this->reply_to($email['reply_to_email']);            
        }
        else
        {
            $this->reply_to(MAILER_EMAIL, MAILER_NAME);
        }

        $this->subject($email['subject']);

        $message = $this->make_message($email['template'], $email['data']);
        $alt_message = $this->make_message($email['template'], $email['data'], 'txt');

//        echo $message; echo $alt_message; return;

        $this->message($message);
        $this->set_alt_message($alt_message);

        if (isset($email['to']))
        {
            $this->to($email['to']);
        }
        else
        {
            $this->to('');  // Codeigniter bug fix for only BCC emails
        }

        if (isset($email['bcc']))
        {
            $this->bcc($email['bcc']);
        }

        if ($this->send())
//        if (TRUE)
        {
            return TRUE;
        }
    }

    function make_message($template, $data, $type = 'html')
    {
        $data = (array) $data;
        $data['heading_style'] = $this->ci->load->view("components/email/heading-style", NULL, TRUE);
        $data['header'] = $this->ci->load->view("components/email/header-$type", NULL, TRUE);
        $data['footer'] = $this->ci->load->view("components/email/footer-$type", NULL, TRUE);

        $message = $this->ci->load->view("email/$template-$type", $data, TRUE);
        
        return $message;
    }

    function send_no_emails_remaining_message($type)
    {
        return;

        $email = array(
            'to' => SITE_EMAIL,
            'subject' => "No $type emails remaining",
            'message' => '',
            'alt_message' => '',            
        );

        $this->make_and_send($email);
    }

}

?>