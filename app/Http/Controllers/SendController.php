<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Job;
use App\Models\Template;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use stdClass;

class SendController extends Controller
{
    private ?string $subject = null;
    private ?string $body = null;
    private ?stdClass $template = null;
    private ?array $mails = [];

    private function sendAsSeparately(): void
    {
        if (empty($this->mails)) {
            return;
        }

        foreach ($this->mails as $mail) {
            Mail::to($mail)->send(
                new SendMail($this->template->subject, $this->template->body)
            );
        }
    }

    private function sendAsBulk(): void
    {
        if (empty($this->mails)) {
            return;
        }

        Mail::to($this->mails)->send(
            new SendMail($this->template->subject, $this->template->body)
        );
    }

    private function sendAsCC(): void
    {
        $mails = $this->mails;
        $firstMail = array_shift($mails);
        if (empty($firstMail) || empty($mails)) {
            return;
        }

        Mail::to($firstMail)
            ->cc($mails)
            ->send(
                new SendMail($this->template->subject, $this->template->body)
            );
    }

    private function sendAsBCC(): void
    {
        $mails = $this->mails;
        $firstMail = array_shift($mails);
        if (empty($firstMail) || empty($mails)) {
            return;
        }

        Mail::to($firstMail)
            ->bcc($mails)
            ->send(
                new SendMail($this->template->subject, $this->template->body)
            );
    }

    public function index(Job $job): void
    {
        $attributes = $job->getAttributes();
        if (empty($attributes) ||
            empty($attributes['template_id']) ||
            empty($attributes['category_id']) ||
            empty($attributes['send_as'])
        ) {
            Notification::make()->title('Not Sent')->danger()->send();
            return;
        }

        $this->template = DB::table((new Template())->getTable())
            ->select(['subject', 'body'])
            ->where('id', $attributes['template_id'])
            ->first();

        $this->mails = DB::table((new \App\Models\Mail())->getTable())
            ->where('category_id', $attributes['category_id'])
            ->where('is_active', 1)
            ->pluck('email')
            ->toArray();

        switch ($attributes['send_as']) {
            case 'Bulk':
                $this->sendAsBulk();
                break;
            case 'CC':
                $this->sendAsCC();
                break;
            case 'BCC':
                $this->sendAsBCC();
                break;
            case 'Separately':
            default:
                $this->sendAsSeparately();

        }

        Notification::make()->title('Sent')->success()->send();
    }
}
