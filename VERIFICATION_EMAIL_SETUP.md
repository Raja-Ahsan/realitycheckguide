# Verification Email Setup (for the team)

## Issue
Users who register (e.g. as Viewers) may see "Your account is not active. Please verify your email" when logging in but **never receive the verification email**. This happens when the app is not configured to send real email.

## Fix in code (done)
- Registration now uses a **database transaction**: if anything fails (including before saving the user), no user record is created, so the username/email is not "reserved."
- Verification email is sent **after** the user is created. If sending fails, the user sees a message to use "Forgot Password" or contact support.
- **Resend verification**: Inactive users can go to "Resend verification email" (linked from the login page when they see the inactive-account message) to request a new verification link.

## Required: Mail configuration for production
For verification emails to be **actually delivered**:

1. **Do not use `MAIL_MAILER=log` in production.** With `log`, emails are only written to `storage/logs/laravel.log` and are never sent to users.

2. **Use real SMTP (or another mailer)** in `.env`:
   - `MAIL_MAILER=smtp`
   - `MAIL_HOST=...` (e.g. your SMTP server)
   - `MAIL_PORT=587`
   - `MAIL_USERNAME=...`
   - `MAIL_PASSWORD=...`
   - `MAIL_ENCRYPTION=tls`
   - `MAIL_FROM_ADDRESS=noreply@yourdomain.com`
   - `MAIL_FROM_NAME="Reality Check Guide"`

3. **Test** by registering a test user and checking that the verification email arrives (and check spam folder).

4. **Optional**: To avoid timeouts and improve delivery, queue the verification mailable (implement `ShouldQueue` on the Mailable and run `php artisan queue:work`).
