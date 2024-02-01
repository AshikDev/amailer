<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Email;
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
    private ?array $accounts = null;

    private function sendAsSeparately(): void
    {
        if (empty($this->accounts)) {
            return;
        }

        foreach ($this->accounts as $mail) {
            Mail::to($mail)->send(
                new SendMail($this->template->subject, $this->template->body)
            );
        }
    }

    private function sendAsBulk(): void
    {
        if (empty($this->accounts)) {
            return;
        }

        Mail::to($this->accounts)->send(
            new SendMail($this->template->subject, $this->template->body)
        );
    }

    private function sendAsCC(): void
    {
        $mails = $this->accounts;
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
        $mails = $this->accounts;
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

        if (empty($this->template->subject) || empty($this->template->body)) {
            return;
        }

        $email = DB::table((new Email())->getTable())
            ->select('account')
            ->where('category_id', $attributes['category_id'])
            ->where('is_active', 1)
            ->first();

        if (empty($email->account)) {
            return;
        }

        $emailArray = array_map('trim', explode(',', $email->account));
        $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        $this->accounts = array_filter($emailArray, function($email) use ($regex) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match($regex, $email);
        });
        $this->accounts = array_unique($this->accounts);

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
