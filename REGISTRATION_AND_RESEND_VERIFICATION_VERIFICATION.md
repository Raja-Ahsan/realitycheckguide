# Step-by-step verification: Registration transaction & Resend verification

This document walks through the code to confirm that (1) registration never saves the user when something fails, and (2) resend verification works end-to-end.

---

## Part 1: Registration (`storeUser`) – transaction and “no save on failure”

**File:** `app/Http/Controllers/WebController.php` (lines 425–534)

### Step 1: Validation (before any DB write)

```php
$this->validate($request, [
    'name' => 'required|...',
    'email' => 'required|email|unique:users,email',
    ...
]);
if ($request->amount > 0) {
    $this->validate($request, ['stripeToken' => 'required']);
}
```

- If validation fails, Laravel redirects back with errors and **no user is created**.
- Email is only checked for uniqueness here; the row is not inserted yet.

### Step 2: Payment (paid registration only)

```php
if ($request->amount > 0) {
    // Stripe Customer + Charge
    try { ... } catch (\Exception $e) {
        return back()->withErrors([...])->withInput();  // no user created
    }
    if ($response->status !== 'succeeded') {
        return back()->withErrors([...])->withInput();  // no user created
    }
}
```

- If Stripe fails or status is not `succeeded`, we return immediately. **No user is created.**

### Step 3: Generate token (outside transaction)

```php
do {
    $verify_token = uniqid();
} while (User::where('verify_token', $verify_token)->first());
```

- Only generates a unique string. No user row is written here.

### Step 4: Database transaction (user + payment)

```php
try {
    DB::beginTransaction();

    $user = User::create([...]);           // ① User row
    $user->assignRole(...);               // ② Role (can throw)
    $this->handleRoleSpecificRegistration(...);
    Payment::create([...]);               // ③ Payment row
    if ($response && ...) {
        PaymentDetail::create([...]);     // ④ PaymentDetail (paid only)
    }

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();   // ← Any exception: ALL of ①–④ are rolled back
    Log::error(...);
    return back()->withErrors([...])->withInput();
}
```

- **User, role, payment (and payment detail) are created only inside the transaction.**
- If **any** of these throw (e.g. `assignRole`, `Payment::create`, DB error), we hit `catch`, run `DB::rollBack()`, and redirect with an error. **No user and no payment rows are persisted.**

### Step 5: Send verification email (after commit)

```php
try {
    Mail::to($user->email)->send(new \App\Mail\Email($details));
} catch (\Exception $e) {
    Log::error('Verification email failed: ...');
    return redirect()->route('login')->with('warning', 'Account created successfully, but we could not send...');
}
return redirect()->route('login')->with('message', 'Registration successful! ...');
```

- Email is sent **after** `DB::commit()`, so the user already exists.
- If sending fails, we do **not** rollback (transaction is already committed). We redirect with a **warning** and tell the user to use “Forgot Password” or contact support.
- If sending succeeds, we redirect with a **success message**.

**Conclusion (Part 1):**  
- If validation fails → no user.  
- If Stripe fails or does not succeed → no user.  
- If anything inside the transaction fails → `rollBack()` → no user, no payment.  
- Only after a successful `commit()` is the user saved; then we attempt the verification email and handle send failure with a warning.

---

## Part 2: Resend verification flow

**Files:**  
- `app/Http/Controllers/WebController.php` – `resendVerificationForm`, `resendVerification`  
- `resources/views/auth/resend-verification.blade.php`  
- `resources/views/auth/login.blade.php` (resend link when inactive)  
- `app/Http/Controllers/admin/UserController.php` – `authenticate` (inactive user)

### Step 1: User tries to log in with unverified account

**File:** `app/Http/Controllers/admin/UserController.php`

```php
} elseif (!empty($user) && $user->status == 0) {
    return redirect()->back()
        ->with('error', 'Your account is not active. Please verify your email (check your inbox and spam folder).')
        ->with('show_resend_verification', true)
        ->withInput($request->only('email'));
}
```

- When the user exists but `status == 0`, we redirect back with an error, set `show_resend_verification`, and keep their email in the session for the login form.

### Step 2: Login page shows error + resend link

**File:** `resources/views/auth/login.blade.php`

```blade
@if (Session::has('error'))
    <p class="alert alert-danger">...</p>
    @if (Session::has('show_resend_verification'))
    <p class="small mb-2"><a href="{{ route('resend-verification') }}">Didn't receive the verification email? Click here to resend.</a></p>
    @endif
@endif
```

- When the “inactive account” error is shown, the “Click here to resend” link is displayed and points to `route('resend-verification')`.

### Step 3: Resend form (GET)

**Route:** `GET /resend-verification` → `WebController::resendVerificationForm`

- Renders `auth.resend-verification` with a form that POSTs to `route('resend-verification.send')`.

### Step 4: Resend submit (POST)

**Route:** `POST /resend-verification` → `WebController::resendVerification`

**File:** `app/Http/Controllers/WebController.php`

```php
$this->validate($request, ['email' => 'required|email']);

$user = User::where('email', $request->email)->where('status', 0)->first();
if (!$user) {
    return redirect()->back()
        ->with('error', 'No inactive account found with this email. ...')
        ->withInput($request->only('email'));
}

do {
    $verify_token = uniqid();
} while (User::where('verify_token', $verify_token)->first());
$user->verify_token = $verify_token;
$user->save();

try {
    Mail::to($user->email)->send(new \App\Mail\Email($details));
} catch (\Exception $e) {
    Log::error('Resend verification email failed: ...');
    return redirect()->route('login')->with('error', 'We could not send the verification email. ...');
}

return redirect()->route('login')->with('message', 'Verification email sent! ...');
```

- Only users with `status == 0` can get a new link (no resend for already-active accounts).
- New token is generated and saved, then the same verification mailable is sent.
- If send fails, user is redirected to login with an error; if it succeeds, with a success message.

### Step 5: User clicks link in email

- Verification link uses the same `email-verification/{token}` route as initial registration (`WebController::verifyEmail`).
- That method sets `status = 1`, clears `verify_token`, and shows the “Email Verified Successfully” view. After that, login works.

**Conclusion (Part 2):**  
- Inactive login → error + “resend” link.  
- Resend form → validate email → find inactive user → new token → send same verification email → redirect to login with message or error.  
- User verifies via the same flow as on signup; no duplicate logic.

---

## Summary

| Check | Result |
|-------|--------|
| Validation failure → user saved? | No – redirect before any DB write. |
| Stripe failure → user saved? | No – return before transaction. |
| Exception inside transaction → user saved? | No – `DB::rollBack()` in catch. |
| Verification email send failure after commit → user saved? | Yes (by design); user is told to use Forgot Password or support. |
| Resend link shown when status = 0? | Yes – when `show_resend_verification` is set on redirect. |
| Resend only for inactive users? | Yes – `User::where('email', ...)->where('status', 0)`. |
| Same verification link/route for signup and resend? | Yes – same token and `verifyEmail($token)`. |

All mentioned points for registration transaction and resend verification are implemented and consistent with the code.
