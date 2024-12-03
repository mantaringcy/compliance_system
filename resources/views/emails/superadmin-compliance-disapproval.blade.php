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
                            <h2 style="color: #333333; font-size: 24px; text-align: center;">Compliance Request Disapproved</h2>
                            <p style="color: #666666; font-size: 16px; text-align: center;">* The compliance request has been disapproved. Please review the feedback or contact the administrator for further clarification.
                            </p>

                            <!-- Table of Compliance Data -->
                            <table role="presentation" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Compliance Name</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Reference Date</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Frequency</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Start Working On</th>
                                        <th style="text-align: left; padding: 8px; background-color: #f2f2f2; color: #333333;">Submit On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            {{ $compliance['compliance_name'] }}
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            {{ \Carbon\Carbon::parse($compliance['reference_date'])->format('F j, Y') }}
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            {{ config('static_data.frequency.' . $compliance['frequency']) }}
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            {{ config('static_data.start_working_on.' . $compliance['start_working_on']) }}
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            {{ config('static_data.submit_on.' . $compliance['submit_on']) }}
                                        </td>
                                    </tr>
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