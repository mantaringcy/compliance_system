<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplianceReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $complianceData;
    public $emailSubject;
    public $emailType; 

    /**
     * Create a new message instance.
     *
     * @param array $complianceData
     * @param string $emailSubject
     * @param string $emailType  // Accept email type for conditional email selection
     */
    public function __construct($complianceData, $emailSubject, $emailType)
    {
        $this->complianceData = $complianceData;
        $this->emailSubject = $emailSubject;
        $this->emailType = $emailType;  // Set email type

        // dd($complianceData[1]['days_left']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Conditionally select the correct blade template
        switch ($this->emailType) {
            case 'monthly':
                $view = 'emails.monthly-compliance-reminder';
                break;
            case 'weekly':
                $view = 'emails.weekly-compliance-reminder';
                break;
            case 'deadline':
                $view = 'emails.deadline-compliance-reminder';
                break;
            default:
                $view = 'emails.default-compliance-reminder';
        }

        return $this->subject($this->emailSubject)
                    ->view($view)
                    ->with([
                        'compliances' => $this->complianceData,
                        'subjectType' => $this->emailSubject,
                        'reminderType' => $this->emailType,
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Compliance Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // view: 'email.compliance-reminder',
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
