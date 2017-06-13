<?php

namespace Zhiyi\Plus\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Zhiyi\Plus\Models\VerifyCode as VerifyCodeModel;

class VerifyCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var VerifyCodeModel
     */
    protected $verifyCode;

    /**
     * Create a new message instance.
     *
     * @param VerifyCodeModel $verifyCode
     */
    public function __construct(VerifyCodeModel $verifyCode)
    {
        $this->verifyCode = $verifyCode;
    }

    /**
     * @inheritDoc
     */
    public function send(MailerContract $mailer)
    {
        try {
            parent::send($mailer);
        } catch (\Exception $e) {
        } catch (\Throwable $e) {}

        $this->verifyCode->state = isset($e) ? 2 : 1;
        $this->verifyCode->save();

        if (isset($e)) {
            throw $e;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.verify_code')
            ->subject(config('app.name') . '验证码')
            ->with($this->verifyCode->toArray());
    }
}
