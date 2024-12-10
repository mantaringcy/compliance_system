<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance Reminder | Weekly Compliance Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #F8F9FA; margin: 0; padding: 0;">

    <!-- Main container -->
    <table role="presentation" style="width: 100%; background-color: #F8F9FA; padding: 20px;">
        <tr>
            <td align="center">
                <!-- Inner content wrapper -->
                <table role="presentation" style="background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #E7EAEE;">
                    <tr>
                        <td>
                            <h2 style="color: #131920; font-size: 24px; text-align: center;">Weekly Compliance Reminder</h2>
                            <p style="color: #666666; font-size: 16px; text-align: center;">This is a reminder for the status of your compliance this week.</p>

                            <!-- Table of Compliance Data -->
                            <table role="presentation" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                <thead
                                    style="
                                            background: #FCFCFC;
                                            border-top: 1px solid #E7EAEE;
                                            border-bottom: 1px solid #E7EAEE;
                                            height: 37px;
                                            font-size: 13px;
                                            font-weight: bold;
                                            color: #131920;
                                            text-transform: uppercase;
                                        ">
                                    <tr>
                                        <th style="text-align: left; padding: 10px;">Compliance Name</th>
                                        <th style="text-align: left; padding: 10px;">Start Date</th>
                                        <th style="text-align: left; padding: 10px;">Submit Date</th>
                                        <th style="text-align: left; padding: 10px;">Deadline</th>
                                        <th style="text-align: left; padding: 10px;">Days Left</th>
                                        <th style="text-align: left; padding: 10px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($compliances as $compliance)
                                        <tr
                                            style="
                                                height: 39px;
                                                border-top: 2px solid #E7EAEE;
                                                color: #131920;
                                                {{ $compliance['status'] == 'completed' ? 'background: #F0F0F0;' : '' }}
                                            ">
                                            <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">{{ $compliance['compliance_name'] }}</td>
                                            <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">
                                                {{ \Carbon\Carbon::parse($compliance['computed_start_date'])->format('F j, Y') }}
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">
                                                {{ \Carbon\Carbon::parse($compliance['computed_submit_date'])->format('F j, Y') }}
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">
                                                {{ \Carbon\Carbon::parse($compliance['computed_deadline'])->format('F j, Y') }}
                                            </td>
                                            @if ($compliance['status'] == 'completed')
                                                <td 
                                                    colspan="2" 
                                                    style="
                                                        padding: 10px; 
                                                        border-bottom: 1px solid #E7EAEE; 
                                                        color: #131920; 
                                                        text-align: center;"
                                                    >
                                                    <span 
                                                        style="
                                                            background-color: #2CA87F; 
                                                            color: white; 
                                                            padding: 0.25em 0.5em; 
                                                            border-radius: 0.25rem; 
                                                            font-size: 10.5px; 
                                                            font-weight: 700;
                                                            text-align: center;
                                                            ">
                                                        COMPLIED ✓
                                                    </span>
                                                </td>
                                            @else
                                                <td style="padding: 10px; border-bottom: 1px solid #E7EAEE;">
                                                    @if ($compliance['days_left'] < 0)
                                                        <span style="color: #DC2625;">
                                                            {{ abs($compliance['days_left']) }} days overdue
                                                        </span>
                                                    @else
                                                        <span style="color: #2CA87F;">
                                                            {{ abs($compliance['days_left']) }} days left
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #E7EAEE;">
                                                    @if ($compliance['status'] == 'in_progress')
                                                        <span 
                                                            style="
                                                                background-color: #4680FF; 
                                                                color: white; 
                                                                padding: 0.25em 0.5em; 
                                                                border-radius: 0.25rem; 
                                                                font-size: 10.5px; 
                                                                font-weight: 700; 
                                                            "
                                                        >
                                                            IN PROGRESS
                                                        </span>
                                                    @elseif ($compliance['status'] == 'pending')
                                                        <span 
                                                            style="
                                                                background-color: #E48A01; 
                                                                color: white; 
                                                                padding: 0.25em 0.5em; 
                                                                border-radius: 0.25rem; 
                                                                font-size: 10.5px; 
                                                                font-weight: 700;
                                                                text-align: center;
                                                            "
                                                        >
                                                            PENDING
                                                        </span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Footer section -->
                            <p style="color: #999999; font-size: 14px; text-align: center; margin-top: 20px;">
                                This is an automated reminder. Please contact us if you have any questions.
                            </p>

                            <!-- Clickable link to compliance module -->
                            <p style="text-align: center; margin: 0; font-size: 14px; color: #4680FF;">
                                <span>
                                    <a href="http://compliance_system.test/overview" style="color: #4680FF;">Click here</a>
                                </span>
                                to view compliance due
                            </p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>