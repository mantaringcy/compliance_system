<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplianceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $compliance; // To store compliance data
    public $emailType;  // The email type

    /**
     * Create a new message instance.
     *
     * @param  array  $compliance
     * @param  string $emailType
     * @return void
    */

    public function __construct($compliance, $emailType)
    {
        $this->compliance = $compliance;
        $this->emailType = $emailType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Determine the view based on the email type
        switch ($this->emailType) {
            case 'create-superadmin':
                $view = 'emails.superadmin-compliance-created';
                break;

            case 'create-user':
                $view = 'emails.user-compliance-request-create';
                break;

            case 'update-superadmin':
                $view = 'emails.superadmin-compliance-updated';
                break;

            case 'update-user':
                $view = 'emails.user-compliance-request-update';
                break;

            case 'delete-superadmin':
                $view = 'emails.superadmin-compliance-deleted';
                break;

            case 'delete-user':
                $view = 'emails.user-compliance-request-delete';
                break;

            case 'approve-create-superadmin':
                $view = 'emails.superadmin-compliance-approval-create';
                break;

            case 'approve-update-superadmin':
                $view = 'emails.superadmin-compliance-approval-update';
                break;

            case 'approve-delete-superadmin':
                $view = 'emails.superadmin-compliance-approval-delete';
                break;

            case 'disapprove-superadmin':
                $view = 'emails.superadmin-compliance-disapproval';
                break;

            default:
                $view = 'emails.default_compliance';
        }

        return $this->subject('Compliance Notification')
                    ->view($view)
                    ->with([
                        'compliance' => $this->compliance
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Compliance Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            //
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
