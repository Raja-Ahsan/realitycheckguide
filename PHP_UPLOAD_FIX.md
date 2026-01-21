# Fix PHP Upload Limits for XAMPP

## Problem
Your file (180MB) exceeds the current PHP limits:
- `upload_max_filesize`: 80M
- `post_max_size`: 64M

## Solution: Update php.ini

### Step 1: Find php.ini Location
Run this command to find your php.ini file:
```bash
php --ini
```

### Step 2: Edit php.ini
Open the php.ini file in a text editor (usually located at `D:\xampp\php\php.ini`)

### Step 3: Update These Values
Find and update these lines in php.ini:

```ini
; Maximum allowed size for uploaded files
upload_max_filesize = 200M

; Maximum size of POST data
post_max_size = 200M

; Maximum execution time (for large uploads)
max_execution_time = 300

; Maximum input time
max_input_time = 300

; Memory limit
memory_limit = 256M
```

### Step 4: Restart Apache
1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache again

### Step 5: Verify Changes
Run this command to verify:
```bash
php -i | findstr /i "upload_max_filesize post_max_size"
```

You should see:
```
upload_max_filesize => 200M => 200M
post_max_size => 200M => 200M
```

## Alternative: Quick Fix via .user.ini (if supported)
If your server supports .user.ini files, create one in the public directory with:
```ini
upload_max_filesize = 200M
post_max_size = 200M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

## Note
The .htaccess file already has these settings, but XAMPP on Windows typically runs PHP as CGI/FastCGI, which doesn't respect php_value directives in .htaccess. You must update php.ini directly.
