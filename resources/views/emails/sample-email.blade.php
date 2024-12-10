<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance Reminder</title>
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
                            <h2 style="color: #131920; font-size: 24px; text-align: center;">New Compliance Created</h2>
                            <p style="color: #666666; font-size: 16px; text-align: center;">A new compliance has been created. Please review and address this requirement as soon as possible.</p>

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
                                        <th style="text-align: left; padding: 10px;">Reference Date</th>
                                        <th style="text-align: left; padding: 10px;">Frequency</th>
                                        {{-- <th style="text-align: left; padding: 10px;">Start Working On</th> --}}
                                        {{-- <th style="text-align: left; padding: 10px;">Submit On</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        style="
                                            height: 39px;
                                            border-top: 2px solid #E7EAEE;
                                            color: #131920;
                                            ">
                                        <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">
                                            Compliance Name
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">
                                            January 01, 2001
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #E7EAEE;">
                                            Monthly
                                        </td>
                                        {{-- <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            February 01, 2001
                                        </td>
                                        <td style="padding: 8px; border-bottom: 1px solid #ddd; color: #333333;">
                                            March 01, 2001
                                        </td> --}}
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Footer section -->
                            <p style="color: #999999; font-size: 14px; text-align: center; margin-top: 20px;">
                                This is an automated reminder. Please contact us if you have any questions.
                            </p>

                            <!-- Clickable link to compliance module -->
                            <p style="text-align: center; margin: 0; font-size: 14px; color: #4680FF;">
                                <span>
                                    <a href="http://compliance_system.test/compliances" style="color: #4680FF;">Click here</a>
                                </span>
                                to view full compliance details
                            </p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>