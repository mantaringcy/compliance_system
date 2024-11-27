<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

    <!-- Main container -->
    <table role="presentation" style="width: 100%; background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <!-- Inner content wrapper -->
                <table role="presentation" style="background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                    <tr>
                        <td>
                            <h2 style="color: #333333; font-size: 24px; text-align: center;">Upcoming Compliance Deadlines</h2>
                            <p style="color: #666666; font-size: 16px; text-align: center;">This is a reminder to review and address your upcoming compliance obligations. These compliances are due 5 days from now.</p>

                            <!-- Table of Compliance Data -->
                            <table role="presentation" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Compliance Name</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Start Working On</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Submit On</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Deadline</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Days Left</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($monthlyCompliances as $compliance) --}}
                                    @foreach ($compliances as $compliance)
                                        <tr>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">{{ $compliance['compliance_name'] }}</td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                                {{ \Carbon\Carbon::parse($compliance['computed_start_date'])->format('F j, Y') }}
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                                {{ \Carbon\Carbon::parse($compliance['computed_submit_date'])->format('F j, Y') }}
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                                {{ \Carbon\Carbon::parse($compliance['computed_deadline'])->format('F j, Y') }}
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                                @if($compliance['days_left'] < 0)
                                                    <span style="color: #DC2625;">
                                                        {{ abs($compliance['days_left']) }} days overdue
                                                    </span>
                                                @else
                                                    <span style="color: #2CA87F;">
                                                        {{ abs($compliance['days_left']) }} days left
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                                {{-- {{ $compliance->status }} --}}
                                                @if($compliance['status'] == 'completed')
                                                    <span style="background-color: #2CA87F; color: white; padding: 0.25em 0.5em; border-radius: 0.25rem; font-size: 14px; font-weight: 600;">COMPLIED</span>
                                                @elseif($compliance['status'] == 'in_progress')
                                                    <span style="background-color: #E3ECFF; color: #4680FF; padding: 0.25em 0.5em; border-radius: 0.25rem; font-size: 14px; font-weight: 600;">IN PROGRESS</span>
                                                @elseif($compliance['status'] == 'pending')
                                                    <span style="background-color: #FBECDE; color: #E48A01; padding: 0.25em 0.5em; border-radius: 0.25rem; font-size: 14px; font-weight: 600;">PENDING</span>
                                                @endif
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Footer section -->
                            <p style="color: #999999; font-size: 14px; text-align: center; margin-top: 20px;">
                                This is an automated reminder. Please contact us if you have any questions.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>