<?php

declare(strict_types=1);

namespace Chandler\Email;

use Postmark\PostmarkClient;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;

class Email
{
    public static function send(string $to, string $subject, string $html)
    {
        if (isset(CHANDLER_ROOT_CONF["email"]["postmark"])) {
            return (new PostmarkClient(CHANDLER_ROOT_CONF["email"]["postmark"]["key"]))->sendEmail(
                CHANDLER_ROOT_CONF["email"]["postmark"]["user"],
                $to,
                $subject,
                $html,
                strip_tags($html),
                null,
                true,
                null,
                null,
                null,
                ["Sensitivity" => "Company-Confidential"],
                null,
                "None",
                null,
                CHANDLER_ROOT_CONF["email"]["postmark"]["stream"]
            );
        } else {
            $dsn = sprintf(
                "%s://%s:%s@%s:%s",
                CHANDLER_ROOT_CONF["email"]["ssl"] ? "smtps" : "smtp",
                rawurlencode(CHANDLER_ROOT_CONF["email"]["user"] ?? CHANDLER_ROOT_CONF["email"]["addr"]),
                rawurlencode(CHANDLER_ROOT_CONF["email"]["pass"]),
                CHANDLER_ROOT_CONF["email"]["host"],
                CHANDLER_ROOT_CONF["email"]["port"]
            );
            $transport = Transport::fromDsn($dsn);

            $message = new MimeEmail();
            $message->from(new Address(CHANDLER_ROOT_CONF["email"]["addr"]));
            $message->to($to);
            $message->subject($subject);
            $message->html($html);
            $message->getHeaders()->addTextHeader("Sensitivity", "Company-Confidential");

            $mailer = new Mailer($transport);
            return $mailer->send($message);
        }
    }
}
