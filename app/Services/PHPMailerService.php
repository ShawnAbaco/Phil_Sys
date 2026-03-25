<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    protected $mailer;
    protected $config;

    public function __construct()
    {
        $this->config = config('phpmailer');
        $this->initialize();
    }

    /**
     * Initialize PHPMailer with configuration
     */
    protected function initialize()
    {
        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            if ($this->config['debug'] > 0) {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            }
            
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['username'];
            $this->mailer->Password = $this->config['password'];
            $this->mailer->SMTPSecure = $this->config['encryption'];
            $this->mailer->Port = $this->config['port'];
            $this->mailer->CharSet = 'UTF-8';
            
            // Default from address
            $this->mailer->setFrom(
                $this->config['from_address'], 
                $this->config['from_name']
            );

        } catch (Exception $e) {
            throw new Exception("PHPMailer initialization failed: " . $e->getMessage());
        }
    }

    /**
     * Send a plain text email
     */
    public function sendPlain($to, $subject, $body, $toName = '')
    {
        try {
            // Recipients
            if (!empty($toName)) {
                $this->mailer->addAddress($to, $toName);
            } else {
                $this->mailer->addAddress($to);
            }

            // Content
            $this->mailer->isHTML(false);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception("Email could not be sent. Error: " . $this->mailer->ErrorInfo);
        } finally {
            $this->clearAddresses();
        }
    }

    /**
     * Send an HTML email
     */
    public function sendHTML($to, $subject, $htmlBody, $altBody = '', $toName = '')
    {
        try {
            // Recipients
            if (!empty($toName)) {
                $this->mailer->addAddress($to, $toName);
            } else {
                $this->mailer->addAddress($to);
            }

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $altBody ?: strip_tags($htmlBody);

            return $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception("Email could not be sent. Error: " . $this->mailer->ErrorInfo);
        } finally {
            $this->clearAddresses();
        }
    }

    /**
     * Send email with attachment
     */
    public function sendWithAttachment($to, $subject, $body, $attachmentPath, $attachmentName = '', $isHTML = false)
    {
        try {
            // Recipient
            $this->mailer->addAddress($to);

            // Attachment
            if (file_exists($attachmentPath)) {
                if (!empty($attachmentName)) {
                    $this->mailer->addAttachment($attachmentPath, $attachmentName);
                } else {
                    $this->mailer->addAttachment($attachmentPath);
                }
            } else {
                throw new Exception("Attachment file not found: " . $attachmentPath);
            }

            // Content
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception("Email could not be sent. Error: " . $this->mailer->ErrorInfo);
        } finally {
            $this->clearAddresses();
        }
    }

    /**
     * Send email to multiple recipients
     */
    public function sendBulk($recipients, $subject, $body, $isHTML = false)
    {
        try {
            // Add multiple recipients
            foreach ($recipients as $recipient) {
                if (is_array($recipient) && isset($recipient['email'])) {
                    $name = $recipient['name'] ?? '';
                    if (!empty($name)) {
                        $this->mailer->addAddress($recipient['email'], $name);
                    } else {
                        $this->mailer->addAddress($recipient['email']);
                    }
                } elseif (is_string($recipient)) {
                    $this->mailer->addAddress($recipient);
                }
            }

            // Content
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            if (!$isHTML) {
                $this->mailer->AltBody = strip_tags($body);
            }

            return $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception("Bulk email could not be sent. Error: " . $this->mailer->ErrorInfo);
        } finally {
            $this->clearAddresses();
        }
    }

    /**
     * Clear all addresses for next email
     */
    protected function clearAddresses()
    {
        $this->mailer->clearAddresses();
        $this->mailer->clearAttachments();
        $this->mailer->clearReplyTos();
        $this->mailer->clearCustomHeaders();
    }

    /**
     * Get PHPMailer instance for custom configuration
     */
    public function getMailer()
    {
        return $this->mailer;
    }
}