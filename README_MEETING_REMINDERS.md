# Meeting Reminder System - Login-Based Version

This system automatically sends email reminders to meeting participants when they log in to the system, showing only meetings they are participating in that are scheduled within 12 hours.

## Features

- **Login-Based Reminders**: Instead of running every minute, reminders are sent when users log in or access the dashboard
- **Personalized**: Each user only receives reminders for meetings they are participating in
- **Smart Timing**: Only sends reminders for meetings within 12 hours from login time
- **No Duplicates**: Users won't receive multiple reminders for the same meeting during their session
- **Dashboard Reminders**: Additional reminders sent when users access the main dashboard

## How It Works

1. **User Login**: When a user logs in, the system checks for upcoming meetings within 12 hours
2. **Dashboard Access**: Additional check when user accesses the main dashboard
3. **Personalized Query**: Only finds meetings where the logged-in user is a participant
4. **Send Emails**: Sends personalized HTML emails with meeting details and time remaining
5. **Log Results**: Records success/failure in application logs

## Setup Instructions

### 1. Email Configuration

Update `beholf/app/Config/Email.php` with your SMTP settings:

```php
public string $SMTPHost = 'smtp.gmail.com';
public string $SMTPUser = 'your-email@gmail.com';
public string $SMTPPass = 'your-app-password';
public string $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

### 2. Testing

1. Create a test meeting scheduled for within 12 hours from now
2. Add participants with valid email addresses
3. Login to the system
4. Check application logs for results

## Email Content

The reminder emails include:
- Meeting title
- Scheduled date and time
- Location
- Accurate hours remaining until meeting
- Professional HTML formatting

## Files Modified/Created

- `beholf/app/Controllers/Reminder.php` - New controller for handling reminders
- `beholf/app/Controllers/Login.php` - Added reminder call on login
- `beholf/app/Controllers/Admin.php` - Added reminder call on dashboard load
- `beholf/app/Config/Email.php` - SMTP configuration

## Advantages of Login-Based Approach

- **Resource Efficient**: No need for cron jobs running every minute
- **User-Centric**: Reminders sent exactly when users are active
- **Reduced Server Load**: Only processes when users are logged in
- **Better User Experience**: Immediate feedback when accessing the system

## Troubleshooting

- **No emails sent**: Check SMTP configuration and logs
- **Permission issues**: Make sure the web server can send emails
- **Email credentials**: Use app passwords for Gmail instead of main password

## Security Notes

- Store email credentials securely
- Use app passwords for Gmail instead of main password
- Consider adding rate limiting for reminder checks
