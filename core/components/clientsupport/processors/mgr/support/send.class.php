<?php
/**
 * Send the support form via email
 *
 * @package clientsupport
 * @subpackage processors
 */
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Mail\modPHPMailer;
use MODX\Revolution\Mail\modMail;
use MODX\Revolution\modX;

class ClientSupportSendProcessor extends Processor
{
    /**
     * @var ClientSupport
     */
    protected $clientsupport;

    /**
     * @param modX $modx
     * @param array $properties
     */
    public function __construct(modX $modx,array $properties = [])
    {
        parent::__construct($modx, $properties);

        $this->clientsupport = $this->modx->services->get('clientsupport');
    }

    /**
     * Process.
     *
     * @return void
     */
    public function process()
    {
        $browser = $this->getBrowser();

        $data = [
            'name'          => $this->getProperty('name'),
            'email'         => $this->getProperty('email'),
            'problem'       => $this->getProperty('problem'),
            'message'       => $this->getProperty('message'),
            'url'           => $this->getProperty('url'),
            'username'      => $this->modx->user->get('username'),
            'ip_address'    => $_SERVER['REMOTE_ADDR'],
            'browser'       => $browser['name'] . ' ' . $browser['version'],
            'platform'      => $browser['platform']
        ];

        $emailTo        = $this->modx->getOption('clientsupport.email_to', null, $this->modx->getOption('emailsender'), true);
        $emailFrom      = $this->modx->getOption('clientsupport.email_from', null, $this->modx->getOption('emailsender'), true);
        $emailFromName  = $this->modx->getOption('clientsupport.email_from_name', null, $this->getProperty('name'), true);
        $emailTpl       = $this->modx->getOption('clientsupport.email_tpl', null, 'email', true);
        $emailMsg       = $this->clientsupport->getChunk($emailTpl, $data);
        $emailSubject   = $this->modx->lexicon('clientsupport.email.subject', ['subject' => $this->getProperty('problem')]);

        $mail = new modPHPMailer($this->modx);
        $mail->set(modMail::MAIL_BODY, $emailMsg);
        $mail->set(modMail::MAIL_FROM, $emailFrom);
        $mail->set(modMail::MAIL_FROM_NAME, $emailFromName);
        $mail->set(modMail::MAIL_SUBJECT, $emailSubject);
        $mail->address('to', $emailTo);
        $mail->address('reply-to', $this->getProperty('email'));
        $mail->setHTML(true);

        if (!$mail->send()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: ' . $mail->mailer->ErrorInfo);
        }

        $mail->reset();

        return;
    }

    /**
     * Get browser information.
     *
     * @return array
     */
    public function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = ['Version', $ub, 'other'];
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            // we will have two since we are not using 'other' argument yet
            // see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        return [
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'   => $pattern
        ];
    }
}

return 'ClientSupportSendProcessor';
